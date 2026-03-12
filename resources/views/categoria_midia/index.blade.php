@extends('layouts.medilab')

@section('title', 'Categorias de Mídia')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="section-title mb-0">
        <h2>Categorias de Mídia</h2>
        <p>Organize os tipos de anexos do sistema.</p>
    </div>
    <a href="{{ route('categoria_midia.create') }}" class="btn btn-primary">Nova categoria de mídia</a>
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
            @forelse ($categorias_midia as $categoria)
                <tr>
                    <td>{{ $categoria->id }}</td>
                    <td>{{ $categoria->nome }}</td>
                    <td>
                        <a href="{{ route('categoria_midia.edit', $categoria->id) }}" class="btn btn-sm btn-secondary">Editar</a>
                        <a href="{{ route('categoria_midia.delete', $categoria->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Confirma exclusão?')">Excluir</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Nenhuma categoria de mídia cadastrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
