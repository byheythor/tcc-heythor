<?php

namespace Tests\Feature;

use App\Models\DadoBiomedico;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DailyMeasurementChartTest extends TestCase
{
    use RefreshDatabase;

    public function test_each_owned_record_has_daily_counts_on_the_same_dates_including_zero_counts(): void
    {
        $owner = User::factory()->create();
        $glucose = $this->dataFor($owner, 'Glicemia');
        $weight = $this->dataFor($owner, 'Peso');
        $empty = $this->dataFor($owner, 'Sem medições');
        $foreign = $this->dataFor(User::factory()->create(), 'Exclusivo de outra conta');

        $this->measurement($glucose, '2026-02-02 09:00:00');
        $this->measurement($glucose, '2026-01-31 08:00:00');
        $this->measurement($glucose, '2026-01-31 20:00:00');
        $this->measurement($weight, '2026-01-31 10:00:00');
        $this->measurement($weight, '2026-02-01 10:00:00', 'não informado');
        $this->measurement($foreign, '2026-01-31 08:00:00');
        $this->measurement($foreign, '2026-02-03 08:00:00');

        // The separate evolution panel filters must not remove any daily series.
        $response = $this->actingAs($owner)->get(route('home', [
            'dado_biomedico_id' => $glucose->id,
            'data_inicio' => '2026-02-02',
            'data_fim' => '2026-02-02',
        ]));
        $response->assertOk()->assertDontSee('Exclusivo de outra conta');
        $chart = $response->viewData('chartData')['medicoesPorDia'];
        $this->assertSame(['31/01/2026', '01/02/2026', '02/02/2026'], $chart['labels']->all());
        $series = $chart['datasets']->keyBy('id');
        $this->assertSame([$glucose->id, $weight->id, $empty->id], $series->keys()->all());
        $this->assertSame('Glicemia', $series[$glucose->id]['label']);
        $this->assertSame([2, 0, 1], $series[$glucose->id]['data']->all());
        $this->assertSame([1, 1, 0], $series[$weight->id]['data']->all());
        $this->assertSame([0, 0, 0], $series[$empty->id]['data']->all());
        $this->assertSame(5, $series->sum(fn ($dataset) => $dataset['data']->sum()));
        $this->assertSame(5, $response->viewData('totalMedicoes'));
        $this->assertCount(1, $response->viewData('chartData')['evolucao']['pontos']);
    }

    public function test_records_with_the_same_name_keep_separate_series(): void
    {
        $owner = User::factory()->create();
        $first = $this->dataFor($owner, 'Medição');
        $second = $this->dataFor($owner, 'Medição');
        $this->measurement($first, '2026-01-01 08:00:00');
        $this->measurement($second, '2026-01-05 08:00:00');

        $response = $this->actingAs($owner)->get(route('home'));
        $response->assertOk();
        $chart = $response->viewData('chartData')['medicoesPorDia'];
        $series = $chart['datasets']->keyBy('id');
        $this->assertCount(2, $series);
        $this->assertSame(['01/01/2026', '05/01/2026'], $chart['labels']->all());
        $this->assertSame([1, 0], $series[$first->id]['data']->all());
        $this->assertSame([0, 1], $series[$second->id]['data']->all());
    }

    public function test_accounts_without_measurements_have_empty_dates_and_an_explanatory_message(): void
    {
        $owner = User::factory()->create();
        $response = $this->actingAs($owner)->get(route('home'));
        $response->assertOk()->assertSee('Registre medições para visualizar as quantidades por dia.');
        $this->assertEmpty($response->viewData('chartData')['medicoesPorDia']['labels']);
        $this->assertEmpty($response->viewData('chartData')['medicoesPorDia']['datasets']);

        $data = $this->dataFor($owner, 'Peso');
        $response = $this->get(route('home'));
        $response->assertOk()->assertDontSee('id="chartMedicoesPorDia"', false);
        $series = $response->viewData('chartData')['medicoesPorDia']['datasets'];
        $this->assertCount(1, $series);
        $this->assertSame($data->id, $series[0]['id']);
        $this->assertEmpty($series[0]['data']);
    }

    private function dataFor(User $owner, string $name): DadoBiomedico
    {
        return $owner->dadosBiomedicos()->create(['nome' => $name, 'valor_referencia' => '', 'unidade_medida' => '']);
    }

    private function measurement(DadoBiomedico $data, string $date, string $value = '95'): void
    {
        $data->medicoes()->create(['nome' => 'Aferição', 'valor' => $value, 'data_hora' => $date]);
    }
}
