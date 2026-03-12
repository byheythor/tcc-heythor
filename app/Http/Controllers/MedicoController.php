<?php

namespace App\Http\Controllers;

use App\Models\Especialidade;
use App\Models\Medico;
use Illuminate\Http\Request;

class MedicoController extends Controller
{
    public function index(Request $request)
    {
        $query = Medico::query()->with('especialidade');

        if ($request->filled('q')) {
            $term = trim((string) $request->input('q'));
            $query->where(function ($q) use ($term) {
                $q->where('nome', 'like', '%' . $term . '%')
                    ->orWhere('crm', 'like', '%' . $term . '%')
                    ->orWhere('contato', 'like', '%' . $term . '%');
            });
        }

        if ($request->filled('especialidade_id')) {
            $query->where('especialidade_id', $request->integer('especialidade_id'));
        }

        $medicos = $query->orderBy('nome')->paginate(10)->withQueryString();
        $especialidades = Especialidade::orderBy('nome')->get();

        return view('medico.index', compact('medicos', 'especialidades'));
    }

    public function create()
    {
        $especialidades = Especialidade::all();
        return view('medico.create', compact('especialidades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'crm' => ['nullable', 'string', 'max:20'],
            'contato' => ['nullable', 'string', 'max:45'],
            'especialidade_id' => ['required', 'integer', 'exists:especialidade,id'],
        ]);

        Medico::create($request->only('nome', 'crm', 'contato', 'especialidade_id'));
        return redirect()->route('medico.index')->with('success', 'Médico criado com sucesso.');
    }

    public function edit($id)
    {
        $medico = Medico::findOrFail($id);
        $especialidades = Especialidade::all();
        return view('medico.edit', compact('medico', 'especialidades'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'crm' => ['nullable', 'string', 'max:20'],
            'contato' => ['nullable', 'string', 'max:45'],
            'especialidade_id' => ['required', 'integer', 'exists:especialidade,id'],
        ]);

        $medico = Medico::findOrFail($id);
        $medico->update($request->only('nome', 'crm', 'contato', 'especialidade_id'));
        return redirect()->route('medico.index')->with('success', 'Médico atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $medico = Medico::findOrFail($id);
        $medico->delete();
        return redirect()->route('medico.index')->with('success', 'Médico removido com sucesso.');
    }
}
