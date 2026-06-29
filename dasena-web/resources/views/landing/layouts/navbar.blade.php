<header class="header header-6">
  <div class="navbar-area">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-12">
          <nav class="navbar navbar-expand-lg">
            <a class="navbar-brand" href="{{ route('home') }}">
              <img src="{{ asset('assets/user/img/logo/logo.svg') }}" alt="Logo" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
              data-bs-target="#navbarSupportedContent6" aria-controls="navbarSupportedContent6" aria-expanded="false"
              aria-label="Toggle navigation">
              <span class="toggler-icon"></span>
              <span class="toggler-icon"></span>
              <span class="toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent6">
              <ul id="nav6" class="navbar-nav ms-auto">
                <li class="nav-item">
                  <a class="page-scroll active" href="#home">Beranda</a>
                </li>
                <li class="nav-item">
                  <a class="page-scroll" href="#feature">Fitur</a>
                </li>
                <li class="nav-item">
                  <a class="page-scroll" href="#about">Tentang</a>
                </li>
                {{-- <li class="nav-item">
                  <a class="page-scroll" href="#pricing">Pricing</a>
                </li> --}}
                <li class="nav-item">
                  <a class="page-scroll" href="#contact">Hubungi Kami</a>
                </li>
                <li class="nav-item">
                  @auth
                    <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                  @else
                    <a href="{{ route('login') }}" class="nav-link">Login</a>
                  @endauth
                </li>
              </ul>
            </div>
          </nav>
        </div>
      </div>
    </div>
  </div>
</header>