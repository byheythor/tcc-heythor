<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->categorias();

        if ($request->filled('q')) {
            $query->where('nome', 'like', '%'.trim((string) $request->input('q')).'%');
        }

        $categorias = $query->orderBy('nome')->paginate(10)->withQueryString();

        return view('categoria.index', compact('categorias'));
    }

    public function create()
    {
        return view('categoria.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:45'],
        ]);

        $request->user()->categorias()->create($request->only('nome'));

        return redirect()->route('categoria.index')->with('success', 'Categoria criada com sucesso.');
    }

    public function edit($id)
    {
        $categoria = auth()->user()->categorias()->findOrFail($id);

        return view('categoria.edit', compact('categoria'));
    }

    public function update(Request $request, $id)
    {
        $categoria = $request->user()->categorias()->findOrFail($id);

        $request->validate([
            'nome' => ['required', 'string', 'max:45'],
        ]);

        $categoria->update($request->only('nome'));

        return redirect()->route('categoria.index')->with('success', 'Categoria atualizada com sucesso.');
    }

    public function destroy($id)
    {
        $categoria = auth()->user()->categorias()->findOrFail($id);
        $categoria->delete();

        return redirect()->route('categoria.index')->with('success', 'Categoria removida com sucesso.');
    }
}
