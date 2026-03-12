@extends('layouts.medilab')

@section('title', 'Nova Medição')

@section('content')
<div class="section-title">
    <h2>Nova Medição</h2>
    <p>Registre medições para acompanhar a evolução dos seus indicadores.</p>
</div>

<div class="row g-4 align-items-start">
    <div class="col-lg-4">
        <div class="icon-box h-100">
            <div class="icon"><i class="fas fa-chart-line"></i></div>
            <h4>Registro de medição</h4>
            <p>Informe o valor medido, data/hora e o dado biomédico relacionado.</p>
            <a href="{{ route('medicao.index') }}" class="btn btn-sm btn-outline-primary mt-2">Ver medições</a>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-lg-5">
                <form method="POST" action="{{ route('medicao.store') }}" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" name="nome" id="nome" value="{{ old('nome') }}" class="form-control" required maxlength="100" placeholder="Ex.: Aferição matinal">
                    </div>
                    <div class="col-md-3">
                        <label for="valor" class="form-label">Valor</label>
                        <input type="text" name="valor" id="valor" value="{{ old('valor') }}" class="form-control" required maxlength="45" placeholder="Ex.: 95">
                    </div>
                    <div class="col-md-3">
                        <label for="data_hora" class="form-label">Data e hora</label>
                        <input type="datetime-local" name="data_hora" id="data_hora" value="{{ old('data_hora') }}" class="form-control" required>
                    </div>
                    <div class="col-md-8">
                        <label for="dado_biomedico_id" class="form-label">Dado biomédico</label>
                        <select name="dado_biomedico_id" id="dado_biomedico_id" class="form-select" required>
                            <option value="">Selecione...</option>
                            @foreach ($dadosBiomedicos as $dado)
                                <option value="{{ $dado->id }}" @selected(old('dado_biomedico_id') == $dado->id)>{{ $dado->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Salvar medição</button>
                        <a href="{{ route('medicao.index') }}" class="btn btn-light">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
