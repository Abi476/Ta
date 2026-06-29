<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta _token="{{ csrf_token() }}" id="meta_token">
  <title>Login Dasena</title>
  <link rel="icon" href="{{ asset('assets/admin/images/favicon.ico') }}" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="{{ asset('assets/user/css/login-style.css') }}">
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
          Sistem Analisis Sentimen masyarakat untuk layanan Pemadam Kebakaran. Silakan login untuk mengelola dataset,
          dan melihat visualisasi evaluasi kinerja.
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
        <h2 class="form-title" style="text-align: center;">Login</h2>

        <div class="input-group-custom">
          <label>Email<span class="text-danger">*</span></label>
          <input id="email" type="text" class="input-custom" placeholder="Masukkan email">
        </div>

        <div class="input-group-custom mt-4">
          <label>Kata Sandi <span class="text-danger">*</span></label>
          <input id="password" type="password" class="input-custom" placeholder="Masukkan kata sandi">
        </div>

        <div class="form-check mt-3 mb-2" style="font-size: 0.85rem;">
          <input class="form-check-input" type="checkbox" id="showPassword"
            onchange="document.getElementById('password').type = this.checked ? 'text' : 'password'">
          <label class="form-check-label text-muted" for="showPassword">
            Tampilkan Password
          </label>
        </div>

        <div class="form-actions">
          <div class="button-group">
            <button type="button" class="btn-login" onclick="handleLogin()">Login</button>
            <button type="button" class="btn-register" onclick="handleRegister()">Register</button>
          </div>

          <div class="divider-custom">
            <span>Atau</span>
          </div>

          <a href="{{ route('auth.google') }}" class="btn-google">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20px" height="20px">
              <path fill="#FFC107"
                d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z" />
              <path fill="#FF3D00"
                d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z" />
              <path fill="#4CAF50"
                d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z" />
              <path fill="#1976D2"
                d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z" />
            </svg>
            <span>Lanjutkan dengan Google</span>
          </a>
          <a href="{{ route('password.request') }}" class="btn-forgot mt-2">Lupa password?</a>
        </div>
      </div>

      <div class="footer-text">
        © {{ date('Y') }} Crafted with ❤️ by Lab KSI Politeknik Negeri Jember
      </div>

    </div>

  </div>

  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>

  <script>
    $(document).ready(function () {
      $('#email').keypress(function (event) {
        if (event.which == 13) {
          let username = document.getElementById('email');
          let password = document.getElementById('password');

          if (username.value == '') {
            username.focus();
            return;
          }
          if (password.value == '') {
            password.focus();
            return;
          }
          if (username.value != '' && password.value != '') {
            handleLogin();
          }
          event.preventDefault();
        }
      });

      $('#password').keypress(function (event) {
        if (event.which == 13) {
          let username = document.getElementById('email');
          let password = document.getElementById('password');

          if (password.value == '') {
            password.focus();
            return;
          }
          if (username.value == '') {
            username.focus();
            return;
          }
          if (username.value != '' && password.value != '') {
            handleLogin();
          }
          event.preventDefault();
        }
      });
    });

    function handleLogin() {
      let username = document.getElementById('email').value.trim();
      let password = document.getElementById('password').value;
      let token = document.getElementById('meta_token').getAttribute('_token');

      if (username === '' && password === '') {
        Swal.fire({
          title: "Peringatan",
          text: "Email dan Kata Sandi tidak boleh kosong!",
          icon: "warning",
          confirmButtonColor: '#d32f2f'
        });
        return;
      } else if (username === '') {
        Swal.fire({
          title: "Peringatan",
          text: "Email harus diisi!",
          icon: "warning",
          confirmButtonColor: '#d32f2f'
        }).then(() => {
          document.getElementById('email').focus();
        });
        return;
      } else if (password === '') {
        Swal.fire({
          title: "Peringatan",
          text: "Kata sandi harus diisi!",
          icon: "warning",
          confirmButtonColor: '#d32f2f'
        }).then(() => {
          document.getElementById('password').focus();
        });
        return;
      }

      Swal.fire({
        title: 'Status',
        text: 'Sedang melakukan login...',
        icon: 'info',
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });

      $.post('{{ route('login') }}', {
        '_token': token,
        'email': username,
        'password': password
      })
        .done(function (data) {
          if (data.status) {
            Swal.fire({
              title: "Berhasil",
              text: "Login berhasil! Anda akan diarahkan ke dashboard",
              icon: "success",
              showConfirmButton: false,
              timer: 1500
            }).then(() => {
              window.location.replace("{{ route('dashboard') }}");
            });
          } else {
            Swal.fire({
              title: "Login Gagal",
              text: data.msg || "Email atau kata sandi yang Anda masukkan salah.",
              icon: "error",
              confirmButtonColor: '#d32f2f'
            });
          }
        })

        .fail(function (xhr, status, error) {
          let errorMessage = "Terjadi kesalahan pada server. Silakan coba lagi.";

          if (xhr.responseJSON && xhr.responseJSON.message) {
            errorMessage = xhr.responseJSON.message;
          }

          Swal.fire({
            title: "Kesalahan",
            text: errorMessage,
            icon: "error",
            confirmButtonColor: '#d32f2f'
          });
        });
    }

    function handleRegister() {
      window.location.href = "{{ route('register') }}";
    }
  </script>
</body>

</html>