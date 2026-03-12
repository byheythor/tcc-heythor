@extends('layouts.medilab')

@section('title', 'Nova Categoria de Mídia')

@section('content')
<div class="section-title">
    <h2>Nova Categoria de Mídia</h2>
    <p>Cadastre categorias para organizar arquivos e anexos.</p>
</div>

<div class="row g-4 align-items-start">
    <div class="col-lg-4">
        <div class="icon-box h-100">
            <div class="icon"><i class="fas fa-folder-open"></i></div>
            <h4>Cadastro de categoria de mídia</h4>
            <p>Classifique imagens, exames e documentos em grupos claros.</p>
            <a href="{{ route('categoria_midia.index') }}" class="btn btn-sm btn-outline-primary mt-2">Ver categorias de mídia</a>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-lg-5">
                <form method="POST" action="{{ route('categoria_midia.store') }}" class="row g-3">
                    @csrf
                    <div class="col-12">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" name="nome" id="nome" value="{{ old('nome') }}" class="form-control" required maxlength="45" placeholder="Ex.: Exames de imagem">
                    </div>
                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Salvar categoria</button>
                        <a href="{{ route('categoria_midia.index') }}" class="btn btn-light">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
