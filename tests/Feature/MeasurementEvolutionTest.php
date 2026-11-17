<?php

namespace Tests\Feature;

use App\Models\DadoBiomedico;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class MeasurementEvolutionTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_requires_authentication(): void
    {
        $this->get(route('home'))->assertRedirect(route('login'));
    }

    public function test_evolution_preserves_individual_values_in_chronological_order_and_isolates_accounts(): void
    {
        $owner = User::factory()->create();
        $data = $this->dataFor($owner);
        $other = $this->dataFor(User::factory()->create(), 'Registro de outra conta');
        $this->measurement($other, '999', '2026-06-01 08:00:00', 'Segredo de outra conta');
        $this->measurement($data, '82.1', '2026-06-10 08:00:00', 'Terceira');
        $this->measurement($data, '82,4', '2026-06-01 08:00:00', 'Primeira');
        $this->measurement($data, '82.3', '2026-06-01 20:00:00', 'Segunda');

        $response = $this->actingAs($owner)->get(route('home', ['dado_biomedico_id' => $data->id]));
        $response->assertOk()->assertSee('Evolução das medições')
            ->assertDontSee('Registro de outra conta')->assertDontSee('Segredo de outra conta');
        $chart = $response->viewData('chartData');
        $points = $chart['evolucao']['pontos'];
        $this->assertSame(['Primeira', 'Segunda', 'Terceira'], $points->pluck('nome')->all());
        $this->assertSame([82.4, 82.3, 82.1], $points->pluck('y')->all());
        $this->assertSame(12 * 3600 * 1000, $points[1]['x'] - $points[0]['x']);
        $this->assertSame('01/06/2026 08:00:00', $points[0]['data']);
        $this->assertSame('kg', $chart['evolucao']['unidade']);
        $this->assertSame('UTC', $chart['evolucao']['fusoHorario']);
        $this->assertSame(1, $response->viewData('totalDadosBiomedicos'));
        $this->assertSame(3, $response->viewData('totalMedicoes'));
        $this->assertSame([2, 1], $chart['medicoesPorDia']['datasets'][0]['data']->all());
        $this->assertSame([3], $chart['medicoesPorDado']['values']->all());
        $this->assertSame([1], $chart['dadosPorCategoria']['values']->all());
    }

    public function test_selecting_another_account_or_missing_record_returns_not_found(): void
    {
        $owner = User::factory()->create();
        $foreign = $this->dataFor(User::factory()->create());
        $this->actingAs($owner)->get(route('home', ['dado_biomedico_id' => $foreign->id]))->assertNotFound();
        $this->get(route('home', ['dado_biomedico_id' => 999999]))->assertNotFound();
    }

    public function test_default_selection_prefers_own_data_with_measurements(): void
    {
        $owner = User::factory()->create();
        $this->dataFor($owner, 'A sem medições');
        $data = $this->dataFor($owner, 'Peso');
        $this->measurement($data, '80', '2026-06-01 08:00:00');

        $this->actingAs($owner)->get(route('home'))->assertOk()->assertViewHas('dadoSelecionado', fn ($selected) => $selected->id === $data->id);
    }

    #[DataProvider('periods')]
    public function test_date_filters_include_whole_days_and_only_affect_the_evolution_panel(array $filters, array $expected): void
    {
        $owner = User::factory()->create();
        $data = $this->dataFor($owner);
        foreach (['2026-05-31 23:59:59', '2026-06-01 00:00:00', '2026-06-01 23:59:59', '2026-06-02 00:00:00'] as $index => $date) {
            $this->measurement($data, (string) ($index + 1), $date);
        }

        $response = $this->actingAs($owner)->get(route('home', ['dado_biomedico_id' => $data->id] + $filters));
        $response->assertOk();
        $this->assertSame($expected, $response->viewData('chartData')['evolucao']['pontos']->pluck('y')->all());
        $this->assertSame(4, $response->viewData('totalMedicoes'));
        $this->assertSame([1, 2, 1], $response->viewData('chartData')['medicoesPorDia']['datasets'][0]['data']->all());
        $this->assertSame('2026-06-02 00:00:00', $response->viewData('ultimaMedicao')->data_hora->format('Y-m-d H:i:s'));
        $response->assertSee(route('home', ['dado_biomedico_id' => $data->id]).'#evolucao-medicoes', false);
    }

    public static function periods(): array
    {
        return [
            'single day' => [['data_inicio' => '2026-06-01', 'data_fim' => '2026-06-01'], [2.0, 3.0]],
            'only start' => [['data_inicio' => '2026-06-01'], [2.0, 3.0, 4.0]],
            'only end' => [['data_fim' => '2026-06-01'], [1.0, 2.0, 3.0]],
            'all history' => [[], [1.0, 2.0, 3.0, 4.0]],
        ];
    }

    #[DataProvider('invalidFilters')]
    public function test_invalid_filters_are_rejected(array $filters, string $field): void
    {
        $this->actingAs(User::factory()->create())->getJson(route('home', $filters))->assertUnprocessable()->assertJsonValidationErrors($field);
    }

    public static function invalidFilters(): array
    {
        return [
            'invalid day' => [['data_inicio' => '2026-02-30'], 'data_inicio'],
            'incorrect format' => [['data_fim' => '01/06/2026'], 'data_fim'],
            'reversed range' => [['data_inicio' => '2026-06-02', 'data_fim' => '2026-06-01'], 'data_fim'],
            'invalid identifier' => [['dado_biomedico_id' => 'abc'], 'dado_biomedico_id'],
            'invalid identifier array' => [['dado_biomedico_id' => ['1']], 'dado_biomedico_id'],
        ];
    }

    public function test_web_validation_displays_a_readable_error(): void
    {
        $this->actingAs(User::factory()->create())->from(route('home'))->get(route('home', [
            'data_inicio' => '2026-06-02', 'data_fim' => '2026-06-01',
        ]))->assertRedirect(route('home'))->assertSessionHasErrors('data_fim');
        $this->get(route('home'))->assertSee('A data final deve ser igual ou posterior à data inicial.');
    }

    public function test_zero_negative_and_same_time_readings_are_preserved_and_text_is_not_converted(): void
    {
        $owner = User::factory()->create();
        $data = $this->dataFor($owner);
        $values = ['0', '-2,5', 'positivo', '120/80', '95 mg/dL'];
        foreach ($values as $value) {
            $this->measurement($data, $value, '2026-06-01 08:00:00');
        }

        $response = $this->actingAs($owner)->get(route('home'));
        $response->assertOk()->assertSee('Medições sem um valor numérico representável:');
        $evolution = $response->viewData('chartData')['evolucao'];
        $this->assertSame([0.0, -2.5], $evolution['pontos']->pluck('y')->all());
        $this->assertSame(1, $evolution['pontos']->pluck('x')->unique()->count());
        $this->assertSame(3, $evolution['naoNumericas']);
        $this->assertSame(5, $evolution['totalPeriodo']);
        $this->assertSame($values, $data->medicoes()->orderBy('id')->pluck('valor')->all());
    }

    public function test_a_single_zero_reading_is_shown_as_a_point(): void
    {
        $owner = User::factory()->create();
        $this->measurement($this->dataFor($owner), '0', '2026-06-01 08:00:00');

        $response = $this->actingAs($owner)->get(route('home'));
        $response->assertOk()->assertSee('id="chartEvolucaoMedicoes"', false)->assertSee('Há apenas uma medição numérica');
        $this->assertSame([0.0], $response->viewData('chartData')['evolucao']['pontos']->pluck('y')->all());
    }

    public function test_text_only_and_empty_periods_have_distinct_messages(): void
    {
        $owner = User::factory()->create();
        $data = $this->dataFor($owner);
        $this->measurement($data, 'positivo', '2026-06-01 08:00:00');

        $this->actingAs($owner)->get(route('home'))->assertOk()
            ->assertSee('Não há medições numéricas para representar neste período.')
            ->assertDontSee('id="chartEvolucaoMedicoes"', false);
        $response = $this->get(route('home', ['data_inicio' => '2026-07-01']));
        $response->assertOk()->assertSee('Não há medições para este dado biomédico no período selecionado.');
        $this->assertSame(0, $response->viewData('chartData')['evolucao']['naoNumericas']);
    }

    public function test_new_accounts_and_data_without_measurements_have_helpful_empty_states(): void
    {
        $owner = User::factory()->create();
        $this->actingAs($owner)->get(route('home'))->assertOk()->assertSee('Cadastre um dado biomédico');
        $data = $this->dataFor($owner);
        $this->get(route('home'))->assertOk()
            ->assertViewHas('dadoSelecionado', fn ($selected) => $selected->id === $data->id)
            ->assertSee('Não há medições para este dado biomédico no período selecionado.');
    }

    public function test_user_labels_cannot_break_out_of_the_chart_script(): void
    {
        $owner = User::factory()->create();
        $payload = '</script><script>alert(1)</script>';
        $this->measurement($this->dataFor($owner, $payload), '80', '2026-06-01 08:00:00', $payload);

        $this->actingAs($owner)->get(route('home'))->assertOk()->assertDontSee($payload, false)->assertSee(e($payload), false);
    }

    private function dataFor(User $owner, string $name = 'Peso corporal'): DadoBiomedico
    {
        return $owner->dadosBiomedicos()->create(['nome' => $name, 'unidade_medida' => 'kg', 'valor_referencia' => 'Individual']);
    }

    private function measurement(DadoBiomedico $data, string $value, string $date, string $name = 'Aferição'): void
    {
        $data->medicoes()->create(['nome' => $name, 'valor' => $value, 'data_hora' => $date]);
    }
}
