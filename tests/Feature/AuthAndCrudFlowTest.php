<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\CategoriaMidia;
use App\Models\DadoBiomedico;
use App\Models\Especialidade;
use App\Models\Medicao;
use App\Models\Medico;
use App\Models\Midia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AuthAndCrudFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_screen_loads(): void
    {
        $this->get('/register')->assertOk();
    }

    public function test_user_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Usuario Teste',
            'email' => 'teste@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/home');

        $this->assertDatabaseHas('users', [
            'email' => 'teste@example.com',
            'nome' => 'Usuario Teste',
        ]);
    }

    public function test_authenticated_user_can_complete_core_crud_flow(): void
    {
        Storage::fake('anexos');
        Storage::fake('public');

        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post(route('especialidade.store'), ['nome' => 'Cardiologia'])
            ->assertRedirect(route('especialidade.index'));
        $especialidade = Especialidade::where('nome', 'Cardiologia')->firstOrFail();

        $this->post(route('medico.store'), [
            'nome' => 'Dr. Silva',
            'crm' => '12345',
            'contato' => '31999999999',
            'especialidade_id' => $especialidade->id,
        ])->assertRedirect(route('medico.index'));
        $medico = Medico::where('nome', 'Dr. Silva')->firstOrFail();

        $this->post(route('categoria.store'), ['nome' => 'Exames'])
            ->assertRedirect(route('categoria.index'));
        $categoria = Categoria::where('nome', 'Exames')->firstOrFail();

        $this->post(route('dado_biomedico.store'), [
            'nome' => 'Pressao arterial',
            'valor_referencia' => '12/8',
            'unidade_medida' => 'mmHg',
            'medico_id' => $medico->id,
            'categoria_id' => $categoria->id,
        ])->assertRedirect(route('dado_biomedico.index'));
        $dado = DadoBiomedico::where('nome', 'Pressao arterial')
            ->where('paciente_id', $user->id)
            ->firstOrFail();

        $this->post(route('medicao.store'), [
            'nome' => 'Manha',
            'valor' => '12/8',
            'dado_biomedico_id' => $dado->id,
            'data_hora' => now()->format('Y-m-d H:i:s'),
        ])->assertRedirect(route('medicao.index'));

        $this->assertDatabaseHas('medicao', [
            'nome' => 'Manha',
            'dado_biomedico_id' => $dado->id,
        ]);

        $this->post(route('categoria_midia.store'), ['nome' => 'Laudo'])
            ->assertRedirect(route('categoria_midia.index'));
        $categoriaMidia = CategoriaMidia::where('nome', 'Laudo')->firstOrFail();

        $this->post(route('midia.store'), [
            'tipo' => 'PDF',
            'dado_biomedico_id' => $dado->id,
            'categoria_midia_id' => $categoriaMidia->id,
            'anexo' => UploadedFile::fake()->create('laudo.pdf', 100, 'application/pdf'),
        ])->assertRedirect(route('midia.index'));

        $midia = Midia::latest('id')->firstOrFail();
        Storage::disk('anexos')->assertExists($midia->anexo);
        Storage::disk('public')->assertMissing($midia->anexo);
    }

    public function test_medico_requires_especialidade(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('medico.store'), [
            'nome' => 'Dr Sem Especialidade',
            'crm' => '0000',
            'contato' => '31911111111',
            'especialidade_id' => null,
        ]);

        $response->assertSessionHasErrors(['especialidade_id']);
    }

    public function test_user_only_sees_own_dado_biomedico(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        DadoBiomedico::create([
            'nome' => 'Glicose',
            'valor_referencia' => '99',
            'unidade_medida' => 'mg/dL',
            'paciente_id' => $owner->id,
        ]);

        DadoBiomedico::create([
            'nome' => 'Colesterol',
            'valor_referencia' => '190',
            'unidade_medida' => 'mg/dL',
            'paciente_id' => $other->id,
        ]);

        $this->actingAs($owner)
            ->get(route('dado_biomedico.index'))
            ->assertSee('Glicose')
            ->assertDontSee('Colesterol');
    }
}
