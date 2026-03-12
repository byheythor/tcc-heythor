<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Exibir uma lista de usuários
        $users = User::all();
        return view('user.index', compact('users'));
    }

    public function create()
    {
        // Exibir o formulário de criação de usuário
        return view('user.create');
    }

    public function store(Request $request)
    {
        // Salvar um novo usuário no banco de dados
        User::create($request->all());
        return redirect()->route('user.index');
    }

    public function show($id)
    {
        // Exibir detalhes de um usuário específico
        $user = User::findOrFail($id);
        return view('user.show', compact('user'));
    }

    public function edit($id)
    {
        // Exibir o formulário de edição de um usuário específico
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        // Atualizar um usuário no banco de dados
        $user = User::findOrFail($id);
        $user->update($request->all());
        return redirect()->route('user.index');
    }

    public function destroy($id)
    {
        // Excluir um usuário do banco de dados
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('user.index');
    }
}
