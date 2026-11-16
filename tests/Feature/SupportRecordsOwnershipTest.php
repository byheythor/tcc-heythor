<?php

namespace Tests\Feature;

use App\Models\DadoBiomedico;
use App\Models\Midia;
use App\Models\User;
use Database\Seeders\DashboardTestSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SupportRecordsOwnershipTest extends TestCase
{
    use RefreshDatabase;

    public static function catalogs(): array
    {
        return [
            'categorias' => ['categoria', 'categorias', 'categoria'],
            'especialidades' => ['especialidade', 'especialidades', 'especialidade'],
            'medicos' => ['medico', 'medicos', 'medico'],
            'categorias de midia' => ['categoria_midia', 'categoriasMidia', 'categoria_midia'],
        ];
    }

    #[DataProvider('catalogs')]
    public function test_listing_and_search_only_show_own_records(string $prefix, string $relation, string $table): void
    {
        $owner = User::factory()->create();
        $this->createCatalog($owner, $relation, 'Cadastro Proprietario');
        $this->createCatalog(User::factory()->create(), $relation, 'Cadastro OutraConta');

        $this->actingAs($owner)->get(route($prefix.'.index'))
            ->assertOk()->assertSee('Cadastro Proprietario')->assertDontSee('Cadastro OutraConta');
        $this->get(route($prefix.'.index', ['q' => 'Cadastro']))
            ->assertOk()->assertSee('Cadastro Proprietario')->assertDontSee('Cadastro OutraConta');
        $this->get(route($prefix.'.index', ['q' => 'OutraConta']))
            ->assertOk()->assertDontSee('Cadastro OutraConta');
    }

    #[DataProvider('catalogs')]
    public function test_owner_can_manage_records_and_cannot_override_ownership(string $prefix, string $relation, string $table): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $payload = ['nome' => 'Cadastro Novo', 'user_id' => $other->id];
        if ($relation === 'medicos') {
            $payload['especialidade_id'] = $owner->especialidades()->create(['nome' => 'Clínica'])->id;
        }

        $this->actingAs($owner)->post(route($prefix.'.store'), $payload)
            ->assertRedirect(route($prefix.'.index'));
        $record = $owner->{$relation}()->where('nome', 'Cadastro Novo')->firstOrFail();
        $this->assertDatabaseHas($table, ['id' => $record->id, 'user_id' => $owner->id]);
        $this->assertSame(0, $other->{$relation}()->count());
        $this->get(route($prefix.'.edit', $record))->assertOk()->assertSee('Cadastro Novo');

        $payload['nome'] = 'Cadastro Atualizado';
        $this->post(route($prefix.'.update', $record), $payload)->assertRedirect(route($prefix.'.index'));
        $this->assertDatabaseHas($table, ['id' => $record->id, 'nome' => 'Cadastro Atualizado', 'user_id' => $owner->id]);

        $this->get(route($prefix.'.delete', $record))->assertRedirect(route($prefix.'.index'));
        $this->assertDatabaseMissing($table, ['id' => $record->id]);
    }

    #[DataProvider('catalogs')]
    public function test_another_account_cannot_open_edit_update_or_delete_record(string $prefix, string $relation, string $table): void
    {
        $owner = User::factory()->create();
        $record = $this->createCatalog($owner, $relation, 'Registro Protegido');
        $other = User::factory()->create();

        $this->actingAs($other)->get(route($prefix.'.edit', $record))->assertNotFound();
        $this->post(route($prefix.'.update', $record), ['nome' => 'Alterado', 'user_id' => $other->id])->assertNotFound();
        $this->get(route($prefix.'.delete', $record))->assertNotFound();

        $this->assertDatabaseHas($table, ['id' => $record->id, 'nome' => 'Registro Protegido', 'user_id' => $owner->id]);
    }

    #[DataProvider('catalogs')]
    public function test_guests_cannot_access_catalog_management(string $prefix, string $relation, string $table): void
    {
        $record = $this->createCatalog(User::factory()->create(), $relation, 'Registro Protegido');

        $this->get(route($prefix.'.index'))->assertRedirect(route('login'));
        $this->get(route($prefix.'.edit', $record))->assertRedirect(route('login'));
        $this->post(route($prefix.'.store'), ['nome' => 'Novo'])->assertRedirect(route('login'));
        $this->post(route($prefix.'.update', $record), ['nome' => 'Alterado'])->assertRedirect(route('login'));
        $this->get(route($prefix.'.delete', $record))->assertRedirect(route('login'));
        $this->assertDatabaseHas($table, ['id' => $record->id, 'nome' => 'Registro Protegido']);
    }

    public function test_form_and_filter_options_only_include_own_support_records(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $own = $this->createSupportSet($owner);
        $this->createSupportSet($other);
        $dado = $this->createData($owner, $own);
        $midia = $this->createMedia($dado, $own['categoria_midia']);
        $this->actingAs($owner);

        foreach ([route('medico.index'), route('medico.create'), route('medico.edit', $own['medico'])] as $url) {
            $this->get($url)->assertOk()->assertViewHas('especialidades', fn ($rows) => $rows->modelKeys() === [$own['especialidade']->id]);
        }
        foreach ([route('dado_biomedico.index'), route('dado_biomedico.create'), route('dado_biomedico.edit', $dado)] as $url) {
            $this->get($url)->assertOk()
                ->assertViewHas('categorias', fn ($rows) => $rows->modelKeys() === [$own['categoria']->id])
                ->assertViewHas('medicos', fn ($rows) => $rows->modelKeys() === [$own['medico']->id]);
        }
        foreach ([route('midia.create'), route('midia.edit', $midia)] as $url) {
            $this->get($url)->assertOk()->assertViewHas('categoriasMidia', fn ($rows) => $rows->modelKeys() === [$own['categoria_midia']->id]);
        }
    }

    public function test_doctor_cannot_use_another_accounts_specialty_on_create_or_update(): void
    {
        $owner = User::factory()->create();
        $own = $this->createSupportSet($owner);
        $other = $this->createSupportSet(User::factory()->create());
        $payload = ['nome' => 'Médico Inválido', 'especialidade_id' => $other['especialidade']->id];

        $this->actingAs($owner)->post(route('medico.store'), $payload)->assertSessionHasErrors('especialidade_id');
        $this->post(route('medico.update', $own['medico']), $payload)->assertSessionHasErrors('especialidade_id');

        $this->assertDatabaseCount('medico', 2);
        $this->assertSame($own['especialidade']->id, $own['medico']->fresh()->especialidade_id);
    }

    public function test_biomedical_record_cannot_use_another_accounts_doctor_or_category(): void
    {
        $owner = User::factory()->create();
        $own = $this->createSupportSet($owner);
        $other = $this->createSupportSet(User::factory()->create());
        $dado = $this->createData($owner, $own);
        $payload = [
            'nome' => 'Registro Inválido', 'valor_referencia' => '99', 'unidade_medida' => 'mg/dL',
            'medico_id' => $other['medico']->id, 'categoria_id' => $other['categoria']->id,
        ];

        $this->actingAs($owner)->post(route('dado_biomedico.store'), $payload)->assertSessionHasErrors(['medico_id', 'categoria_id']);
        $this->post(route('dado_biomedico.update', $dado), $payload)->assertSessionHasErrors(['medico_id', 'categoria_id']);

        $this->assertDatabaseCount('dado_biomedico', 1);
        $this->assertDatabaseHas('dado_biomedico', ['id' => $dado->id, 'medico_id' => $own['medico']->id, 'categoria_id' => $own['categoria']->id]);
    }

    public function test_media_cannot_use_another_accounts_media_category(): void
    {
        Storage::fake('anexos');
        $owner = User::factory()->create();
        $own = $this->createSupportSet($owner);
        $other = $this->createSupportSet(User::factory()->create());
        $dado = $this->createData($owner, $own);
        $midia = $this->createMedia($dado, $own['categoria_midia']);
        Storage::disk('anexos')->put($midia->anexo, 'original');
        $payload = [
            'dado_biomedico_id' => $dado->id,
            'categoria_midia_id' => $other['categoria_midia']->id,
            'anexo' => UploadedFile::fake()->create('exame.pdf', 10, 'application/pdf'),
        ];

        $this->actingAs($owner)->post(route('midia.store'), $payload)->assertSessionHasErrors('categoria_midia_id');
        $this->post(route('midia.update', $midia), $payload)->assertSessionHasErrors('categoria_midia_id');

        $this->assertDatabaseCount('midia', 1);
        $this->assertSame($own['categoria_midia']->id, $midia->fresh()->categoria_midia_id);
        $this->assertSame('original', Storage::disk('anexos')->get($midia->anexo));
        $this->assertCount(1, Storage::disk('anexos')->allFiles());
    }

    public function test_new_account_starts_without_catalogs_and_can_create_data_without_optional_relations(): void
    {
        $this->createSupportSet(User::factory()->create());
        $owner = User::factory()->create();
        $this->actingAs($owner);

        $this->get(route('medico.create'))->assertOk()->assertViewHas('especialidades', fn ($rows) => $rows->isEmpty());
        $this->get(route('dado_biomedico.create'))->assertOk()
            ->assertViewHas('categorias', fn ($rows) => $rows->isEmpty())
            ->assertViewHas('medicos', fn ($rows) => $rows->isEmpty());
        $this->get(route('midia.create'))->assertOk()->assertViewHas('categoriasMidia', fn ($rows) => $rows->isEmpty());

        $this->post(route('dado_biomedico.store'), ['nome' => 'Glicemia', 'valor_referencia' => '99', 'unidade_medida' => 'mg/dL'])
            ->assertRedirect(route('dado_biomedico.index'));
        $this->assertDatabaseHas('dado_biomedico', ['paciente_id' => $owner->id, 'medico_id' => null, 'categoria_id' => null]);
    }

    public function test_dashboard_seeder_does_not_reuse_another_accounts_catalogs(): void
    {
        $other = User::factory()->create();
        $category = $other->categorias()->create(['nome' => 'Cardiovascular']);
        $specialty = $other->especialidades()->create(['nome' => 'Cardiologia']);
        $doctor = $other->medicos()->create(['nome' => 'Cadastro particular', 'crm' => 'CRM-SP 654321', 'especialidade_id' => $specialty->id]);

        $this->seed(DashboardTestSeeder::class);

        $demo = User::where('email', 'dashboard.teste@example.com')->firstOrFail();
        $this->assertSame(5, $demo->dadosBiomedicos()->count());
        foreach ($demo->dadosBiomedicos()->with(['categoria', 'medico.especialidade'])->get() as $data) {
            $this->assertSame($demo->id, $data->categoria->user_id);
            $this->assertSame($demo->id, $data->medico->user_id);
            $this->assertSame($demo->id, $data->medico->especialidade->user_id);
        }
        $this->assertSame($other->id, $category->fresh()->user_id);
        $this->assertSame('Cadastro particular', $doctor->fresh()->nome);
    }

    private function createCatalog(User $owner, string $relation, string $name): Model
    {
        $attributes = ['nome' => $name];
        if ($relation === 'medicos') {
            $attributes['especialidade_id'] = $owner->especialidades()->create(['nome' => 'Clínica'])->id;
        }

        return $owner->{$relation}()->create($attributes);
    }

    private function createSupportSet(User $owner): array
    {
        $specialty = $owner->especialidades()->create(['nome' => 'Especialidade '.$owner->id]);

        return [
            'especialidade' => $specialty,
            'medico' => $owner->medicos()->create(['nome' => 'Médico '.$owner->id, 'especialidade_id' => $specialty->id]),
            'categoria' => $owner->categorias()->create(['nome' => 'Categoria '.$owner->id]),
            'categoria_midia' => $owner->categoriasMidia()->create(['nome' => 'Anexos '.$owner->id]),
        ];
    }

    private function createData(User $owner, array $support): DadoBiomedico
    {
        return $owner->dadosBiomedicos()->create([
            'nome' => 'Glicemia', 'valor_referencia' => '99', 'unidade_medida' => 'mg/dL',
            'medico_id' => $support['medico']->id, 'categoria_id' => $support['categoria']->id,
        ]);
    }

    private function createMedia(DadoBiomedico $data, Model $category): Midia
    {
        return $data->midias()->create(['anexo' => 'midias/exame.pdf', 'categoria_midia_id' => $category->id]);
    }
}
