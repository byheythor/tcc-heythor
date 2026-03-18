@extends('layouts.medilab')

@section('title', 'Medições')

@section('content')
<div class="section-title">
    <h2>Medições</h2>
    <p>Acompanhe os registros de medições dos seus dados biomédicos.</p>
</div>

<div class="row g-4 align-items-start">
    <div class="col-lg-4">
        <div class="icon-box h-100">
            <div class="icon"><i class="fas fa-chart-line"></i></div>
            <h4>Painel de medições</h4>
            <p>Total cadastrado: <strong>{{ $medicoes->total() }}</strong></p>
            <a href="{{ route('medicao.create') }}" class="btn btn-primary btn-sm mt-2">Nova medição</a>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('medicao.index') }}" class="row g-2">
                    <div class="col-md-5">
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Buscar por nome ou valor">
                    </div>
                    <div class="col-md-4">
                        <select name="dado_biomedico_id" class="form-select">
                            <option value="">Filtrar por dado biomédico</option>
                            @foreach ($dadosBiomedicos as $dado)
                                <option value="{{ $dado->id }}" @selected(request('dado_biomedico_id') == $dado->id)>{{ $dado->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary">Filtrar</button>
                        <a href="{{ route('medicao.index') }}" class="btn btn-outline-secondary">Limpar</a>
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
                                <th>Valor</th>
                                <th>Data/Hora</th>
                                <th>Dado biomédico</th>
                                <th class="text-end" style="width: 200px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($medicoes as $medicao)
                                <tr>
                                    <td>{{ $medicao->nome }}</td>
                                    <td>{{ $medicao->valor }}</td>
                                    <td>{{ optional($medicao->data_hora)->format('d/m/Y H:i') }}</td>
                                    <td>{{ optional($medicao->dado_biomedico)->nome ?: '-' }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('medicao.edit', $medicao->id) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                        <a href="{{ route('medicao.delete', $medicao->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Confirma exclusão?')">Excluir</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">Nenhuma medição cadastrada.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $medicoes->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
