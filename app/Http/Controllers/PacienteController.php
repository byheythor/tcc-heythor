<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    public function index()
    {
        // Exibir uma lista de pacientes
        $pacientes = Paciente::all();
        return view('paciente.index', compact('pacientes'));
    }

    public function create()
    {
        // Exibir o formulário de criação de paciente
        return view('paciente.create');
    }

    public function store(Request $request)
    {
        // Salvar um novo paciente no banco de dados
        Paciente::create($request->all());
        return redirect()->route('paciente.index');
    }

    public function show($id)
    {
        // Exibir detalhes de um paciente específico
        $paciente = Paciente::findOrFail($id);
        return view('paciente.show', compact('paciente'));
    }

    public function edit($id)
    {
        // Exibir o formulário de edição de um paciente específico
        $paciente = Paciente::findOrFail($id);
        return view('paciente.edit', compact('paciente'));
    }

    public function update(Request $request, $id)
    {
        // Atualizar um paciente no banco de dados
        $paciente = Paciente::findOrFail($id);
        $paciente->update($request->all());
        return redirect()->route('paciente.index');
    }

    public function destroy($id)
    {
        // Excluir um paciente do banco de dados
        $paciente = Paciente::findOrFail($id);
        $paciente->delete();
        return redirect()->route('paciente.index');
    }
}
