@extends('layouts.medilab')

@section('title', 'Categorias')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="section-title mb-0">
        <h2>Categorias</h2>
        <p>Gerencie as categorias dos seus dados biomédicos.</p>
    </div>
    <a href="{{ route('categoria.create') }}" class="btn btn-primary">Nova categoria</a>
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
            @forelse ($categorias as $categoria)
                <tr>
                    <td>{{ $categoria->id }}</td>
                    <td>{{ $categoria->nome }}</td>
                    <td>
                        <a href="{{ route('categoria.edit', $categoria->id) }}" class="btn btn-sm btn-secondary">Editar</a>
                        <a href="{{ route('categoria.delete', $categoria->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Confirma exclusão?')">Excluir</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Nenhuma categoria cadastrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
