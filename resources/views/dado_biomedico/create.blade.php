@extends('layouts.medilab')

@section('title', 'Novo Dado Biomédico')

@section('content')
<div class="section-title">
    <h2>Novo Dado Biomédico</h2>
    <p>Registre um novo tipo de dado biomédico para acompanhar no sistema.</p>
</div>

<div class="row g-4 align-items-start">
    <div class="col-lg-4">
        <div class="icon-box h-100">
            <div class="icon"><i class="fas fa-heartbeat"></i></div>
            <h4>Cadastro de dado biomédico</h4>
            <p>Defina referência, unidade e vínculos para facilitar medições futuras.</p>
            <div class="d-flex gap-2 flex-wrap mt-2">
                <a href="{{ route('dado_biomedico.index') }}" class="btn btn-sm btn-outline-secondary">Ver dados biomédicos</a>
                <a href="{{ route('medicao.create') }}" class="btn btn-sm btn-outline-primary">Nova medição</a>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-lg-5">
                <form method="POST" action="{{ route('dado_biomedico.store') }}" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" name="nome" id="nome" value="{{ old('nome') }}" class="form-control" required maxlength="100" placeholder="Ex.: Glicose em jejum">
                    </div>
                    <div class="col-md-3">
                        <label for="valor_referencia" class="form-label">Valor referencial</label>
                        <input type="text" name="valor_referencia" id="valor_referencia" value="{{ old('valor_referencia') }}" class="form-control" required maxlength="30" placeholder="70-99">
                    </div>
                    <div class="col-md-3">
                        <label for="unidade_medida" class="form-label">Unidade de medida</label>
                        <input type="text" name="unidade_medida" id="unidade_medida" value="{{ old('unidade_medida') }}" class="form-control" required maxlength="30" placeholder="mg/dL">
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
                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Salvar dado biomédico</button>
                        <a href="{{ route('dado_biomedico.index') }}" class="btn btn-light">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
