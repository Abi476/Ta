<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Register Dasena</title>
  <link rel="icon" href="{{ asset('assets/admin/images/favicon.ico') }}" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    crossorigin="anonymous">
  <link rel="stylesheet" href="{{ asset('assets/user/css/register-style.css') }}">
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
          Sistem Analisis Sentimen masyarakat untuk layanan Pemadam Kebakaran. Silakan daftar untuk
          mendapatkan akses ke sistem.
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
        <h2 class="form-title">Daftar Akun</h2>

        <form id="registerForm" method="POST" action="{{ route('register') }}">
          @csrf

          <div class="input-group-custom">
            <label>Nama Lengkap <span class="text-danger">*</span></label>
            <input id="name" type="text" name="name" class="input-custom" placeholder="Masukkan nama lengkap" autofocus>
          </div>

          <div class="input-group-custom">
            <label>Email <span class="text-danger">*</span></label>
            <input id="email" type="email" name="email" class="input-custom" placeholder="Masukkan email">
          </div>

          <div class="input-group-custom">
            <label>Kata Sandi <span class="text-danger">*</span></label>
            <input id="password" type="password" name="password" class="input-custom" placeholder="Masukkan kata sandi">
          </div>

          <div class="input-group-custom">
            <label>Konfirmasi Kata Sandi <span class="text-danger">*</span></label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="input-custom"
              placeholder="Ulangi kata sandi">
          </div>

          <div class="form-actions">
            <button type="button" id="btnSubmit" class="btn-register">Register</button>
            <a href="{{ route('login') }}" class="text-link">Sudah punya akun? Login di sini</a>
          </div>
        </form>

      </div>

      <div class="footer-text">
        © {{ date('Y') }} Crafted with ❤️ by Lab KSI Politeknik Negeri Jember
      </div>
    </div>

  </div>

  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    $(document).ready(function () {
      $('#btnSubmit').click(function () {
        let name = $('#name').val().trim();
        let email = $('#email').val().trim();
        let password = $('#password').val();
        let password_confirmation = $('#password_confirmation').val();
        let token = $('input[name="_token"]').val(); // Mengambil token CSRF Laravel

        if (name === '' || email === '' || password === '' || password_confirmation === '') {
          Swal.fire({
            title: "Peringatan",
            text: "Mohon maaf, semua kolom pendaftaran wajib diisi.",
            icon: "warning",
            confirmButtonColor: '#d32f2f'
          });
          return;
        }

        let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
          Swal.fire({
            title: "Peringatan",
            text: "Format email yang Anda masukkan tidak valid.",
            icon: "warning",
            confirmButtonColor: '#d32f2f'
          }).then(() => {
            $('#email').focus();
          });
          return;
        }

        if (password !== password_confirmation) {
          Swal.fire({
            title: "Peringatan",
            text: "Konfirmasi kata sandi tidak cocok. Silakan periksa kembali.",
            icon: "error",
            confirmButtonColor: '#d32f2f'
          }).then(() => {
            $('#password_confirmation').focus();
          });
          return;
        }

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

        Swal.fire({
          title: 'Memproses...',
          text: 'Mohon tunggu sebentar, sistem sedang mendaftarkan akun Anda.',
          icon: 'info',
          showConfirmButton: false,
          allowOutsideClick: false,
          allowEscapeKey: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });

        $.post('{{ route('register') }}', {
          '_token': token,
          'name': name,
          'email': email,
          'password': password,
          'password_confirmation': password_confirmation
        })

          .done(function (data) {
            Swal.fire({
              title: "Pendaftaran Berhasil!",
              text: "Selamat datang di Dasena. Anda akan segera diarahkan ke Dashboard.",
              icon: "success",
              showConfirmButton: false,
              timer: 2500 // Notifikasi tampil selama 2.5 detik agar sempat dibaca
            }).then(() => {
              window.location.replace("{{ route('dashboard') }}");
            });
          })

          .fail(function (xhr, status, error) {
            let errorMessage = "Mohon maaf, terjadi kesalahan pada sistem kami. Silakan coba lagi nanti.";

            if (xhr.responseJSON && xhr.responseJSON.errors) {
              let firstErrorKey = Object.keys(xhr.responseJSON.errors)[0];
              errorMessage = xhr.responseJSON.errors[firstErrorKey][0];
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
              errorMessage = xhr.responseJSON.message;
            }

            Swal.fire({
              title: "Pendaftaran Gagal",
              text: errorMessage,
              icon: "error",
              confirmButtonColor: '#d32f2f'
            });
          });
      });

      $('.input-custom').keypress(function (e) {
        if (e.which == 13) {
          $('#btnSubmit').click();
          return false;
        }
      });
    });
  </script>
</body>

</html>