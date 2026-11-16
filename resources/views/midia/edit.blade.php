@extends('layouts.medilab')

@section('title', 'Editar Mídia')

@section('content')
<div class="section-title">
    <h2>Editar Mídia</h2>
    <p>Atualize metadados da mídia e, se necessário, troque o arquivo.</p>
</div>

<form method="POST" action="{{ route('midia.update', $midia->id) }}" enctype="multipart/form-data" class="row g-3">
    @csrf
    <div class="col-md-6">
        <label for="tipo" class="form-label">Tipo</label>
        <input type="text" name="tipo" id="tipo" value="{{ old('tipo', $midia->tipo) }}" class="form-control" maxlength="45">
    </div>
    <div class="col-md-6">
        <label for="anexo" class="form-label">Novo arquivo (opcional)</label>
        <input type="file" name="anexo" id="anexo" class="form-control">
    </div>
    <div class="col-md-6">
        <label for="dado_biomedico_id" class="form-label">Dado biomédico</label>
        <select name="dado_biomedico_id" id="dado_biomedico_id" class="form-select" required>
            <option value="">Selecione...</option>
            @foreach ($dadosBiomedicos as $dado)
                <option value="{{ $dado->id }}" @selected(old('dado_biomedico_id', $midia->dado_biomedico_id) == $dado->id)>{{ $dado->nome }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <div class="d-flex justify-content-between align-items-center">
            <label for="categoria_midia_id" class="form-label mb-0">Categoria de mídia</label>
            <a href="{{ route('categoria_midia.create') }}" class="small" target="_blank">+ Criar categoria</a>
        </div>
        <select name="categoria_midia_id" id="categoria_midia_id" class="form-select" required>
            <option value="">Selecione...</option>
            @foreach ($categoriasMidia as $categoria)
                <option value="{{ $categoria->id }}" @selected(old('categoria_midia_id', $midia->categoria_midia_id) == $categoria->id)>{{ $categoria->nome }}</option>
            @endforeach
        </select>
    </div>

    @if ($midia->anexo)
        <div class="col-12">
            <a href="{{ route('midia.anexo', $midia->id) }}" target="_blank" rel="noopener">Arquivo atual</a>
        </div>
    @endif

    <div class="col-12">
        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="{{ route('midia.index') }}" class="btn btn-light">Cancelar</a>
    </div>
</form>
@endsection
