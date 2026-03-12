@extends('layouts.medilab')

@section('title', 'Dados Biomédicos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="section-title mb-0">
        <h2>Dados Biomédicos</h2>
        <p>Cadastre e gerencie os dados de referência para acompanhamento.</p>
    </div>
    <a href="{{ route('dado_biomedico.create') }}" class="btn btn-primary">Novo dado</a>
</div>

<form method="GET" action="{{ route('dado_biomedico.index') }}" class="row g-2 mb-3">
    <div class="col-md-4">
        <select name="categoria_id" class="form-select">
            <option value="">Filtrar por categoria</option>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}" @selected(request('categoria_id') == $categoria->id)>{{ $categoria->nome }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <select name="medico_id" class="form-select">
            <option value="">Filtrar por médico</option>
            @foreach ($medicos as $medico)
                <option value="{{ $medico->id }}" @selected(request('medico_id') == $medico->id)>{{ $medico->nome }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <button type="submit" class="btn btn-outline-primary">Filtrar</button>
        <a href="{{ route('dado_biomedico.index') }}" class="btn btn-outline-secondary">Limpar</a>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Valor referencial</th>
                <th>Unidade</th>
                <th>Médico</th>
                <th>Categoria</th>
                <th style="width: 180px;">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dados_biomedicos as $dado)
                <tr>
                    <td>{{ $dado->id }}</td>
                    <td>{{ $dado->nome }}</td>
                    <td>{{ $dado->valor_referencia }}</td>
                    <td>{{ $dado->unidade_medida }}</td>
                    <td>{{ optional($dado->medico)->nome }}</td>
                    <td>{{ optional($dado->categoria)->nome }}</td>
                    <td>
                        <a href="{{ route('dado_biomedico.edit', $dado->id) }}" class="btn btn-sm btn-secondary">Editar</a>
                        <a href="{{ route('dado_biomedico.delete', $dado->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Confirma exclusão?')">Excluir</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Nenhum dado biomédico cadastrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
