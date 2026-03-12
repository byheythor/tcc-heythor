@extends('layouts.medilab')

@section('title', 'Nova Especialidade')

@section('content')
<div class="section-title">
    <h2>Nova Especialidade</h2>
    <p>Cadastre uma especialidade para associar aos médicos.</p>
</div>

<form method="POST" action="{{ route('especialidade.store') }}" class="row g-3">
    @csrf
    <div class="col-md-8">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" name="nome" id="nome" value="{{ old('nome') }}" class="form-control" required maxlength="45">
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('especialidade.index') }}" class="btn btn-light">Cancelar</a>
    </div>
</form>
@endsection
