<?php

namespace App\Http\Controllers;

use App\Models\Especialidade;
use Illuminate\Http\Request;

class EspecialidadeController extends Controller
{
    public function index()
    {
        $especialidades = Especialidade::all();
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

        Especialidade::create($request->only('nome'));
        return redirect()->route('especialidade.index')->with('success', 'Especialidade criada com sucesso.');
    }

    public function edit($id)
    {
        $especialidade = Especialidade::findOrFail($id);
        return view('especialidade.edit', compact('especialidade'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:45'],
        ]);

        $especialidade = Especialidade::findOrFail($id);
        $especialidade->update($request->only('nome'));
        return redirect()->route('especialidade.index')->with('success', 'Especialidade atualizada com sucesso.');
    }

    public function destroy($id)
    {
        $especialidade = Especialidade::findOrFail($id);
        $especialidade->delete();
        return redirect()->route('especialidade.index')->with('success', 'Especialidade removida com sucesso.');
    }
}
