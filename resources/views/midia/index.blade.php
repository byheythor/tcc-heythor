@extends('layouts.medilab')

@section('title', 'Mídias')

@section('content')
<div class="section-title">
    <h2>Mídias</h2>
    <p>Gerencie anexos de exames e imagens.</p>
</div>

<div class="row g-4 align-items-start">
    <div class="col-lg-4">
        <div class="icon-box h-100">
            <div class="icon"><i class="fas fa-file-medical-alt"></i></div>
            <h4>Painel de mídias</h4>
            <p>Total cadastrado: <strong>{{ $midias->total() }}</strong></p>
            <div class="d-flex gap-2 flex-wrap mt-2">
                <a href="{{ route('categoria_midia.create') }}" class="btn btn-outline-primary btn-sm">Nova categoria</a>
                <a href="{{ route('midia.create') }}" class="btn btn-primary btn-sm">Nova mídia</a>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('midia.index') }}" class="row g-2">
                    <div class="col-md-5">
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Buscar por tipo">
                    </div>
                    <div class="col-md-4">
                        <select name="dado_biomedico_id" class="form-select">
                            <option value="">Filtrar por dado biomédico</option>
                            @foreach ($dadosBiomedicos as $dado)
                                <option value="{{ $dado->id }}" @selected(request('dado_biomedico_id') == $dado->id)>{{ $dado->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary">Filtrar</button>
                        <a href="{{ route('midia.index') }}" class="btn btn-outline-secondary">Limpar</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Dado biomédico</th>
                                <th>Categoria</th>
                                <th>Anexo</th>
                                <th class="text-end" style="width: 200px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($midias as $midia)
                                <tr>
                                    <td>{{ $midia->tipo ?: '-' }}</td>
                                    <td>{{ optional($midia->dado_biomedico)->nome ?: '-' }}</td>
                                    <td>{{ optional($midia->categoria_midia)->nome ?: '-' }}</td>
                                    <td>
                                        @if ($midia->anexo)
                                            <a href="{{ route('midia.anexo', $midia->id) }}" target="_blank" rel="noopener">Visualizar</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('midia.edit', $midia->id) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                        <a href="{{ route('midia.delete', $midia->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Confirma exclusão?')">Excluir</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">Nenhuma mídia cadastrada.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $midias->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
