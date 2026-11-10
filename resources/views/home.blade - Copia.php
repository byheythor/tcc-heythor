@extends('layouts.medilab')

@section('title', 'Home')

@section('content')
<div class="section-title">
    <h2>Bem-vindo</h2>
    <p>Você está autenticado no sistema.</p>
</div>

<div class="text-center">
    <a href="{{ route('principal') }}" class="btn btn-primary">Ir para o painel</a>
</div>
@endsection
