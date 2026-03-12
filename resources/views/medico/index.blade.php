@extends('layouts.medilab')

@section('title', 'Médicos')

@section('content')
<div class="section-title">
    <h2>Médicos</h2>
    <p>Gerencie os médicos associados aos seus dados biomédicos.</p>
</div>

<div class="row g-4 align-items-start">
    <div class="col-lg-4">
        <div class="icon-box h-100">
            <div class="icon"><i class="fas fa-user-md"></i></div>
            <h4>Painel de médicos</h4>
            <p>Total cadastrado: <strong>{{ $medicos->total() }}</strong></p>
            <div class="d-flex gap-2 flex-wrap mt-2">
                <a href="{{ route('especialidade.create') }}" class="btn btn-outline-primary btn-sm">Nova especialidade</a>
                <a href="{{ route('medico.create') }}" class="btn btn-primary btn-sm">Novo médico</a>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('medico.index') }}" class="row g-2">
                    <div class="col-md-5">
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Buscar por nome, CRM ou contato">
                    </div>
                    <div class="col-md-4">
                        <select name="especialidade_id" class="form-select">
                            <option value="">Filtrar por especialidade</option>
                            @foreach ($especialidades as $especialidade)
                                <option value="{{ $especialidade->id }}" @selected((string) request('especialidade_id') === (string) $especialidade->id)>{{ $especialidade->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary">Filtrar</button>
                        <a href="{{ route('medico.index') }}" class="btn btn-outline-secondary">Limpar</a>
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
                                <th>CRM</th>
                                <th>Contato</th>
                                <th>Especialidade</th>
                                <th class="text-end" style="width: 200px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($medicos as $medico)
                                <tr>
                                    <td>{{ $medico->id }}</td>
                                    <td>{{ $medico->nome }}</td>
                                    <td>{{ $medico->crm ?: '-' }}</td>
                                    <td>{{ $medico->contato ?: '-' }}</td>
                                    <td>{{ optional($medico->especialidade)->nome ?: '-' }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('medico.edit', $medico->id) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                        <a href="{{ route('medico.delete', $medico->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Confirma exclusão?')">Excluir</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">Nenhum médico cadastrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $medicos->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
