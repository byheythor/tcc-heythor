@extends('layouts.medilab')

@section('title', 'Médicos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="section-title mb-0">
        <h2>Médicos</h2>
        <p>Gerencie os médicos associados aos seus dados biomédicos.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('especialidade.create') }}" class="btn btn-outline-primary">Nova especialidade</a>
        <a href="{{ route('medico.create') }}" class="btn btn-primary">Novo médico</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>CRM</th>
                <th>Contato</th>
                <th>Especialidade</th>
                <th style="width: 180px;">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($medicos as $medico)
                <tr>
                    <td>{{ $medico->id }}</td>
                    <td>{{ $medico->nome }}</td>
                    <td>{{ $medico->crm }}</td>
                    <td>{{ $medico->contato }}</td>
                    <td>{{ optional($medico->especialidade)->nome }}</td>
                    <td>
                        <a href="{{ route('medico.edit', $medico->id) }}" class="btn btn-sm btn-secondary">Editar</a>
                        <a href="{{ route('medico.delete', $medico->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Confirma exclusão?')">Excluir</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Nenhum médico cadastrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
