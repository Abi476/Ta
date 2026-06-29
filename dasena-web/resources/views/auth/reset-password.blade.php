<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Buat Sandi Baru - Dasena</title>
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
          Sistem Analisis Sentimen masyarakat untuk layanan Pemadam Kebakaran. Silakan buat kata sandi baru untuk akun
          Anda agar dapat kembali mengakses sistem.
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
        <h2 class="form-title">Buat Sandi Baru</h2>
        <p class="form-subtitle">
          Silakan masukkan kata sandi baru Anda di bawah ini. Pastikan kata sandi kuat dan mudah diingat.
        </p>

        <form id="resetForm" method="POST" action="{{ route('password.store') }}">
          @csrf

          <input type="hidden" name="token" value="{{ $request->route('token') }}">

          <div class="input-group-custom">
            <label>Email <span class="text-danger">*</span></label>
            <input id="email" type="email" name="email" class="input-custom" value="{{ old('email', $request->email) }}"
              placeholder="Masukkan email Anda" required readonly>
          </div>

          <div class="input-group-custom">
            <label>Kata Sandi Baru <span class="text-danger">*</span></label>
            <input id="password" type="password" name="password" class="input-custom"
              placeholder="Masukkan kata sandi baru" required autofocus>
          </div>

          <div class="input-group-custom">
            <label>Konfirmasi Kata Sandi <span class="text-danger">*</span></label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="input-custom"
              placeholder="Ulangi kata sandi baru" required>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn-reset">Simpan Sandi Baru</button>
            <a href="{{ route('login') }}" class="text-link">Batal dan kembali ke Login</a>
          </div>
        </form>

      </div>

      <div class="footer-text">
        © {{ date('Y') }} Crafted with ❤️ by Lab KSI Politeknik Negeri Jember
      </div>
    </div>

  </div>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    $(document).ready(function () {
      @if ($errors->any())
        Swal.fire({
          title: "Gagal memproses!",
          text: "{{ $errors->first() }}",
          icon: "error",
          confirmButtonColor: '#d32f2f'
        });
      @endif

      $('#resetForm').on('submit', function (e) {
        e.preventDefault();

        let password = $('#password').val();
        let password_confirmation = $('#password_confirmation').val();

        if (password.length < 8) {
          Swal.fire({
            title: "Peringatan",
            text: "Kata sandi harus terdiri dari minimal 8 karakter.",
            icon: "warning",
            confirmButtonColor: '#d32f2f'
          }).then(() => {
            $('#password').focus();
          });
          return;
        }

        if (password !== password_confirmation) {
          Swal.fire({
            title: "Peringatan",
            text: "Konfirmasi kata sandi tidak cocok. Silakan periksa kembali.",
            icon: "warning",
            confirmButtonColor: '#d32f2f'
          }).then(() => {
            $('#password_confirmation').focus();
          });
          return;
        }

        Swal.fire({
          title: 'Menyimpan...',
          text: 'Sedang memperbarui kata sandi Anda.',
          icon: 'info',
          showConfirmButton: false,
          allowOutsideClick: false,
          allowEscapeKey: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });

        setTimeout(() => {
          this.submit();
        }, 300);
      });

    });
  </script>
</body>

</html>