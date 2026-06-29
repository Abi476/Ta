<!DOCTYPE html>
<html class="no-js" lang="id">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
  <link rel="icon" href="{{ asset('assets/admin/images/favicon.ico') }}" type="image/x-icon">
  <title>@yield('title', 'Dasena - Analisis Sentimen')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ asset('assets/user/css/bootstrap-5.0.0-beta1.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/user/css/LineIcons.2.0.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/user/css/tiny-slider.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/user/css/animate.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/user/css/lindy-uikit.css') }}">
</head>

<body>

  <div class="preloader">
    <div class="loader">
      <div class="spinner">
        <div class="spinner-container">
          <div class="spinner-rotator">
            <div class="spinner-left">
              <div class="spinner-circle"></div>
            </div>
            <div class="spinner-right">
              <div class="spinner-circle"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @yield('content')
  <footer class="footer footer-style-4">
    <div class="container">
      <div class="widget-wrapper">
        <div class="row">
          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="footer-widget wow fadeInUp" data-wow-delay=".2s">
              <div class="logo">
                <a href="#0">
                  <img src="{{ asset('assets/user/img/logo/logo.svg') }}" alt="">
                </a>
              </div>
              <p class="desc">Aplikasi web yang mengintegrasikan model Natural Language Processing (NLP) untuk
                klasifikasi sentimen terkait Damkar.</p>
              <ul class="socials">
                <li> <a href="#0"> <i class="lni lni-facebook-filled"></i> </a> </li>
                <li> <a href="#0"> <i class="lni lni-twitter-filled"></i> </a> </li>
                <li> <a href="#0"> <i class="lni lni-instagram-filled"></i> </a> </li>
                <li> <a href="#0"> <i class="lni lni-linkedin-original"></i> </a> </li>
              </ul>
            </div>
          </div>
          <div class="col-xl-2 offset-xl-1 col-lg-2 col-md-6 col-sm-6">
            <div class="footer-widget wow fadeInUp" data-wow-delay=".3s">
              <h6>Quick Link</h6>
              <ul class="links">
                <li> <a href="#home">Beranda</a> </li>
                <li> <a href="#feature">Fitur</a></li>
                <li> <a href="#about">Tentang</a> </li>
                <li> <a href="#contact">Hubungi Kami</a> </li>
                {{-- <li> <a href="#0">Testimonial</a> </li>
                <li> <a href="#0">Contact</a> </li> --}}
              </ul>
            </div>
          </div>
          <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
            <div class="footer-widget wow fadeInUp" data-wow-delay=".4s">
              <h6>Lokasi</h6>
              <ul class="links">
                <li style="margin-bottom: 8px; line-height: 1.5;"><strong>Politeknik Negeri Jember</strong></li>
                <li style="margin-bottom: 8px; line-height: 1.5;">Jurusan Teknologi Informasi</li>
                <li style="margin-bottom: 8px; line-height: 1.5;">Jl. Mastrip PO. Box 164, Tegalgede</li>
                <li style="margin-bottom: 8px; line-height: 1.5;">Kec. Sumbersari, Jember, Jawa Timur 68121</li>
              </ul>
            </div>
          </div>
        </div>
        <div class="copyright-wrapper wow fadeInUp" data-wow-delay=".2s">
          <p>© 2026 Crafted with ❤️ by Lab KSI Politeknik Negeri Jember</p>
        </div>
      </div>
  </footer>
  <a href="#" class="scroll-top"> <i class="lni lni-chevron-up"></i> </a>
  <script src="{{ asset('assets/user/js/bootstrap-5.0.0-beta1.min.js') }}"></script>
  <script src="{{ asset('assets/user/js/tiny-slider.js') }}"></script>
  <script src="{{ asset('assets/user/js/wow.min.js') }}"></script>
  <script src="{{ asset('assets/user/js/main.js') }}"></script>
  @stack('scripts')
</body>

</html>