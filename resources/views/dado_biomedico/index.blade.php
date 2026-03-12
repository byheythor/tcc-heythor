@extends('layouts.medilab')

@section('title', 'Dados Biomédicos')

@section('content')
<div class="section-title">
    <h2>Dados Biomédicos</h2>
    <p>Cadastre e gerencie os dados de referência para acompanhamento.</p>
</div>

<div class="row g-4 align-items-start">
    <div class="col-lg-4">
        <div class="icon-box h-100">
            <div class="icon"><i class="fas fa-heartbeat"></i></div>
            <h4>Painel de dados biomédicos</h4>
            <p>Total cadastrado: <strong>{{ $dados_biomedicos->total() }}</strong></p>
            <a href="{{ route('dado_biomedico.create') }}" class="btn btn-primary btn-sm mt-2">Novo dado biomédico</a>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('dado_biomedico.index') }}" class="row g-2">
                    <div class="col-md-3">
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Buscar por nome">
                    </div>
                    <div class="col-md-3">
                        <select name="categoria_id" class="form-select">
                            <option value="">Filtrar por categoria</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}" @selected(request('categoria_id') == $categoria->id)>{{ $categoria->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="medico_id" class="form-select">
                            <option value="">Filtrar por médico</option>
                            @foreach ($medicos as $medico)
                                <option value="{{ $medico->id }}" @selected(request('medico_id') == $medico->id)>{{ $medico->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary">Filtrar</button>
                        <a href="{{ route('dado_biomedico.index') }}" class="btn btn-outline-secondary">Limpar</a>
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
                                <th>Valor referencial</th>
                                <th>Unidade</th>
                                <th>Médico</th>
                                <th>Categoria</th>
                                <th class="text-end" style="width: 200px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dados_biomedicos as $dado)
                                <tr>
                                    <td>{{ $dado->id }}</td>
                                    <td>{{ $dado->nome }}</td>
                                    <td>{{ $dado->valor_referencia }}</td>
                                    <td>{{ $dado->unidade_medida }}</td>
                                    <td>{{ optional($dado->medico)->nome ?: '-' }}</td>
                                    <td>{{ optional($dado->categoria)->nome ?: '-' }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('dado_biomedico.edit', $dado->id) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                        <a href="{{ route('dado_biomedico.delete', $dado->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Confirma exclusão?')">Excluir</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">Nenhum dado biomédico cadastrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $dados_biomedicos->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
