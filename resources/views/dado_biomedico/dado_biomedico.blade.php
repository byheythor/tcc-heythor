<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Gerenciador Pessoal de Dados Biomédicos</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ url ('/assets/img/favicon.png')}}" rel="icon">
    <link href="{{ url ('/assets/img/apple-touch-icon.png')}}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ url ('/assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
    <link href="{{ url ('/assets/vendor/animate.css')}}/animate.min.css')}}" rel="stylesheet">
    <link href="{{ url ('/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ url ('/assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
    <link href="{{ url ('/assets/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
    <link href="{{ url ('/assets/vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet">
    <link href="{{ url ('/assets/vendor/remixicon/remixicon.css')}}" rel="stylesheet">
    <link href="{{ url ('/assets/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ url ('/assets/css/style.css')}}" rel="stylesheet">

    <!-- =======================================================
  * Template Name: Medilab
  * Updated: Sep 18 2023 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/medilab-free-medical-bootstrap-theme/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
    <style>
        #person-circle {
            margin-right: 2px;
        }

        #appointment {
            margin-top: 175px !important;
        }

        #barra {
            margin: 25px;
        }

        .custom-btn {
            margin: 20px;
            width: 130px;
            height: 40px;
            color: #fff;
            border-radius: 5px;
            padding: 10px 25px;
            font-family: 'Lato', sans-serif;
            font-weight: 500;
            background: transparent;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            display: inline-block;
            box-shadow:inset 2px 2px 2px 0px rgba(255,255,255,.5),
            7px 7px 20px 0px rgba(0,0,0,.1),
            4px 4px 5px 0px rgba(0,0,0,.1);
            outline: none;
        }

        .btn-3 {
        background: rgb(0,172,238);
        background: linear-gradient(0deg, rgba(0,172,238,1) 0%, rgba(2,126,251,1) 100%);
        width: 150px;
        height: 40px;
        line-height: 42px;
        padding: 0;
        border: none;
        
        }
        .btn-3 span {
        position: relative;
        display: block;
        width: 100%;
        height: 100%;
        }
        .btn-3:before,
        .btn-3:after {
        position: absolute;
        content: "";
        right: 0;
        top: 0;
        background: rgba(2,126,251,1);
        transition: all 0.3s ease;
        }
        .btn-3:before {
        height: 0%;
        width: 2px;
        }
        .btn-3:after {
        width: 0%;
        height: 2px;
        }
        .btn-3:hover{
        background: transparent;
        box-shadow: none;
        }
        .btn-3:hover:before {
        height: 100%;
        }
        .btn-3:hover:after {
        width: 100%;
        }
        .btn-3 span:hover{
        color: rgba(2,126,251,1);
        }
        .btn-3 span:before,
        .btn-3 span:after {
        position: absolute;
        content: "";
        left: 0;
        bottom: 0;
        background: rgba(2,126,251,1);
        transition: all 0.3s ease;
        }
        .btn-3 span:before {
        width: 2px;
        height: 0%;
        }
        .btn-3 span:after {
        width: 0%;
        height: 2px;
        }
        .btn-3 span:hover:before {
        height: 100%;
        }
        .btn-3 span:hover:after {
        width: 100%;
        }
    </style>
</head>

