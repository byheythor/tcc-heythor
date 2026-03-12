@extends('layouts.medilab')

@section('title', 'Categorias')

@section('content')
<div class="section-title">
    <h2>Categorias</h2>
    <p>Gerencie as categorias dos seus dados biomédicos.</p>
</div>

<div class="row g-4 align-items-start">
    <div class="col-lg-4">
        <div class="icon-box h-100">
            <div class="icon"><i class="fas fa-layer-group"></i></div>
            <h4>Painel de categorias</h4>
            <p>Total cadastrado: <strong>{{ $categorias->total() }}</strong></p>
            <a href="{{ route('categoria.create') }}" class="btn btn-primary btn-sm mt-2">Nova categoria</a>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('categoria.index') }}" class="row g-2">
                    <div class="col-md-8">
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Buscar por nome">
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary">Filtrar</button>
                        <a href="{{ route('categoria.index') }}" class="btn btn-outline-secondary">Limpar</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th class="text-end" style="width: 200px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categorias as $categoria)
                                <tr>
                                    <td>{{ $categoria->id }}</td>
                                    <td>{{ $categoria->nome }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('categoria.edit', $categoria->id) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                        <a href="{{ route('categoria.delete', $categoria->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Confirma exclusão?')">Excluir</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4">Nenhuma categoria cadastrada.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $categorias->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
