<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sessão expirada</title>
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 mb-3">Sessão expirada</h1>
                    <p class="mb-3">Sua sessão expirou por tempo de inatividade ou alteração de token de segurança.</p>
                    <p class="mb-4">Atualize a página e tente novamente. Se o erro persistir, saia e entre novamente no sistema.</p>
                    <a href="{{ url()->previous() }}" class="btn btn-outline-primary">Voltar</a>
                    <a href="{{ route('login') }}" class="btn btn-primary">Ir para login</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
