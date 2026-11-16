<?php

namespace App\Http\Controllers;

use App\Models\DadoBiomedico;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DadoBiomedicoController extends Controller
{
    public function index(Request $request)
    {
        $query = DadoBiomedico::where('paciente_id', auth()->id())->with(['medico', 'categoria']);

        if ($request->filled('q')) {
            $query->where('nome', 'like', '%'.trim((string) $request->input('q')).'%');
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->integer('categoria_id'));
        }

        if ($request->filled('medico_id')) {
            $query->where('medico_id', $request->integer('medico_id'));
        }

        $dados_biomedicos = $query->orderBy('nome')->paginate(10)->withQueryString();
        $categorias = $request->user()->categorias()->get();
        $medicos = $request->user()->medicos()->get();

        return view('dado_biomedico.index', compact('dados_biomedicos', 'categorias', 'medicos'));
    }

    public function create()
    {
        $categorias = auth()->user()->categorias()->get();
        $medicos = auth()->user()->medicos()->get();

        return view('dado_biomedico.create', compact('categorias', 'medicos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'valor_referencia' => ['required', 'string', 'max:30'],
            'unidade_medida' => ['required', 'string', 'max:30'],
            'medico_id' => ['nullable', 'integer', Rule::exists('medico', 'id')->where('user_id', $request->user()->id)],
            'categoria_id' => ['nullable', 'integer', Rule::exists('categoria', 'id')->where('user_id', $request->user()->id)],
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

        $categorias = auth()->user()->categorias()->get();
        $medicos = auth()->user()->medicos()->get();

        return view('dado_biomedico.edit', compact('dado_biomedico', 'categorias', 'medicos'));
    }

    public function update(Request $request, $id)
    {
        $dado_biomedico = $request->user()->dadosBiomedicos()->findOrFail($id);

        $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'valor_referencia' => ['required', 'string', 'max:30'],
            'unidade_medida' => ['required', 'string', 'max:30'],
            'medico_id' => ['nullable', 'integer', Rule::exists('medico', 'id')->where('user_id', $request->user()->id)],
            'categoria_id' => ['nullable', 'integer', Rule::exists('categoria', 'id')->where('user_id', $request->user()->id)],
        ]);

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
