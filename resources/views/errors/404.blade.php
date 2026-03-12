<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página não encontrada</title>
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 mb-3">Página não encontrada</h1>
                    <p class="mb-4">A URL acessada não existe ou foi removida.</p>
                    <a href="{{ route('principal') }}" class="btn btn-primary">Ir para início</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
