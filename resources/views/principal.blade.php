<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Gerenciador Pessoal de Dados Biomédicos</title>

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

    <style>
        #services {
            margin-top: 175px !important;
        }

        #barra {
            margin: 25px;
        }

        .panel-card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .panel-card p {
            flex-grow: 1;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <div id="topbar" class="d-flex align-items-center fixed-top">
        <div class="container d-flex justify-content-between">
            <div class="contact-info d-flex align-items-center">
                <i class="bi bi-person"></i>
                {{ auth()->user()->nome ?? auth()->user()->name }}
                <i class="bi bi-envelope ms-3"></i>
                {{ auth()->user()->email }}
            </div>
            <div class="d-none d-lg-flex social-links align-items-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger fw-semibold">
                        <i class="bi bi-box-arrow-right me-1"></i>Sair
                    </button>
                </form>
            </div>
        </div>
    </div>

    <header id="header" class="fixed-top">
        <div class="container d-flex align-items-center">
            <h1 class="logo me-auto">
                <a href="{{ route('principal') }}">
                    Gerenciador Pessoal <br>
                    de Dados Biomédicos
                </a>
            </h1>

            <nav id="navbar" class="navbar order-last order-lg-0">
                <ul>
                    <li><a class="nav-link scrollto active" href="#hero">Início</a></li>
                    <li><a class="nav-link" href="{{ route('profile.show') }}">Meu perfil</a></li>
                    <li class="dropdown"><a href="#"><span>Cadastros</span> <i class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li><a class="scrollto" href="#services">Atalhos da página inicial</a></li>
                            <li><a href="{{ route('especialidade.create') }}">Nova especialidade</a></li>
                            <li><a href="{{ route('medico.create') }}">Novo médico</a></li>
                            <li><a href="{{ route('categoria.create') }}">Nova categoria</a></li>
                            <li><a href="{{ route('dado_biomedico.create') }}">Novo dado biomédico</a></li>
                            <li><a href="{{ route('categoria_midia.create') }}">Nova categoria de mídia</a></li>
                            <li><a href="{{ route('midia.create') }}">Nova mídia</a></li>
                            <li><a href="{{ route('medicao.create') }}">Nova medição (appointment)</a></li>
                        </ul>
                    </li>
                    <li class="dropdown"><a href="#"><span>Consultas</span> <i class="bi bi-chevron-down"></i></a>
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

            <a href="#appointment" class="appointment-btn scrollto"><span class="d-none d-md-inline">Make an Appointment</span></a>
        </div>
    </header>

    <section id="hero" class="d-flex align-items-center">
        <div class="container">
            <h1>
                Seja bem-vindo ao seu <br> Gerenciador Pessoal <br> de Dados Biomédicos
            </h1>
            <a href="#services" class="btn-get-started scrollto">Inserir dados</a>
        </div>
    </section>

    <main id="main">
        <section id="services" class="services">
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

                <div class="section-title">
                    <h2>Inserir Dados</h2>
                    <p>Use os atalhos abaixo para começar os cadastros.</p>
                </div>

                <h5 class="mb-3">Cadastros essenciais</h5>
                <div class="row g-4 mb-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="icon-box panel-card">
                            <div class="icon"><i class="fas fa-stethoscope"></i></div>
                            <h4><a href="{{ route('especialidade.create') }}">Registrar nova Especialidade</a></h4>
                            <p>Cadastre especialidades que serão usadas no cadastro dos médicos.</p>
                            <a href="{{ route('especialidade.create') }}" class="btn btn-primary">Inserir</a>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="icon-box panel-card">
                            <div class="icon"><i class="fas fa-hospital-user"></i></div>
                            <h4><a href="{{ route('medico.create') }}">Registrar novo Médico</a></h4>
                            <p>Registre médicos para associar aos dados biomédicos.</p>
                            <a href="{{ route('medico.create') }}" class="btn btn-primary">Inserir</a>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="icon-box panel-card">
                            <div class="icon"><i class="fas fa-list"></i></div>
                            <h4><a href="{{ route('categoria.create') }}">Registrar nova Categoria</a></h4>
                            <p>Registre categorias para facilitar a organização e filtros.</p>
                            <a href="{{ route('categoria.create') }}" class="btn btn-primary">Inserir</a>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="icon-box panel-card">
                            <div class="icon"><i class="fas fa-heartbeat"></i></div>
                            <h4><a href="{{ route('dado_biomedico.create') }}">Registrar novo Dado Biomédico</a></h4>
                            <p>Registre o dado biomédico para iniciar o histórico de medições.</p>
                            <a href="{{ route('dado_biomedico.create') }}" class="btn btn-primary">Inserir</a>
                        </div>
                    </div>
                </div>

                <h5 class="mb-3">Cadastros complementares</h5>
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="icon-box panel-card">
                            <div class="icon"><i class="fas fa-photo-video"></i></div>
                            <h4><a href="{{ route('categoria_midia.create') }}">Registrar Categoria de Mídia</a></h4>
                            <p>Cadastre categorias para organizar anexos e exames.</p>
                            <a href="{{ route('categoria_midia.create') }}" class="btn btn-primary">Inserir</a>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="icon-box panel-card">
                            <div class="icon"><i class="fas fa-file-medical"></i></div>
                            <h4><a href="{{ route('midia.create') }}">Registrar nova Mídia</a></h4>
                            <p>Faça upload de imagens e PDFs vinculados aos dados biomédicos.</p>
                            <a href="{{ route('midia.create') }}" class="btn btn-primary">Inserir</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="appointment" class="appointment section-bg">
            <div class="container">
                <div class="section-title">
                    <h2>Make an Appointment</h2>
                    <p>Registre uma nova medição para um dado biomédico já cadastrado.</p>
                </div>

                <form action="{{ route('medicao.store') }}" method="post" class="row g-3 align-items-end">
                    @csrf
                    <div class="col-lg-3 col-md-6 form-group">
                        <label for="nome" class="form-label">Nome da medição</label>
                        <input type="text" name="nome" class="form-control" id="nome" placeholder="Nome da medição" value="{{ old('nome') }}" required>
                    </div>
                    <div class="col-lg-3 col-md-6 form-group">
                        <label for="valor" class="form-label">Valor</label>
                        <input type="text" class="form-control" name="valor" id="valor" placeholder="Valor" value="{{ old('valor') }}" required>
                    </div>
                    <div class="col-lg-3 col-md-6 form-group">
                        <label for="data_hora" class="form-label">Data e hora</label>
                        <input type="datetime-local" class="form-control" name="data_hora" id="data_hora" value="{{ old('data_hora') }}" required>
                    </div>

                    <div class="col-lg-3 col-md-6 form-group">
                        <label for="dado_biomedico_id" class="form-label">Dado biomédico</label>
                        <select name="dado_biomedico_id" id="dado_biomedico_id" class="form-select" required>
                            <option value="">Selecione o dado biomédico</option>
                            @foreach ($dadosBiomedicos as $dado)
                                <option value="{{ $dado->id }}" @selected(old('dado_biomedico_id') == $dado->id)>{{ $dado->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 text-center mt-3">
                        <button type="submit" class="btn btn-primary" {{ $dadosBiomedicos->isEmpty() ? 'disabled' : '' }}>Registrar medição</button>
                        @if ($dadosBiomedicos->isEmpty())
                            <p class="small text-danger mt-2 mb-0">
                                Cadastre ao menos um dado biomédico antes de registrar medição.
                            </p>
                        @endif
                    </div>
                </form>

                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Últimas medições</h5>
                            <a href="{{ route('medicao.index') }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Valor</th>
                                        <th>Data/Hora</th>
                                        <th>Dado biomédico</th>
                                        <th class="text-end" style="width: 180px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($medicoesRecentes as $medicao)
                                        <tr>
                                            <td>{{ $medicao->nome }}</td>
                                            <td>{{ $medicao->valor }}</td>
                                            <td>{{ optional($medicao->data_hora)->format('d/m/Y H:i') }}</td>
                                            <td>{{ optional($medicao->dado_biomedico)->nome ?: '-' }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('medicao.edit', $medicao->id) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                                <a href="{{ route('medicao.delete', $medicao->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Confirma exclusão?')">Excluir</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">Nenhuma medição registrada ainda.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $medicoesRecentes->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>

</html>
