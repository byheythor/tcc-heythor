@extends('layouts.medilab')

@section('title', 'Especialidades')

@section('content')
<div class="section-title">
    <h2>Especialidades</h2>
    <p>Gerencie as especialidades médicas cadastradas.</p>
</div>

<div class="row g-4 align-items-start">
    <div class="col-lg-4">
        <div class="icon-box h-100">
            <div class="icon"><i class="fas fa-stethoscope"></i></div>
            <h4>Painel de especialidades</h4>
            <p>Total cadastrado: <strong>{{ $especialidades->total() }}</strong></p>
            <a href="{{ route('especialidade.create') }}" class="btn btn-primary btn-sm mt-2">Nova especialidade</a>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('especialidade.index') }}" class="row g-2">
                    <div class="col-md-8">
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Buscar por nome">
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary">Filtrar</button>
                        <a href="{{ route('especialidade.index') }}" class="btn btn-outline-secondary">Limpar</a>
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
                                <th>Nome</th>
                                <th class="text-end" style="width: 200px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($especialidades as $especialidade)
                                <tr>
                                    <td>{{ $especialidade->nome }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('especialidade.edit', $especialidade->id) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                        <a href="{{ route('especialidade.delete', $especialidade->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Confirma exclusão?')">Excluir</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center py-4">Nenhuma especialidade cadastrada.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $especialidades->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
