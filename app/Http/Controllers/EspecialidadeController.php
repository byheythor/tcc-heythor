<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EspecialidadeController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->especialidades();

        if ($request->filled('q')) {
            $query->where('nome', 'like', '%'.trim((string) $request->input('q')).'%');
        }

        $especialidades = $query->orderBy('nome')->paginate(10)->withQueryString();

        return view('especialidade.index', compact('especialidades'));
    }

    public function create()
    {
        return view('especialidade.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:45'],
        ]);

        $request->user()->especialidades()->create($request->only('nome'));

        return redirect()->route('especialidade.index')->with('success', 'Especialidade criada com sucesso.');
    }

    public function edit($id)
    {
        $especialidade = auth()->user()->especialidades()->findOrFail($id);

        return view('especialidade.edit', compact('especialidade'));
    }

    public function update(Request $request, $id)
    {
        $especialidade = $request->user()->especialidades()->findOrFail($id);

        $request->validate([
            'nome' => ['required', 'string', 'max:45'],
        ]);

        $especialidade->update($request->only('nome'));

        return redirect()->route('especialidade.index')->with('success', 'Especialidade atualizada com sucesso.');
    }

    public function destroy($id)
    {
        $especialidade = auth()->user()->especialidades()->findOrFail($id);
        $especialidade->delete();

        return redirect()->route('especialidade.index')->with('success', 'Especialidade removida com sucesso.');
    }
}
