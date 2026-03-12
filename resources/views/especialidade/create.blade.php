@extends('layouts.medilab')

@section('title', 'Nova Especialidade')

@section('content')
<div class="section-title">
    <h2>Nova Especialidade</h2>
    <p>Cadastre especialidades para organizar melhor os médicos.</p>
</div>

<div class="row g-4 align-items-start">
    <div class="col-lg-4">
        <div class="icon-box h-100">
            <div class="icon"><i class="fas fa-notes-medical"></i></div>
            <h4>Cadastro de especialidade</h4>
            <p>Este cadastro será usado no formulário de médicos.</p>
            <a href="{{ route('especialidade.index') }}" class="btn btn-sm btn-outline-primary mt-2">Ver especialidades</a>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-lg-5">
                <form method="POST" action="{{ route('especialidade.store') }}" class="row g-3">
                    @csrf
                    <div class="col-12">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" name="nome" id="nome" value="{{ old('nome') }}" class="form-control" required maxlength="45" placeholder="Ex.: Cardiologia">
                    </div>
                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Salvar especialidade</button>
                        <a href="{{ route('especialidade.index') }}" class="btn btn-light">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
