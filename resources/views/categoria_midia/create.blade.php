@extends('layouts.medilab')

@section('title', 'Nova Categoria de Mídia')

@section('content')
<div class="section-title">
    <h2>Nova Categoria de Mídia</h2>
    <p>Cadastre uma categoria para classificação dos anexos.</p>
</div>

<form method="POST" action="{{ route('categoria_midia.store') }}" class="row g-3">
    @csrf
    <div class="col-md-8">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" name="nome" id="nome" value="{{ old('nome') }}" class="form-control" required maxlength="45">
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('categoria_midia.index') }}" class="btn btn-light">Cancelar</a>
    </div>
</form>
@endsection
