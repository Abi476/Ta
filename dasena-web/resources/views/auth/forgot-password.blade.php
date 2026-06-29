<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Lupa Password - Dasena</title>
  <link rel="icon" href="{{ asset('assets/admin/images/favicon.ico') }}" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    crossorigin="anonymous">
  <link rel="stylesheet" href="{{ asset('assets/user/css/forgot-password-style.css') }}">
</head>

<body>

  <div class="dasena-layout">

    <div class="floating-banner">
      <img src="{{ asset('assets/user/img/login/logo-banner.png') }}" alt="Logo Instansi">
    </div>

    <div class="panel-left">
      <div class="branding-content">
        <h1 class="branding-title">Dasena</h1>
        <p class="branding-desc">
          Sistem Analisis Sentimen masyarakat untuk layanan Pemadam Kebakaran. Silakan masukkan email Anda untuk mereset
          kata sandi.
        </p>
      </div>
    </div>

    <div class="panel-right">
      <svg class="cloud-divider" viewBox="0 0 150 1000" preserveAspectRatio="none">
        <path fill="rgba(255, 255, 255, 0.2)"
          d="M150,0 L150,1000 L30,1000 Q130,833 30,666 Q130,500 30,333 Q130,166 30,0 Z" />
        <path fill="rgba(255, 255, 255, 0.5)"
          d="M150,0 L150,1000 L60,1000 Q160,833 60,666 Q160,500 60,333 Q160,166 60,0 Z" />
        <path fill="#ffffff" d="M150,0 L150,1000 L90,1000 Q190,833 90,666 Q190,500 90,333 Q190,166 90,0 Z" />
      </svg>

      <div class="form-wrapper">
        <h2 class="form-title">Lupa Password?</h2>
        <p class="form-subtitle">
          Jangan khawatir! Masukkan alamat email yang terdaftar, dan kami akan mengirimkan tautan untuk mereset kata
          sandi Anda.
        </p>

        @if (session('status'))
          <div class="alert alert-success" style="font-size: 0.9rem;">
            {{ session('status') }}
          </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
          @csrf

          <div class="input-group-custom">
            <label>Email <span class="text-danger">*</span></label>
            <input id="email" type="email" name="email" class="input-custom" placeholder="Masukkan email Anda" required
              autofocus>

            @error('email')
              <span class="text-danger mt-1 d-block" style="font-size: 0.8rem;">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-actions">
            <button type="submit" class="btn-reset">Kirim Link Reset</button>
            <a href="{{ route('login') }}" class="text-link">Kembali ke halaman Login</a>
          </div>
        </form>

      </div>

      <div class="footer-text">
        © {{ date('Y') }} Crafted with ❤️ by Lab KSI Politeknik Negeri Jember
      </div>
    </div>

  </div>

</body>

</html>