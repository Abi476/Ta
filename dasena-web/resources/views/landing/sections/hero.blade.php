<section id="home" class="hero-section-wrapper-5">

  @include('landing.layouts.navbar')

  <div class="hero-section hero-style-5 img-bg"
    style="background-image: url('{{ asset('assets/user/img/hero/hero-5/hero-bg.svg') }}')">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <div class="hero-content-wrapper">
            <h2 class="mb-30 wow fadeInUp" data-wow-delay=".2s">Damkar Sentimen Analisis</h2>
            <p class="mb-30 wow fadeInUp" data-wow-delay=".4s">Aplikasi web yang mengintegrasikan model Natural Language
              Processing (NLP) untuk klasifikasi sentimen terkait Damkar.</p>
              <a href="{{ route('login') }}" class="button button-lg radius-50 wow fadeInUp" data-wow-delay=".6s">
              Mulai
              <i class="lni lni-chevron-right"></i>
            </a>
          </div>
        </div>
        <div class="col-lg-6 align-self-end">
          <div class="hero-image wow fadeInUp" data-wow-delay=".5s">
            <img src="{{ asset('assets/user/img/hero/hero-5/hero-img.svg') }}" alt="" style="margin-top: -100px;">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>