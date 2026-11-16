<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MedicoController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->medicos()->with('especialidade');

        if ($request->filled('q')) {
            $term = trim((string) $request->input('q'));
            $query->where(function ($q) use ($term) {
                $q->where('nome', 'like', '%'.$term.'%')
                    ->orWhere('crm', 'like', '%'.$term.'%')
                    ->orWhere('contato', 'like', '%'.$term.'%');
            });
        }

        if ($request->filled('especialidade_id')) {
            $query->where('especialidade_id', $request->integer('especialidade_id'));
        }

        $medicos = $query->orderBy('nome')->paginate(10)->withQueryString();
        $especialidades = $request->user()->especialidades()->orderBy('nome')->get();

        return view('medico.index', compact('medicos', 'especialidades'));
    }

    public function create()
    {
        $especialidades = auth()->user()->especialidades()->get();

        return view('medico.create', compact('especialidades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'crm' => ['nullable', 'string', 'max:20'],
            'contato' => ['nullable', 'string', 'max:45'],
            'especialidade_id' => ['required', 'integer', Rule::exists('especialidade', 'id')->where('user_id', $request->user()->id)],
        ]);

        $request->user()->medicos()->create($request->only('nome', 'crm', 'contato', 'especialidade_id'));

        return redirect()->route('medico.index')->with('success', 'Médico criado com sucesso.');
    }

    public function edit($id)
    {
        $medico = auth()->user()->medicos()->findOrFail($id);
        $especialidades = auth()->user()->especialidades()->get();

        return view('medico.edit', compact('medico', 'especialidades'));
    }

    public function update(Request $request, $id)
    {
        $medico = $request->user()->medicos()->findOrFail($id);

        $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'crm' => ['nullable', 'string', 'max:20'],
            'contato' => ['nullable', 'string', 'max:45'],
            'especialidade_id' => ['required', 'integer', Rule::exists('especialidade', 'id')->where('user_id', $request->user()->id)],
        ]);

        $medico->update($request->only('nome', 'crm', 'contato', 'especialidade_id'));

        return redirect()->route('medico.index')->with('success', 'Médico atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $medico = auth()->user()->medicos()->findOrFail($id);
        $medico->delete();

        return redirect()->route('medico.index')->with('success', 'Médico removido com sucesso.');
    }
}
