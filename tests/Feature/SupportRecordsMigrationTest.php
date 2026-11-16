<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use RuntimeException;
use Tests\TestCase;

#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
class SupportRecordsMigrationTest extends TestCase
{
    use DatabaseMigrations;

    public function test_shared_catalogs_are_split_without_losing_records_or_relationships(): void
    {
        $migration = require database_path('migrations/2026_11_16_000001_scope_support_records_to_users.php');
        $migration->down();
        $owners = User::factory()->count(2)->create();
        $unusedAccount = User::factory()->create();
        $specialtyId = DB::table('especialidade')->insertGetId(['nome' => 'Clínica']);
        $doctorId = DB::table('medico')->insertGetId(['nome' => 'Médico Compartilhado', 'crm' => '123', 'contato' => 'Contato preservado', 'especialidade_id' => $specialtyId]);
        $categoryId = DB::table('categoria')->insertGetId(['nome' => 'Metabólico']);
        $mediaCategoryId = DB::table('categoria_midia')->insertGetId(['nome' => 'Laudos']);

        foreach ($owners as $owner) {
            $dataId = DB::table('dado_biomedico')->insertGetId([
                'nome' => 'Glicemia', 'valor_referencia' => '99', 'unidade_medida' => 'mg/dL',
                'paciente_id' => $owner->id, 'medico_id' => $doctorId, 'categoria_id' => $categoryId,
            ]);
            DB::table('medicao')->insert(['nome' => 'Medição', 'valor' => '90', 'data_hora' => '2026-01-01 08:00:00', 'dado_biomedico_id' => $dataId]);
            DB::table('midia')->insert(['anexo' => 'midias/exame-'.$owner->id.'.pdf', 'dado_biomedico_id' => $dataId, 'categoria_midia_id' => $mediaCategoryId]);
        }
        $beforeFiles = DB::table('midia')->orderBy('id')->pluck('anexo', 'id')->all();
        $beforeMeasurements = DB::table('medicao')->orderBy('id')->get()->toJson();

        $migration->up();

        foreach (['categoria', 'especialidade', 'medico', 'categoria_midia'] as $table) {
            $this->assertDatabaseCount($table, 2);
            $this->assertSame(0, DB::table($table)->whereNull('user_id')->count());
        }
        foreach ($owners as $owner) {
            $data = $owner->dadosBiomedicos()->with(['categoria', 'medico.especialidade', 'midias.categoria_midia'])->sole();
            $this->assertSame($owner->id, $data->categoria->user_id);
            $this->assertSame($owner->id, $data->medico->user_id);
            $this->assertSame($owner->id, $data->medico->especialidade->user_id);
            $this->assertSame($owner->id, $data->midias->sole()->categoria_midia->user_id);
            $this->assertSame('Médico Compartilhado', $data->medico->nome);
            $this->assertSame('Contato preservado', $data->medico->contato);
            $this->assertSame('Metabólico', $data->categoria->nome);
        }
        $this->assertSame($beforeFiles, DB::table('midia')->orderBy('id')->pluck('anexo', 'id')->all());
        $this->assertSame($beforeMeasurements, DB::table('medicao')->orderBy('id')->get()->toJson());
        $this->assertSame(0, $unusedAccount->categorias()->count());

        // Um cadastro já individualizado pode existir antes de ser usado por um dado biomédico.
        $owners->first()->categorias()->create(['nome' => 'Ainda não utilizada']);
        $migration->up();
        $this->assertDatabaseCount('categoria', 3);
        $this->assertDatabaseCount('medico', 2);
    }

    public function test_unassigned_legacy_records_stop_migration_before_schema_changes(): void
    {
        $migration = require database_path('migrations/2026_11_16_000001_scope_support_records_to_users.php');
        $migration->down();
        User::factory()->create();
        $id = DB::table('categoria')->insertGetId(['nome' => 'Sem vínculo']);

        try {
            $migration->up();
            $this->fail('A migração não deve escolher um proprietário arbitrário.');
        } catch (RuntimeException $exception) {
            $this->assertStringContainsString('sem proprietário identificável', $exception->getMessage());
        }

        foreach (['categoria', 'especialidade', 'medico', 'categoria_midia'] as $table) {
            $this->assertFalse(Schema::hasColumn($table, 'user_id'));
        }
        $this->assertDatabaseHas('categoria', ['id' => $id, 'nome' => 'Sem vínculo']);

        // Restaura o esquema para o rollback automático do teste.
        DB::table('categoria')->where('id', $id)->delete();
        $migration->up();
    }
}
