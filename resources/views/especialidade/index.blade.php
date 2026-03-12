@extends('layouts.medilab')

@section('title', 'Especialidades')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="section-title mb-0">
        <h2>Especialidades</h2>
        <p>Gerencie as especialidades médicas cadastradas.</p>
    </div>
    <a href="{{ route('especialidade.create') }}" class="btn btn-primary">Nova especialidade</a>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th style="width: 180px;">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($especialidades as $especialidade)
                <tr>
                    <td>{{ $especialidade->id }}</td>
                    <td>{{ $especialidade->nome }}</td>
                    <td>
                        <a href="{{ route('especialidade.edit', $especialidade->id) }}" class="btn btn-sm btn-secondary">Editar</a>
                        <a href="{{ route('especialidade.delete', $especialidade->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Confirma exclusão?')">Excluir</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Nenhuma especialidade cadastrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
