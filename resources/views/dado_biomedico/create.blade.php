@extends('layouts.medilab')

@section('title', 'Novo Dado Biomédico')

@section('content')
<div class="section-title">
    <h2>Novo Dado Biomédico</h2>
    <p>Registre um novo dado biomédico.</p>
</div>

<form method="POST" action="{{ route('dado_biomedico.store') }}" class="row g-3">
    @csrf
    <div class="col-md-6">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" name="nome" id="nome" value="{{ old('nome') }}" class="form-control" required maxlength="100">
    </div>
    <div class="col-md-3">
        <label for="valor_referencia" class="form-label">Valor referencial</label>
        <input type="text" name="valor_referencia" id="valor_referencia" value="{{ old('valor_referencia') }}" class="form-control" required maxlength="30">
    </div>
    <div class="col-md-3">
        <label for="unidade_medida" class="form-label">Unidade de medida</label>
        <input type="text" name="unidade_medida" id="unidade_medida" value="{{ old('unidade_medida') }}" class="form-control" required maxlength="30">
    </div>
    <div class="col-md-6">
        <label for="medico_id" class="form-label">Médico</label>
        <select name="medico_id" id="medico_id" class="form-select">
            <option value="">Selecione...</option>
            @foreach ($medicos as $medico)
                <option value="{{ $medico->id }}" @selected(old('medico_id') == $medico->id)>{{ $medico->nome }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label for="categoria_id" class="form-label">Categoria</label>
        <select name="categoria_id" id="categoria_id" class="form-select">
            <option value="">Selecione...</option>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}" @selected(old('categoria_id') == $categoria->id)>{{ $categoria->nome }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('dado_biomedico.index') }}" class="btn btn-light">Cancelar</a>
    </div>
</form>
@endsection
