@extends('layouts.medilab')

@section('title', 'Editar Especialidade')

@section('content')
<div class="section-title">
    <h2>Editar Especialidade</h2>
    <p>Atualize os dados da especialidade.</p>
</div>

<form method="POST" action="{{ route('especialidade.update', $especialidade->id) }}" class="row g-3">
    @csrf
    <div class="col-md-8">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" name="nome" id="nome" value="{{ old('nome', $especialidade->nome) }}" class="form-control" required maxlength="45">
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="{{ route('especialidade.index') }}" class="btn btn-light">Cancelar</a>
    </div>
</form>
@endsection
