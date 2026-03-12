@extends('layouts.medilab')

@section('title', 'Nova Mídia')

@section('content')
<div class="section-title">
    <h2>Nova Mídia</h2>
    <p>Envie imagem ou PDF associado a um dado biomédico.</p>
</div>

@if ($dadosBiomedicos->isEmpty())
    <div class="alert alert-warning">
        Nenhum dado biomédico cadastrado. Cadastre primeiro para anexar mídias.
        <a href="{{ route('dado_biomedico.create') }}" class="ms-1">Cadastrar dado biomédico</a>
    </div>
@endif

@if ($categoriasMidia->isEmpty())
    <div class="alert alert-warning">
        Nenhuma categoria de mídia cadastrada. Cadastre uma categoria para continuar.
        <a href="{{ route('categoria_midia.create') }}" class="ms-1">Cadastrar categoria de mídia</a>
    </div>
@endif

<div class="row g-4 align-items-start">
    <div class="col-lg-4">
        <div class="icon-box h-100">
            <div class="icon"><i class="fas fa-file-medical"></i></div>
            <h4>Upload de mídia</h4>
            <p>Anexe documentos, imagens e laudos para manter o histórico completo.</p>
            <div class="d-flex gap-2 flex-wrap mt-2">
                <a href="{{ route('categoria_midia.create') }}" class="btn btn-sm btn-outline-primary">Nova categoria de mídia</a>
                <a href="{{ route('midia.index') }}" class="btn btn-sm btn-outline-secondary">Ver mídias</a>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-lg-5">
                <form method="POST" action="{{ route('midia.store') }}" enctype="multipart/form-data" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label for="tipo" class="form-label">Tipo</label>
                        <input type="text" name="tipo" id="tipo" value="{{ old('tipo') }}" class="form-control" maxlength="45" placeholder="Ex.: Exame de sangue">
                    </div>
                    <div class="col-md-6">
                        <label for="anexo" class="form-label">Arquivo (jpg, png, pdf)</label>
                        <input type="file" name="anexo" id="anexo" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="dado_biomedico_id" class="form-label">Dado biomédico</label>
                        <select name="dado_biomedico_id" id="dado_biomedico_id" class="form-select" required>
                            <option value="">Selecione...</option>
                            @foreach ($dadosBiomedicos as $dado)
                                <option value="{{ $dado->id }}" @selected(old('dado_biomedico_id') == $dado->id)>{{ $dado->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="categoria_midia_id" class="form-label mb-0">Categoria de mídia</label>
                            <a href="{{ route('categoria_midia.create') }}" class="small">+ Criar categoria</a>
                        </div>
                        <select name="categoria_midia_id" id="categoria_midia_id" class="form-select" required>
                            <option value="">Selecione...</option>
                            @foreach ($categoriasMidia as $categoria)
                                <option value="{{ $categoria->id }}" @selected(old('categoria_midia_id') == $categoria->id)>{{ $categoria->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-primary" {{ ($dadosBiomedicos->isEmpty() || $categoriasMidia->isEmpty()) ? 'disabled' : '' }}>Salvar mídia</button>
                        <a href="{{ route('midia.index') }}" class="btn btn-light">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
