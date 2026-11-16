<?php

namespace Tests\Feature;

use App\Models\DadoBiomedico;
use App\Models\Midia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProtegerAnexosTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        Storage::fake('anexos');
    }

    public function test_migrates_registered_and_orphaned_files_and_can_be_repeated(): void
    {
        $midia = $this->createMedia('midias/exame.pdf');
        Storage::disk('public')->put($midia->anexo, 'conteudo original');
        Storage::disk('public')->put('midias/antigo.pdf', 'arquivo sem registro');
        Storage::disk('public')->put('outros/logo.png', 'recurso publico');

        $this->artisan('midias:proteger-anexos')->assertSuccessful();

        $this->assertSame('conteudo original', Storage::disk('anexos')->get($midia->anexo));
        $this->assertSame('arquivo sem registro', Storage::disk('anexos')->get('midias/antigo.pdf'));
        $this->assertSame('midias/exame.pdf', $midia->fresh()->anexo);
        Storage::disk('public')->assertDirectoryEmpty('midias');
        Storage::disk('public')->assertExists('outros/logo.png');

        $this->artisan('midias:proteger-anexos')->assertSuccessful();
        $this->assertSame('conteudo original', Storage::disk('anexos')->get($midia->anexo));
    }

    public function test_dry_run_does_not_change_files(): void
    {
        $midia = $this->createMedia('midias/exame.pdf');
        Storage::disk('public')->put($midia->anexo, 'conteudo original');

        $this->artisan('midias:proteger-anexos', ['--dry-run' => true])->assertSuccessful();

        Storage::disk('public')->assertExists($midia->anexo);
        Storage::disk('anexos')->assertMissing($midia->anexo);
    }

    public function test_conflicting_files_are_preserved_and_nothing_is_moved(): void
    {
        Storage::disk('public')->put('midias/primeiro.pdf', 'sem conflito');
        Storage::disk('public')->put('midias/conflito.pdf', 'versao publica');
        Storage::disk('anexos')->put('midias/conflito.pdf', 'versao privada');

        $this->artisan('midias:proteger-anexos')->assertFailed();

        $this->assertSame('versao publica', Storage::disk('public')->get('midias/conflito.pdf'));
        $this->assertSame('versao privada', Storage::disk('anexos')->get('midias/conflito.pdf'));
        Storage::disk('public')->assertExists('midias/primeiro.pdf');
        Storage::disk('anexos')->assertMissing('midias/primeiro.pdf');
    }

    public function test_identical_private_copy_allows_removal_of_public_copy(): void
    {
        $midia = $this->createMedia('midias/exame.pdf');
        Storage::disk('public')->put($midia->anexo, 'mesmo conteudo');
        Storage::disk('anexos')->put($midia->anexo, 'mesmo conteudo');

        $this->artisan('midias:proteger-anexos')->assertSuccessful();

        Storage::disk('public')->assertMissing($midia->anexo);
        $this->assertSame('mesmo conteudo', Storage::disk('anexos')->get($midia->anexo));
    }

    public function test_missing_registered_file_is_reported_before_any_transfer(): void
    {
        $this->createMedia('midias/ausente.pdf');
        Storage::disk('public')->put('midias/presente.pdf', 'preservar');

        $this->artisan('midias:proteger-anexos')->assertFailed();

        Storage::disk('public')->assertExists('midias/presente.pdf');
        Storage::disk('anexos')->assertMissing('midias/presente.pdf');
    }

    public function test_paths_outside_media_folder_are_rejected(): void
    {
        $this->createMedia('../fora.pdf');
        Storage::disk('public')->put('midias/exame.pdf', 'preservar');

        $this->artisan('midias:proteger-anexos')->assertFailed();

        Storage::disk('public')->assertExists('midias/exame.pdf');
        Storage::disk('anexos')->assertMissing('midias/exame.pdf');
    }

    public function test_overlapping_storage_roots_are_rejected_without_deleting_files(): void
    {
        Storage::disk('public')->put('midias/exame.pdf', 'preservar');
        Storage::set('anexos', Storage::disk('public'));

        $this->artisan('midias:proteger-anexos')->assertFailed();

        $this->assertSame('preservar', Storage::disk('public')->get('midias/exame.pdf'));
    }

    private function createMedia(string $path): Midia
    {
        $owner = User::factory()->create();
        $dado = DadoBiomedico::create([
            'nome' => 'Glicemia',
            'valor_referencia' => '99',
            'unidade_medida' => 'mg/dL',
            'paciente_id' => $owner->id,
        ]);
        $categoria = $owner->categoriasMidia()->create(['nome' => 'Exames']);

        return Midia::create([
            'anexo' => $path,
            'dado_biomedico_id' => $dado->id,
            'categoria_midia_id' => $categoria->id,
        ]);
    }
}