<body>

    <!-- ======= Top Bar ======= -->
    <div id="topbar" class="d-flex align-items-center fixed-top">
        <div class="container d-flex justify-content-between">
            <div class="contact-info d-flex align-items-center">
                <i class="bi bi-person"></i> Heythor Daniel Rodrigues de Assis
                <i class="bi bi-envelope"></i> <a href="heythordaniel0@gmail.com">heythordaniel0@gmail.com</a>
            </div>
            <div class="d-none d-lg-flex social-links align-items-center">
                <a href="https://www.instagram.com/heythor_daniel/" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="https://www.linkedin.com/in/heythor-daniel-rodrigues-de-assis" class="linkedin"><i class="bi bi-linkedin"></i></i></a>
            </div>
        </div>
    </div>

    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top">
        <div class="container d-flex align-items-center">

            <h1 class="logo me-auto">
                <a href="index.html">
                    Gerenciador Pessoal <br>
                    de Dados Biomédicos
                </a>
            </h1>
            <!-- Uncomment below if you prefer to use an image logo -->
            <!-- <a href="index.html" class="logo me-auto"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->

            <nav id="navbar" class="navbar order-last order-lg-0">
                <ul>
                    <li><a class="nav-link scrollto active" href="#hero">Inicio</a></li>
                    <li><a class="nav-link scrollto" href="#services">Inserir dados</a></li>
                    <!-- <li><a class="nav-link scrollto" href="#about">About</a></li>
                    <li><a class="nav-link scrollto" href="#departments">Departments</a></li>
                    <li><a class="nav-link scrollto" href="#doctors">Doctors</a></li>
                    <li class="dropdown"><a href="#"><span>Drop Down</span> <i class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li><a href="#">Drop Down 1</a></li>
                            <li class="dropdown"><a href="#"><span>Deep Drop Down</span> <i class="bi bi-chevron-right"></i></a>
                                <ul>
                                    <li><a href="#">Deep Drop Down 1</a></li>
                                    <li><a href="#">Deep Drop Down 2</a></li>
                                    <li><a href="#">Deep Drop Down 3</a></li>
                                    <li><a href="#">Deep Drop Down 4</a></li>
                                    <li><a href="#">Deep Drop Down 5</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Drop Down 2</a></li>
                            <li><a href="#">Drop Down 3</a></li>
                            <li><a href="#">Drop Down 4</a></li>
                        </ul>
                    </li>
                    <li><a class="nav-link scrollto" href="#contact">Contact</a></li> -->
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav>
            <!-- .navbar -->

            <a href="#appointment" class="appointment-btn scrollto"><span class="d-none d-md-inline">
                    <i id="person-circle" class="bi bi-person-circle"></i> Perfil
                </span></a>

        </div>
    </header>
    <!-- End Header -->

    <!-- ======= Hero Section ======= -->
    <section id="hero" class="d-flex align-items-center">
        <div class="container">
            <h1>
                Gerenciador Pessoal <br> de Dados Biomédicos
            </h1>
            <!--<h2>We are team of talented designers making websites with Bootstrap</h2> -->
            <a href="#appointment" class="btn-get-started scrollto">Registrar dado</a>
        </div>
    </section>
    <!-- End Hero -->

    <main id="main">
        <!-- ======= Appointment Section ======= -->
        <section id="appointment" class="appointment section-bg">
            <div class="container">

                <div class="section-title">
                    <h2>Registrar Dado Biomédico</h2>
                    <p>Insira um novo dado biomédico:</p>
                </div>

                <form method="post" role="form">
                @csrf
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <input type="text" name="name" class="form-control" id="nome" placeholder="Nome do dado biomédico" data-rule="minlen:4" data-msg="Please enter at least 4 chars">
                            <div class="validate"></div>
                        </div>
                        <div class="col-md-4 form-group">
                            <input type="text" name="name" class="form-control" id="valor_referencia" placeholder="Valor Referencial" data-rule="minlen:4" data-msg="Please enter at least 4 chars">
                            <div class="validate"></div>
                        </div>
                        <div class="col-md-4 form-group">
                            <input type="text" name="name" class="form-control" id="unidade_medida" placeholder="Unidade de Medida" data-rule="minlen:4" data-msg="Please enter at least 4 chars">
                            <div class="validate"></div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-4 form-group mt-3">
                            <select name="department" id="department" class="form-select">
                                <option value="">Selecione o Médico</option>
                                <option value="Department 1">Médico 1</option>
                                <option value="Department 2">Médico 2</option>
                                <option value="Department 3">Médico 3</option>
                            </select>
                            <div class="validate"></div>
                        </div>
                        <div class="col-md-4 form-group mt-3">
                            <select name="doctor" id="doctor" class="form-select">
                                <option value="">Selecione a categoria do dado biomédico</option>
                                <option value="Doctor 1">Categoria 1</option>
                                <option value="Doctor 2">Categoria 2</option>
                                <option value="Doctor 3">Categoria 3</option>
                            </select>
                            <div class="validate"></div>
                        </div>
                    </div>

                    <!-- <div class="mb-3">
                        <div class="loading">Loading</div>
                        <div class="error-message"></div>
                        <div class="sent-message">Your appointment request has been sent successfully. Thank you!</div>
                    </div> -->
                    <div class="text-center">
                        <!-- <button type="submit">Registrar</button> -->
                        <button class="custom-btn btn-3"><span>Registrar</span></button>
                    </div>
                </form>

            </div>
        </section>
        <!-- End Appointment Section -->

        <!-- ======= Services Section ======= -->
        <section id="services" class="services">
            <div class="container">
                <div class="section-title">
                    <h2>Dados Biomédicos Registrados</h2>
                    <p>Estes são todos os dados biomédicos registrados:</p>
                </div>
            </div>
        </section>
        <!-- End Services Section -->
    </main>
    <!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer">

        <div class="footer-top">

        </div>

        <div class="container d-md-flex py-4">

            <div class="me-md-auto text-center text-md-start">
                <div class="copyright">
                    &copy; Copyright <strong><span>Heythor</span></strong>. Todos os direitos reservados.
                </div>
                <div class="credits">
                    <!-- All the links in the footer should remain intact. -->
                    <!-- You can delete the links only if you purchased the pro version. -->
                    <!-- Licensing information: https://bootstrapmade.com/license/ -->
                    <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/medilab-free-medical-bootstrap-theme/ -->
                    <!--
                      Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
                    -->
                </div>
            </div>
            <div class="social-links text-center text-md-right pt-3 pt-md-0">
                <a href="https://www.instagram.com/heythor_daniel/" class="instagram"><i class="bx bxl-instagram"></i></a>
                <a href="https://www.linkedin.com/in/heythor-daniel-rodrigues-de-assis" class="linkedin"><i class="bx bxl-linkedin"></i></a>
            </div>
        </div>
    </footer>
    <!-- End Footer -->

    <div id="preloader"></div>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ url ('/assets/vendor/purecounter/purecounter_vanilla.js')}}"></script>
    <script src="{{ url ('/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ url ('/assets/vendor/glightbox/js/glightbox.min.js')}}"></script>
    <script src="{{ url ('/assets/vendor/swiper/swiper-bundle.min.js')}}"></script>
    <script src="{{ url ('/assets/vendor/php-email-form/validate.js')}}"></script>

    <!-- Template Main JS File -->
    <script src="{{ url ('/assets/js/main.js')}}"></script>

</body>

</html>