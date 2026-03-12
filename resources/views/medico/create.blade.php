@extends('layouts.medilab')

@section('title', 'Novo Médico')

@section('content')
<div class="section-title">
    <h2>Novo Médico</h2>
    <p>Cadastre um médico para vincular aos dados biomédicos.</p>
</div>

@if ($especialidades->isEmpty())
    <div class="alert alert-warning">
        Nenhuma especialidade cadastrada. Cadastre primeiro para liberar o cadastro de médicos.
        <a href="{{ route('especialidade.create') }}" class="ms-1">Cadastrar especialidade</a>
    </div>
@endif

<form method="POST" action="{{ route('medico.store') }}" class="row g-3">
    @csrf
    <div class="col-md-6">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" name="nome" id="nome" value="{{ old('nome') }}" class="form-control" required maxlength="100">
    </div>
    <div class="col-md-3">
        <label for="crm" class="form-label">CRM</label>
        <input type="text" name="crm" id="crm" value="{{ old('crm') }}" class="form-control" maxlength="20">
    </div>
    <div class="col-md-3">
        <label for="contato" class="form-label">Contato</label>
        <input type="text" name="contato" id="contato" value="{{ old('contato') }}" class="form-control" maxlength="45">
    </div>
    <div class="col-md-6">
        <div class="d-flex justify-content-between align-items-center">
            <label for="especialidade_id" class="form-label mb-0">Especialidade</label>
            <a href="{{ route('especialidade.create') }}" class="small" target="_blank">+ Criar especialidade</a>
        </div>
        <select name="especialidade_id" id="especialidade_id" class="form-select" required>
            <option value="">Selecione...</option>
            @foreach ($especialidades as $especialidade)
                <option value="{{ $especialidade->id }}" @selected(old('especialidade_id') == $especialidade->id)>{{ $especialidade->nome }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary" {{ $especialidades->isEmpty() ? 'disabled' : '' }}>Salvar</button>
        <a href="{{ route('medico.index') }}" class="btn btn-light">Cancelar</a>
    </div>
</form>
@endsection
