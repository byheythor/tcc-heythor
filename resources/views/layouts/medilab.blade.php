<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>@yield('title', 'Gerenciador Pessoal de Dados Biomédicos')</title>

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
</head>

<body>
    <div id="topbar" class="d-flex align-items-center fixed-top">
        <div class="container d-flex justify-content-between">
            <div class="contact-info d-flex align-items-center">
                <i class="bi bi-person"></i>
                {{ auth()->user()->nome ?? auth()->user()->name ?? 'Usuário' }}
                <i class="bi bi-envelope ms-3"></i>
                {{ auth()->user()->email ?? '-' }}
            </div>
        </div>
    </div>

    <header id="header" class="fixed-top">
        <div class="container d-flex align-items-center">
            <h1 class="logo me-auto"><a href="{{ route('principal') }}">Dados Biomédicos</a></h1>
            <nav id="navbar" class="navbar order-last order-lg-0">
                <ul>
                    <li><a class="nav-link" href="{{ route('principal') }}">Início</a></li>
                    <li class="dropdown">
                        <a href="#"><span>Cadastros</span> <i class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li><a href="{{ route('especialidade.create') }}">Nova especialidade</a></li>
                            <li><a href="{{ route('medico.create') }}">Novo médico</a></li>
                            <li><a href="{{ route('categoria.create') }}">Nova categoria</a></li>
                            <li><a href="{{ route('dado_biomedico.create') }}">Novo dado biomédico</a></li>
                            <li><a href="{{ route('categoria_midia.create') }}">Nova categoria de mídia</a></li>
                            <li><a href="{{ route('midia.create') }}">Nova mídia</a></li>
                            <li><a href="{{ route('medicao.create') }}">Nova medição</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#"><span>Consultas</span> <i class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li><a href="{{ route('dado_biomedico.index') }}">Dados biomédicos</a></li>
                            <li><a href="{{ route('medicao.index') }}">Medições</a></li>
                            <li><a href="{{ route('midia.index') }}">Mídias</a></li>
                            <li><a href="{{ route('medico.index') }}">Médicos</a></li>
                            <li><a href="{{ route('categoria.index') }}">Categorias</a></li>
                            <li><a href="{{ route('especialidade.index') }}">Especialidades</a></li>
                            <li><a href="{{ route('categoria_midia.index') }}">Categorias de mídia</a></li>
                        </ul>
                    </li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav>
            <form method="POST" action="{{ route('logout') }}" class="ms-3">
                @csrf
                <button type="submit" class="appointment-btn border-0 bg-transparent">Sair</button>
            </form>
        </div>
    </header>

    <main id="main">
        <section class="services" style="margin-top: 140px;">
            <div class="container">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </section>
    </main>

    <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>

</html>
