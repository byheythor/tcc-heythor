@extends('layouts.medilab')

@section('title', 'Meu Perfil')

@section('content')
@php
    $cpfMask = $user->cpf ? preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', preg_replace('/\D+/', '', $user->cpf)) : '-';
    $celularDigits = preg_replace('/\D+/', '', (string) $user->celular);
    $celularMask = '-';
    if (strlen($celularDigits) === 11) {
        $celularMask = preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $celularDigits);
    } elseif (strlen($celularDigits) === 10) {
        $celularMask = preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $celularDigits);
    } elseif ($celularDigits !== '') {
        $celularMask = $user->celular;
    }
@endphp

<div class="section-title">
    <h2>Meu Perfil</h2>
    <p>Visualize e atualize seus dados pessoais de acesso ao sistema.</p>
</div>

<div class="row g-4 align-items-start">
    <div class="col-lg-4">
        <div class="icon-box h-100">
            <div class="icon"><i class="fas fa-user-circle"></i></div>
            <h4>Resumo da conta</h4>
            <p><strong>Nome:</strong> {{ $user->nome }}</p>
            <p><strong>E-mail:</strong> {{ $user->email }}</p>
            <p><strong>CPF:</strong> {{ $cpfMask }}</p>
            <p><strong>Celular:</strong> {{ $celularMask }}</p>
            <p><strong>Nascimento:</strong> {{ optional($user->data_nascimento)->format('d/m/Y') ?: '-' }}</p>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-lg-5">
                <form id="profile-form" method="POST" action="{{ route('profile.update') }}" class="row g-3">
                    @csrf

                    <div class="col-md-6">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', $user->nome) }}" maxlength="100" required>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" maxlength="45" required>
                    </div>

                    <div class="col-md-4">
                        <label for="cpf" class="form-label">CPF</label>
                        <input type="text" class="form-control" id="cpf" name="cpf" value="{{ old('cpf', $user->cpf) }}" maxlength="14" placeholder="000.000.000-00" inputmode="numeric">
                    </div>

                    <div class="col-md-4">
                        <label for="celular" class="form-label">Celular</label>
                        <input type="text" class="form-control" id="celular" name="celular" value="{{ old('celular', $user->celular) }}" maxlength="15" placeholder="(11) 99999-8888" inputmode="numeric">
                    </div>

                    <div class="col-md-4">
                        <label for="data_nascimento" class="form-label">Data de nascimento</label>
                        <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" value="{{ old('data_nascimento', optional($user->data_nascimento)->format('Y-m-d')) }}">
                    </div>

                    <div class="col-12">
                        <hr>
                        <h5 class="mb-2">Alterar senha</h5>
                        <p class="small text-muted mb-0">Preencha apenas se quiser trocar a senha atual.</p>
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label">Nova senha</label>
                        <input type="password" class="form-control" id="password" name="password" minlength="8">
                    </div>

                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Confirmar nova senha</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" minlength="8">
                    </div>

                </form>
                <div class="d-flex flex-wrap align-items-center gap-2 mt-3">
                    <button type="submit" form="profile-form" class="btn btn-primary">Salvar perfil</button>
                    <a href="{{ route('principal') }}" class="btn btn-light">Voltar</a>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-danger fw-semibold px-3">
                            <i class="bi bi-box-arrow-right me-1"></i>Sair
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const cpfInput = document.getElementById('cpf');
        const celularInput = document.getElementById('celular');

        function onlyDigits(value) {
            return (value || '').replace(/\D/g, '');
        }

        function formatCpf(value) {
            const digits = onlyDigits(value).slice(0, 11);
            if (digits.length <= 3) return digits;
            if (digits.length <= 6) return digits.replace(/(\d{3})(\d+)/, '$1.$2');
            if (digits.length <= 9) return digits.replace(/(\d{3})(\d{3})(\d+)/, '$1.$2.$3');
            return digits.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
        }

        function formatCelular(value) {
            const digits = onlyDigits(value).slice(0, 11);
            if (digits.length <= 2) return digits;
            if (digits.length <= 6) return digits.replace(/(\d{2})(\d+)/, '($1) $2');
            if (digits.length <= 10) return digits.replace(/(\d{2})(\d{4})(\d+)/, '($1) $2-$3');
            return digits.replace(/(\d{2})(\d{5})(\d+)/, '($1) $2-$3');
        }

        if (cpfInput) {
            cpfInput.value = formatCpf(cpfInput.value);
            cpfInput.addEventListener('input', function () {
                this.value = formatCpf(this.value);
            });
        }

        if (celularInput) {
            celularInput.value = formatCelular(celularInput.value);
            celularInput.addEventListener('input', function () {
                this.value = formatCelular(this.value);
            });
        }
    })();
</script>
@endsection
