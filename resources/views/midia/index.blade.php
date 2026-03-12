@extends('layouts.medilab')

@section('title', 'Mídias')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="section-title mb-0">
        <h2>Mídias</h2>
        <p>Gerencie anexos de exames e imagens.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('categoria_midia.create') }}" class="btn btn-outline-primary">Nova categoria de mídia</a>
        <a href="{{ route('midia.create') }}" class="btn btn-primary">Nova mídia</a>
    </div>
</div>

<form method="GET" action="{{ route('midia.index') }}" class="row g-2 mb-3">
    <div class="col-md-6">
        <select name="dado_biomedico_id" class="form-select">
            <option value="">Filtrar por dado biomédico</option>
            @foreach ($dadosBiomedicos as $dado)
                <option value="{{ $dado->id }}" @selected(request('dado_biomedico_id') == $dado->id)>{{ $dado->nome }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <button type="submit" class="btn btn-outline-primary">Filtrar</button>
        <a href="{{ route('midia.index') }}" class="btn btn-outline-secondary">Limpar</a>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Dado biomédico</th>
                <th>Categoria</th>
                <th>Anexo</th>
                <th style="width: 180px;">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($midias as $midia)
                <tr>
                    <td>{{ $midia->id }}</td>
                    <td>{{ $midia->tipo }}</td>
                    <td>{{ optional($midia->dado_biomedico)->nome }}</td>
                    <td>{{ optional($midia->categoria_midia)->nome }}</td>
                    <td>
                        @if ($midia->anexo)
                            <a href="{{ asset('storage/' . $midia->anexo) }}" target="_blank">Visualizar</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('midia.edit', $midia->id) }}" class="btn btn-sm btn-secondary">Editar</a>
                        <a href="{{ route('midia.delete', $midia->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Confirma exclusão?')">Excluir</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Nenhuma mídia cadastrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
