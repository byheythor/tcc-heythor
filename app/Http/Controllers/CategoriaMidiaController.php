<?php

namespace App\Http\Controllers;

use App\Models\CategoriaMidia;
use Illuminate\Http\Request;

class CategoriaMidiaController extends Controller
{
    public function index()
    {
        $categorias_midia = CategoriaMidia::all();
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

        CategoriaMidia::create($request->only('nome'));
        return redirect()->route('categoria_midia.index')->with('success', 'Categoria de mídia criada com sucesso.');
    }

    public function edit($id)
    {
        $categoria_midia = CategoriaMidia::findOrFail($id);
        return view('categoria_midia.edit', compact('categoria_midia'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:45'],
        ]);

        $categoria_midia = CategoriaMidia::findOrFail($id);
        $categoria_midia->update($request->only('nome'));
        return redirect()->route('categoria_midia.index')->with('success', 'Categoria de mídia atualizada com sucesso.');
    }

    public function destroy($id)
    {
        $categoria_midia = CategoriaMidia::findOrFail($id);
        $categoria_midia->delete();
        return redirect()->route('categoria_midia.index')->with('success', 'Categoria de mídia removida com sucesso.');
    }
}
