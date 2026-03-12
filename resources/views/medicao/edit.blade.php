@extends('layouts.medilab')

@section('title', 'Editar Medição')

@section('content')
<div class="section-title">
    <h2>Editar Medição</h2>
    <p>Atualize os dados da medição.</p>
</div>

<form method="POST" action="{{ route('medicao.update', $medicao->id) }}" class="row g-3">
    @csrf
    <div class="col-md-6">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" name="nome" id="nome" value="{{ old('nome', $medicao->nome) }}" class="form-control" required maxlength="100">
    </div>
    <div class="col-md-3">
        <label for="valor" class="form-label">Valor</label>
        <input type="text" name="valor" id="valor" value="{{ old('valor', $medicao->valor) }}" class="form-control" required maxlength="45">
    </div>
    <div class="col-md-3">
        <label for="data_hora" class="form-label">Data e hora</label>
        <input type="datetime-local" name="data_hora" id="data_hora" value="{{ old('data_hora', optional($medicao->data_hora)->format('Y-m-d\\TH:i')) }}" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label for="dado_biomedico_id" class="form-label">Dado biomédico</label>
        <select name="dado_biomedico_id" id="dado_biomedico_id" class="form-select" required>
            <option value="">Selecione...</option>
            @foreach ($dadosBiomedicos as $dado)
                <option value="{{ $dado->id }}" @selected(old('dado_biomedico_id', $medicao->dado_biomedico_id) == $dado->id)>{{ $dado->nome }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="{{ route('medicao.index') }}" class="btn btn-light">Cancelar</a>
    </div>
</form>
@endsection
