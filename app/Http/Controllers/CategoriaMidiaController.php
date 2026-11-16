<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoriaMidiaController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->categoriasMidia();

        if ($request->filled('q')) {
            $query->where('nome', 'like', '%'.trim((string) $request->input('q')).'%');
        }

        $categorias_midia = $query->orderBy('nome')->paginate(10)->withQueryString();

        return view('categoria_midia.index', compact('categorias_midia'));
    }

    public function create()
    {
        return view('categoria_midia.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:45'],
        ]);

        $request->user()->categoriasMidia()->create($request->only('nome'));

        return redirect()->route('categoria_midia.index')->with('success', 'Categoria de mídia criada com sucesso.');
    }

    public function edit($id)
    {
        $categoria_midia = auth()->user()->categoriasMidia()->findOrFail($id);

        return view('categoria_midia.edit', compact('categoria_midia'));
    }

    public function update(Request $request, $id)
    {
        $categoria_midia = $request->user()->categoriasMidia()->findOrFail($id);

        $request->validate([
            'nome' => ['required', 'string', 'max:45'],
        ]);

        $categoria_midia->update($request->only('nome'));

        return redirect()->route('categoria_midia.index')->with('success', 'Categoria de mídia atualizada com sucesso.');
    }

    public function destroy($id)
    {
        $categoria_midia = auth()->user()->categoriasMidia()->findOrFail($id);
        $categoria_midia->delete();

        return redirect()->route('categoria_midia.index')->with('success', 'Categoria de mídia removida com sucesso.');
    }
}
