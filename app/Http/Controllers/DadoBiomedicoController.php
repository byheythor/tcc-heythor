<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\DadoBiomedico;
use App\Models\Medico;
use Illuminate\Http\Request;

class DadoBiomedicoController extends Controller
{
    public function index(Request $request)
    {
        $query = DadoBiomedico::where('paciente_id', auth()->id());

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->integer('categoria_id'));
        }

        if ($request->filled('medico_id')) {
            $query->where('medico_id', $request->integer('medico_id'));
        }

        $dados_biomedicos = $query->get();
        $categorias = Categoria::all();
        $medicos = Medico::all();

        return view('dado_biomedico.index', compact('dados_biomedicos', 'categorias', 'medicos'));
    }

    public function create()
    {
        $categorias = Categoria::all();
        $medicos = Medico::all();
        return view('dado_biomedico.create', compact('categorias', 'medicos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'valor_referencia' => ['required', 'string', 'max:30'],
            'unidade_medida' => ['required', 'string', 'max:30'],
            'medico_id' => ['nullable', 'integer', 'exists:medico,id'],
            'categoria_id' => ['nullable', 'integer', 'exists:categoria,id'],
        ]);

        $data = $request->only('nome', 'valor_referencia', 'unidade_medida', 'medico_id', 'categoria_id');
        $data['paciente_id'] = auth()->id();
        DadoBiomedico::create($data);

        return redirect()->route('dado_biomedico.index')->with('success', 'Dado biomédico criado com sucesso.');
    }

    public function edit($id)
    {
        $dado_biomedico = DadoBiomedico::where('id', $id)
            ->where('paciente_id', auth()->id())
            ->firstOrFail();

        $categorias = Categoria::all();
        $medicos = Medico::all();

        return view('dado_biomedico.edit', compact('dado_biomedico', 'categorias', 'medicos'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'valor_referencia' => ['required', 'string', 'max:30'],
            'unidade_medida' => ['required', 'string', 'max:30'],
            'medico_id' => ['nullable', 'integer', 'exists:medico,id'],
            'categoria_id' => ['nullable', 'integer', 'exists:categoria,id'],
        ]);

        $dado_biomedico = DadoBiomedico::where('id', $id)
            ->where('paciente_id', auth()->id())
            ->firstOrFail();

        $dado_biomedico->update($request->only('nome', 'valor_referencia', 'unidade_medida', 'medico_id', 'categoria_id'));

        return redirect()->route('dado_biomedico.index')->with('success', 'Dado biomédico atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $dado_biomedico = DadoBiomedico::where('id', $id)
            ->where('paciente_id', auth()->id())
            ->firstOrFail();

        $dado_biomedico->delete();
        return redirect()->route('dado_biomedico.index')->with('success', 'Dado biomédico removido com sucesso.');
    }
}
