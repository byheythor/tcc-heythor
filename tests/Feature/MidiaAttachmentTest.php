<?php

namespace Tests\Feature;

use App\Models\DadoBiomedico;
use App\Models\Midia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\TestCase;

class MidiaAttachmentTest extends TestCase
{
    use RefreshDatabase;

    private const PDF_CONTENT = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog >>\nendobj\n%%EOF\n";

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('anexos');
        Storage::fake('public');
    }

    public function test_owner_can_view_private_attachment_without_caching_it(): void
    {
        $owner = User::factory()->create();
        $midia = $this->createMediaFor($owner);

        $response = $this->actingAs($owner)->get(route('midia.anexo', $midia));

        $response->assertOk()->assertHeader('Content-Type', 'application/pdf')
            ->assertHeader('X-Content-Type-Options', 'nosniff');
        $this->assertStringContainsString('inline', $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('private', $response->headers->get('Cache-Control'));
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
        $this->assertSame(self::PDF_CONTENT, $response->streamedContent());
        Storage::disk('public')->assertMissing($midia->anexo);
    }

    public function test_guest_is_redirected_to_login_when_requesting_attachment(): void
    {
        $midia = $this->createMediaFor(User::factory()->create());

        $this->get(route('midia.anexo', $midia))->assertRedirect(route('login'));
    }

    public function test_another_account_cannot_view_attachment(): void
    {
        $midia = $this->createMediaFor(User::factory()->create());

        $this->actingAs(User::factory()->create())
            ->get(route('midia.anexo', $midia))->assertNotFound();
    }

    public function test_missing_file_returns_not_found_without_public_fallback(): void
    {
        $owner = User::factory()->create();
        $midia = $this->createMediaFor($owner);
        Storage::disk('anexos')->delete($midia->anexo);
        Storage::disk('public')->put($midia->anexo, self::PDF_CONTENT);

        $this->actingAs($owner)->get(route('midia.anexo', $midia))->assertNotFound();
    }

    public function test_nonexistent_media_returns_not_found(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('midia.anexo', 999999))->assertNotFound();
    }

    public function test_private_storage_has_no_alternative_direct_route(): void
    {
        $owner = User::factory()->create();
        $midia = $this->createMediaFor($owner);
        $directUrl = '/storage/anexos/'.$midia->anexo;

        $this->get($directUrl)->assertNotFound();
        $this->actingAs($owner)->get($directUrl)->assertNotFound();
    }

    public function test_listing_and_edit_form_link_to_authorized_route(): void
    {
        $owner = User::factory()->create();
        $midia = $this->createMediaFor($owner);
        $this->actingAs($owner);

        foreach ([route('midia.index'), route('midia.edit', $midia)] as $url) {
            $this->get($url)->assertOk()
                ->assertSee(route('midia.anexo', $midia), false)
                ->assertDontSee(asset('storage/'.$midia->anexo), false);
        }
    }

    public function test_upload_is_stored_only_on_private_disk(): void
    {
        $owner = User::factory()->create();
        $dado = $this->createDataFor($owner);
        $categoria = $owner->categoriasMidia()->create(['nome' => 'Exames']);

        $this->actingAs($owner)->post(route('midia.store'), [
            'tipo' => 'Laudo',
            'dado_biomedico_id' => $dado->id,
            'categoria_midia_id' => $categoria->id,
            'anexo' => UploadedFile::fake()->create('laudo.pdf', 10, 'application/pdf'),
        ])->assertRedirect(route('midia.index'));

        $midia = Midia::sole();
        Storage::disk('anexos')->assertExists($midia->anexo);
        Storage::disk('public')->assertDirectoryEmpty('midias');
    }

    public function test_replacing_attachment_removes_previous_private_file(): void
    {
        $owner = User::factory()->create();
        $midia = $this->createMediaFor($owner);
        $oldPath = $midia->anexo;

        $this->actingAs($owner)->post(route('midia.update', $midia), [
            'tipo' => 'Atualizado',
            'dado_biomedico_id' => $midia->dado_biomedico_id,
            'categoria_midia_id' => $midia->categoria_midia_id,
            'anexo' => UploadedFile::fake()->create('novo.pdf', 10, 'application/pdf'),
        ])->assertRedirect(route('midia.index'));

        $midia->refresh();
        $this->assertNotSame($oldPath, $midia->anexo);
        Storage::disk('anexos')->assertMissing($oldPath);
        Storage::disk('anexos')->assertExists($midia->anexo);
        Storage::disk('public')->assertDirectoryEmpty('midias');
    }

    public function test_editing_without_new_file_preserves_attachment(): void
    {
        $owner = User::factory()->create();
        $midia = $this->createMediaFor($owner);
        $oldPath = $midia->anexo;

        $this->actingAs($owner)->post(route('midia.update', $midia), [
            'tipo' => 'Descrição atualizada',
            'dado_biomedico_id' => $midia->dado_biomedico_id,
            'categoria_midia_id' => $midia->categoria_midia_id,
        ])->assertRedirect(route('midia.index'));

        $this->assertSame($oldPath, $midia->fresh()->anexo);
        $this->assertSame(self::PDF_CONTENT, Storage::disk('anexos')->get($oldPath));
    }

    public function test_failed_record_update_preserves_original_file_and_removes_replacement(): void
    {
        $owner = User::factory()->create();
        $midia = $this->createMediaFor($owner);
        Midia::updating(function () {
            throw new RuntimeException('Falha simulada ao atualizar o registro.');
        });

        try {
            $this->actingAs($owner)->post(route('midia.update', $midia), [
                'dado_biomedico_id' => $midia->dado_biomedico_id,
                'categoria_midia_id' => $midia->categoria_midia_id,
                'anexo' => UploadedFile::fake()->create('novo.pdf', 10, 'application/pdf'),
            ])->assertStatus(500);
        } finally {
            Midia::flushEventListeners();
        }

        $this->assertSame($midia->anexo, $midia->fresh()->anexo);
        $this->assertSame(self::PDF_CONTENT, Storage::disk('anexos')->get($midia->anexo));
        $this->assertCount(1, Storage::disk('anexos')->allFiles());
    }

    public function test_deleting_media_removes_private_file_and_record(): void
    {
        $owner = User::factory()->create();
        $midia = $this->createMediaFor($owner);

        $this->actingAs($owner)->get(route('midia.delete', $midia))
            ->assertRedirect(route('midia.index'));

        $this->assertDatabaseMissing('midia', ['id' => $midia->id]);
        Storage::disk('anexos')->assertMissing($midia->anexo);
    }

    public function test_another_account_cannot_replace_or_delete_attachment(): void
    {
        $midia = $this->createMediaFor(User::factory()->create());
        $other = User::factory()->create();
        $otherData = $this->createDataFor($other);
        $this->actingAs($other);

        $this->post(route('midia.update', $midia), [
            'dado_biomedico_id' => $otherData->id,
            'categoria_midia_id' => $midia->categoria_midia_id,
            'anexo' => UploadedFile::fake()->create('outro.pdf', 10, 'application/pdf'),
        ])->assertNotFound();
        $this->get(route('midia.delete', $midia))->assertNotFound();

        $this->assertDatabaseHas('midia', ['id' => $midia->id, 'anexo' => $midia->anexo]);
        $this->assertSame(self::PDF_CONTENT, Storage::disk('anexos')->get($midia->anexo));
        $this->assertCount(1, Storage::disk('anexos')->allFiles());
    }

    public function test_upload_cannot_be_associated_with_another_accounts_data(): void
    {
        $dado = $this->createDataFor(User::factory()->create());
        $other = User::factory()->create();
        $categoria = $other->categoriasMidia()->create(['nome' => 'Exames']);

        $this->actingAs($other)->post(route('midia.store'), [
            'dado_biomedico_id' => $dado->id,
            'categoria_midia_id' => $categoria->id,
            'anexo' => UploadedFile::fake()->create('laudo.pdf', 10, 'application/pdf'),
        ])->assertNotFound();

        $this->assertDatabaseCount('midia', 0);
        Storage::disk('anexos')->assertDirectoryEmpty('midias');
    }

    private function createDataFor(User $owner): DadoBiomedico
    {
        return DadoBiomedico::create([
            'nome' => 'Glicemia',
            'valor_referencia' => '99',
            'unidade_medida' => 'mg/dL',
            'paciente_id' => $owner->id,
        ]);
    }

    private function createMediaFor(User $owner): Midia
    {
        $dado = $this->createDataFor($owner);
        $categoria = $owner->categoriasMidia()->create(['nome' => 'Exames']);
        $path = 'midias/exame.pdf';
        Storage::disk('anexos')->put($path, self::PDF_CONTENT);

        return Midia::create([
            'anexo' => $path,
            'tipo' => 'Laudo',
            'dado_biomedico_id' => $dado->id,
            'categoria_midia_id' => $categoria->id,
        ]);
    }
}
