<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro interno</title>
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 mb-3">Erro interno</h1>
                    <p class="mb-4">Ocorreu um erro inesperado. Tente novamente em instantes.</p>
                    <a href="{{ route('principal') }}" class="btn btn-primary">Ir para início</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
