<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index(Request $request)
    {
        $query = Categoria::query();

        if ($request->filled('q')) {
            $query->where('nome', 'like', '%' . trim((string) $request->input('q')) . '%');
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

        Categoria::create($request->only('nome'));
        return redirect()->route('categoria.index')->with('success', 'Categoria criada com sucesso.');
    }

    public function edit($id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('categoria.edit', compact('categoria'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:45'],
        ]);

        $categoria = Categoria::findOrFail($id);
        $categoria->update($request->only('nome'));
        return redirect()->route('categoria.index')->with('success', 'Categoria atualizada com sucesso.');
    }

    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->delete();
        return redirect()->route('categoria.index')->with('success', 'Categoria removida com sucesso.');
    }
}
