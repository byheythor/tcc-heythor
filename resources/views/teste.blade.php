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

        #services {
            margin-top: 175px !important;
        }

        #barra {
            margin: 25px;
        }

        #modal {
            display: none;
            position: fixed;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            position: relative;
            width: 60%;
            margin: 0 auto;
            justify-content: center;
            align-items: center !important;
            display: grid;
            place-items: center;
            height: 100vh;
        }

        .close-button {
            position: absolute;
            top: 10px;
            right: 10px;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
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
                Seja bem-vindo ao seu <br> Gerenciador Pessoal <br> de Dados Biomédicos
            </h1>
            <!--<h2>We are team of talented designers making websites with Bootstrap</h2> -->
            <a href="#services" class="btn-get-started scrollto">Registrar dado</a>
        </div>
    </section>
    <!-- End Hero -->

    <main id="main">
        <!-- ======= Services Section ======= -->
        <section id="services" class="services">
            <div class="container">

                <div class="section-title">
                    <h2>Inserir Dados</h2>
                    <!-- <p>Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit in iste officiis commodi
                        quidem hic quas.</p> -->
                </div>

                <div class="row justify-content-center">

                    <div class="col-lg-4 col-md-6 align-items-stretch mt-4 mt-md-0">
                        <div class="icon-box">
                            <!-- <div class="icon"><i class="fas fa-pills"></i></div> -->
                            <div class="icon"><i class="fas fa-hospital-user"></i></div>
                            <h4><a href="">Registrar novo Médico</a></h4>
                            <p>Registre um novo Médico para o seu dado biomédico</p> <br>
                            <button type="button" class="btn btn-primary border" onclick="window.location.href = '/medico'">Inserir</button>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 align-items-stretch">
                        <div class="icon-box">
                            <!-- <div class="icon"><i class="fas fa-heartbeat"></i></div> -->
                            <div class="icon"><i class="fas fa-hospital-user"></i></div>
                            <h4><a href="">Registrar nova Categoria</a></h4>
                            <p>Registre uma nova categoria para o seu dado biomédico</p> <br>
                            <!-- <button type="button" class="btn btn-primary border" onclick="window.location.href = '/categoria'">Inserir</button> -->
                            <button type="button" class="btn btn-primary border" onclick="openModal()">Inserir</button>
                        </div>
                    </div>

                    <!-- Modal -->

                    <div id="modal" class="modal" style="display: none;">
                        <div class="modal-content d-flex justify-content-center align-items-center">
                            <button id="close-modal" class="close-button btn btn-danger border">Fechar</button>
                            <div class="row justify-content-center">
                                <h4>Qual tipo de categoria você gostaria de registrar?</h4> <br><br>
                                <div class="col-lg-5 col-md-7 align-items-stretch">
                                    <div class="icon-box">
                                        <!-- <div class="icon"><i class="fas fa-heartbeat"></i></div> -->
                                        <div class="icon"><i class="fas fa-hospital-user"></i></div>
                                        <h4><a href="">Categoria</a></h4>
                                        <button type="button" class="btn btn-primary border" onclick="window.location.href = '/categoria'">Inserir</button>
                                    </div>
                                </div>

                                <br>

                                <div class="col-lg-5 col-md-7 align-items-stretch">
                                    <div class="icon-box">
                                        <!-- <div class="icon"><i class="fas fa-heartbeat"></i></div> -->
                                        <div class="icon"><i class="fas fa-hospital-user"></i></div>
                                        <h4><a href="">Categoria de Mídia</a></h4>
                                        <button type="button" class="btn btn-primary border" onclick="window.location.href = '/categoria_midia'">Inserir</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Script -->

                    <script>
                        // Função para abrir o modal
                        function openModal() {
                            document.getElementById("modal").style.display = "block";
                        }

                        // Função para fechar o modal
                        function closeModal() {
                            document.getElementById("modal").style.display = "none";
                        }

                        // Adicione um evento de clique ao botão "Inserir"
                        document.querySelector(".btn-primary").addEventListener("click", openModal);

                        // Adicione um evento de clique ao botão de fechar
                        document.getElementById("close-modal").addEventListener("click", closeModal);
                    </script>

                    <!-- Fim do Modal -->

                    <hr id="barra">

                    <div class="col-lg-4 col-md-6 align-items-stretch mt-4 mt-lg-0">
                        <div class="icon-box">
                            <div class="icon"><i class="fas fa-hospital-user"></i></div>
                            <h4><a href="">Registrar novo Dado Biomédico</a></h4>
                            <p>Registre um dado biomédico para inserir suas medições</p> <br>
                            <button type="button" class="btn btn-primary border" onclick="window.location.href = '/dado_biomedico'">Inserir</button>
                        </div>
                    </div>

                    <hr id="barra">

                    <div class="col-lg-4 col-md-6 align-items-stretch mt-4 mt-md-0">
                        <div class="icon-box">
                            <!-- <div class="icon"><i class="fas fa-pills"></i></div> -->
                            <div class="icon"><i class="fas fa-hospital-user"></i></div>
                            <h4><a href="">Registrar nova Medição</a></h4>
                            <p>Registre uma nova medição para o seu dado biomédico</p> <br>
                            <button type="button" class="btn btn-primary border" onclick="window.location.href = '/medicao'">Inserir</button>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 align-items-stretch">
                        <div class="icon-box">
                            <!-- <div class="icon"><i class="fas fa-heartbeat"></i></div> -->
                            <div class="icon"><i class="fas fa-hospital-user"></i></div>
                            <h4><a href="">Registrar nova Mídia</a></h4>
                            <p>Registre uma nova mídia para o seu dado biomédico</p> <br>
                            <button type="button" class="btn btn-primary border" onclick="window.location.href = '/midia'">Inserir</button>
                        </div>
                    </div>

                    <!-- <div class="col-lg-4 col-md-6 align-items-stretch mt-4">
                        <div class="icon-box">
                            <div class="icon"><i class="fas fa-dna"></i></div>
                            <h4><a href="">Nemo Enim</a></h4>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 align-items-stretch mt-4">
                        <div class="icon-box">
                            <div class="icon"><i class="fas fa-wheelchair"></i></div>
                            <h4><a href="">Dele cardo</a></h4>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 align-items-stretch mt-4">
                        <div class="icon-box">
                            <div class="icon"><i class="fas fa-notes-medical"></i></div>
                            <h4><a href="">Divera don</a></h4>
                        </div>
                    </div> -->

                </div>

            </div>
        </section>
        <!-- End Services Section -->

        <!-- ======= Appointment Section ======= -->
        <section id="appointment" class="appointment section-bg">
            <div class="container">

                <div class="section-title">
                    <h2>Dados Biomédicos</h2>
                    <p>Estes são todos os seus dados biomédicos registrados:</p>
                </div>

                <!-- <form action="forms/appointment.php" method="post" role="form" class="php-email-form">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" data-rule="minlen:4" data-msg="Please enter at least 4 chars">
                            <div class="validate"></div>
                        </div>
                        <div class="col-md-4 form-group mt-3 mt-md-0">
                            <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" data-rule="email" data-msg="Please enter a valid email">
                            <div class="validate"></div>
                        </div>
                        <div class="col-md-4 form-group mt-3 mt-md-0">
                            <input type="tel" class="form-control" name="phone" id="phone" placeholder="Your Phone" data-rule="minlen:4" data-msg="Please enter at least 4 chars">
                            <div class="validate"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group mt-3">
                            <input type="datetime" name="date" class="form-control datepicker" id="date" placeholder="Appointment Date" data-rule="minlen:4" data-msg="Please enter at least 4 chars">
                            <div class="validate"></div>
                        </div>
                        <div class="col-md-4 form-group mt-3">
                            <select name="department" id="department" class="form-select">
                <option value="">Select Department</option>
                <option value="Department 1">Department 1</option>
                <option value="Department 2">Department 2</option>
                <option value="Department 3">Department 3</option>
              </select>
                            <div class="validate"></div>
                        </div>
                        <div class="col-md-4 form-group mt-3">
                            <select name="doctor" id="doctor" class="form-select">
                <option value="">Select Doctor</option>
                <option value="Doctor 1">Doctor 1</option>
                <option value="Doctor 2">Doctor 2</option>
                <option value="Doctor 3">Doctor 3</option>
              </select>
                            <div class="validate"></div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <textarea class="form-control" name="message" rows="5" placeholder="Message (Optional)"></textarea>
                        <div class="validate"></div>
                    </div>
                    <div class="mb-3">
                        <div class="loading">Loading</div>
                        <div class="error-message"></div>
                        <div class="sent-message">Your appointment request has been sent successfully. Thank you!</div>
                    </div>
                    <div class="text-center"><button type="submit">Make an Appointment</button></div>
                </form> -->

            </div>
        </section>
        <!-- End Appointment Section -->
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