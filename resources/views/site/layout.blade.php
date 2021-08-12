<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>E-civil</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{asset('site/img/favicon.jpg')}}" rel="icon">
  <link href="{{asset('site/img/apple-touch-icon.png')}}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{asset('site/vendor/animate.css/animate.min.css')}}" rel="stylesheet">
  <link href="{{asset('site/vendor/aos/aos.css')}}" rel="stylesheet">
  <link href="{{asset('site/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('site/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
  <link href="{{asset('site/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
  <link href="{{asset('site/vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet">
  <link href="{{asset('site/vendor/remixicon/remixicon.css')}}" rel="stylesheet">
  <link href="{{asset('site/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{asset('site/css/style.css')}}" rel="stylesheet">
</head>
<body>

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top">
    <div class="container d-flex align-items-center justify-content-between">

      <h1 class="logo"><a href="/">E-Civil</a></h1>
      <!-- Uncomment below if you prefer to use an image logo -->
      <!-- <a href="index.html" class="logo"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto active" href="#hero">Accueil</a></li>
          <li><a class="nav-link scrollto" href="#about">Le Maire</a></li>
          <li><a class="nav-link scrollto" href="#news">Actualit&eacute;s</a></li>
          <li><a class="nav-link scrollto" href="#team">Equipe</a></li>
          <li class="dropdown"><a href="#"><span>D&eacute;mande</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
              <li><a href="{{route('demande-extrait-naissance')}}">Extrait de naissance</a></li>
              <li><a href="{{route('demande-extrait-mariage')}}">Certificat de mariage</a></li>
              <li><a href="{{route('demande-extrait-deces')}}">Certificat de d&eacute;c&egrave;s</a></li>
            </ul>
          </li>
          <li><a class="nav-link scrollto" href="#contact">Contact</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->

 @yield('content')
   <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-4 col-md-6">
            <div class="footer-info">
              <h3>E-Civil</h3>
              <p class="pb-3"><em>Meilleur plateforme de gestion des collectivit&eacute;s en Afrique</em></p>
              <p>
                Abidjan - Yamoussoukro <br>
                C&ocirc;te d'Ivoire<br><br>
                <strong>Contact:</strong> (+225) 07 48 365 690<br>
                <strong>E-mail:</strong> info@groupsmarty.com<br>
              </p>
              <div class="social-links mt-3">
                <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
                <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
                <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
                <a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
                <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
              </div>
            </div>
          </div>

          <div class="col-lg-2 col-md-6 footer-links">
            <h4>Liens utils</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="/">Accueil</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Actualit&eacute;s</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Le Maire</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Equipe</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Contact</a></li>
            </ul>
          </div>

          <div class="col-lg-2 col-md-6 footer-links">
            <h4>D&eacute;mandes</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="{{route('demande-extrait-naissance')}}">Extrait de naissance</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="{{route('demande-extrait-mariage')}}">Certificat de mariage</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="{{route('demande-extrait-deces')}}">Certificat de d&eacute;c&egrave;s</a></li>
            </ul>
          </div>

          <div class="col-lg-4 col-md-6 footer-newsletter">
            <h4>Newsletter</h4>
            <p>Connecter vous &agrave; notre Newsletter pour recevoir nos informations </p>
            <form action="#" method="post">
              <input type="email" name="email"><input type="submit" value="Souscrire">
            </form>

          </div>

        </div>
      </div>
    </div>

    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong><span>GroupSmarty</span></strong>. All Rights Reserved
      </div>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/multi-responsive-bootstrap-template/ -->
        Designed by <a href="https://groupsmarty.com/">GroupSmarty</a>
      </div>
    </div>
  </footer><!-- End Footer -->

  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{asset('site/vendor/aos/aos.js')}}"></script>
  <script src="{{asset('site/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('site/vendor/glightbox/js/glightbox.min.js')}}"></script>
  <script src="{{asset('site/vendor/isotope-layout/isotope.pkgd.min.js')}}"></script>
  <script src="{{asset('site/vendor/php-email-form/validate.js')}}"></script>
  <script src="{{asset('site/vendor/purecounter/purecounter.js')}}"></script>
  <script src="{{asset('site/vendor/swiper/swiper-bundle.min.js')}}"></script>
  <!-- Template Main JS File -->
  <script src="{{asset('site/js/main.js')}}"></script>

</body>

</html>