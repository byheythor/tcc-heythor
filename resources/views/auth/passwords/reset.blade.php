<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Redefinir senha | Gerenciador Pessoal de Dados Biomédicos</title>

    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/animate.css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/responsive.css') }}" rel="stylesheet">

    <style>
        .auth-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 140px;
            padding-bottom: 40px;
            background: linear-gradient(rgba(255, 255, 255, 0.88), rgba(255, 255, 255, 0.92)), url('{{ asset('assets/img/hero-bg.jpg') }}') center center;
            background-size: cover;
        }

        .auth-card {
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(25, 119, 204, 0.15);
            border: 0;
        }
    </style>
</head>

<body class="app-shell auth-page">
    <div id="topbar" class="d-flex align-items-center fixed-top">
        <div class="container d-flex justify-content-between">
            <div class="contact-info d-flex align-items-center">
                <i class="bi bi-heart-pulse"></i> Gerenciador Pessoal de Dados Biomédicos
            </div>
        </div>
    </div>

    <header id="header" class="fixed-top">
        <div class="container d-flex align-items-center">
            <h1 class="logo me-auto"><a href="{{ url('/') }}">Dados Biomédicos</a></h1>
            <nav id="navbar" class="navbar order-last order-lg-0">
                <ul id="navigation-links">
                    <li><a class="nav-link" href="{{ route('login') }}">Entrar</a></li>
                </ul>
                <button type="button" class="bi bi-list mobile-nav-toggle" aria-label="Abrir menu" aria-expanded="false" aria-controls="navigation-links"></button>
            </nav>
        </div>
    </header>

    <section class="auth-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="card auth-card">
                        <div class="card-body p-4 p-md-5">
                            <div class="section-title pb-2">
                                <h2>Redefinir senha</h2>
                                <p>Defina uma nova senha para sua conta.</p>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('password.update', [], false) }}" class="row g-3">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="col-12">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                                </div>

                                <div class="col-md-6">
                                    <label for="password" class="form-label">Nova senha</label>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                </div>

                                <div class="col-md-6">
                                    <label for="password-confirm" class="form-label">Confirmar senha</label>
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>

                                <div class="col-12 d-grid">
                                    <button type="submit" class="btn btn-primary">Redefinir senha</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>

</html>
