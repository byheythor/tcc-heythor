@extends('layouts.medilab')

@section('title', 'Medições')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="section-title mb-0">
        <h2>Medições</h2>
        <p>Registre as medições dos seus dados biomédicos.</p>
    </div>
    <a href="{{ route('medicao.create') }}" class="btn btn-primary">Nova medição</a>
</div>

<form method="GET" action="{{ route('medicao.index') }}" class="row g-2 mb-3">
    <div class="col-md-6">
        <select name="dado_biomedico_id" class="form-select">
            <option value="">Filtrar por dado biomédico</option>
            @foreach ($dadosBiomedicos as $dado)
                <option value="{{ $dado->id }}" @selected(request('dado_biomedico_id') == $dado->id)>{{ $dado->nome }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <button type="submit" class="btn btn-outline-primary">Filtrar</button>
        <a href="{{ route('medicao.index') }}" class="btn btn-outline-secondary">Limpar</a>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Valor</th>
                <th>Data/Hora</th>
                <th>Dado biomédico</th>
                <th style="width: 180px;">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($medicoes as $medicao)
                <tr>
                    <td>{{ $medicao->id }}</td>
                    <td>{{ $medicao->nome }}</td>
                    <td>{{ $medicao->valor }}</td>
                    <td>{{ optional($medicao->data_hora)->format('d/m/Y H:i') }}</td>
                    <td>{{ optional($medicao->dado_biomedico)->nome }}</td>
                    <td>
                        <a href="{{ route('medicao.edit', $medicao->id) }}" class="btn btn-sm btn-secondary">Editar</a>
                        <a href="{{ route('medicao.delete', $medicao->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Confirma exclusão?')">Excluir</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Nenhuma medição cadastrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
