@extends('layouts.medilab')

@section('title', 'Novo Médico')

@section('content')
<div class="section-title">
    <h2>Novo Médico</h2>
    <p>Cadastre médicos para vincular aos dados biomédicos.</p>
</div>

@if ($especialidades->isEmpty())
    <div class="alert alert-warning">
        Nenhuma especialidade cadastrada. Cadastre primeiro para liberar o cadastro de médicos.
        <a href="{{ route('especialidade.create') }}" class="ms-1">Cadastrar especialidade</a>
    </div>
@endif

<div class="row g-4 align-items-start">
    <div class="col-lg-4">
        <div class="icon-box h-100">
            <div class="icon"><i class="fas fa-user-md"></i></div>
            <h4>Cadastro de médico</h4>
            <p>Preencha os dados de identificação e associe a uma especialidade.</p>
            <div class="d-flex gap-2 flex-wrap mt-2">
                <a href="{{ route('especialidade.create') }}" class="btn btn-sm btn-outline-primary">Nova especialidade</a>
                <a href="{{ route('medico.index') }}" class="btn btn-sm btn-outline-secondary">Ver médicos</a>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-lg-5">
                <form method="POST" action="{{ route('medico.store') }}" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" name="nome" id="nome" value="{{ old('nome') }}" class="form-control" required maxlength="100" placeholder="Nome completo do médico">
                    </div>
                    <div class="col-md-3">
                        <label for="crm" class="form-label">CRM</label>
                        <input type="text" name="crm" id="crm" value="{{ old('crm') }}" class="form-control" maxlength="20" placeholder="UF123456">
                    </div>
                    <div class="col-md-3">
                        <label for="contato" class="form-label">Contato</label>
                        <input type="text" name="contato" id="contato" value="{{ old('contato') }}" class="form-control" maxlength="45" placeholder="Telefone ou e-mail">
                    </div>
                    <div class="col-md-8">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="especialidade_id" class="form-label mb-0">Especialidade</label>
                            <a href="{{ route('especialidade.create') }}" class="small">+ Criar especialidade</a>
                        </div>
                        <select name="especialidade_id" id="especialidade_id" class="form-select" required>
                            <option value="">Selecione...</option>
                            @foreach ($especialidades as $especialidade)
                                <option value="{{ $especialidade->id }}" @selected(old('especialidade_id') == $especialidade->id)>{{ $especialidade->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-primary" {{ $especialidades->isEmpty() ? 'disabled' : '' }}>Salvar médico</button>
                        <a href="{{ route('medico.index') }}" class="btn btn-light">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
