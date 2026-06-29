<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="x-ua-compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="" />
  <meta name="keyword" content="" />
  <meta name="author" content="flexilecode" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'Dashboard') - Dasena</title>

  <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/admin/images/favicon.ico') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/bootstrap.min.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/vendors/css/vendors.min.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/vendors/css/daterangepicker.min.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/theme.min.css') }}" />

  @stack('styles')
</head>

<body>
  @include('layouts.navbar')

  @include('layouts.sidebar')

  <main class="nxl-container d-flex flex-column min-vh-100">
    <div class="nxl-content flex-grow-1">
      @yield('page-header')

      <div class="main-content">
        @yield('content')
      </div>
    </div>

    <footer class="footer sticky-bottom bg-white mt-auto"
      style="z-index: 1020; box-shadow: 0 -2px 10px rgba(0,0,0,0.05);">
      <p class="fs-11 text-muted fw-medium text-uppercase mb-0 copyright">
        <span>Copyright ©</span>
        <script>document.write(new Date().getFullYear());</script>
        <span>Dasena</span>
      </p>
    </footer>
  </main>

  <script src="{{ asset('assets/admin/vendors/js/vendors.min.js') }}"></script>
  <script src="{{ asset('assets/admin/vendors/js/daterangepicker.min.js') }}"></script>
  <script src="{{ asset('assets/admin/vendors/js/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/admin/vendors/js/circle-progress.min.js') }}"></script>
  <script src="{{ asset('assets/admin/js/common-init.min.js') }}"></script>
  <script src="{{ asset('assets/admin/js/theme-customizer-init.min.js') }}"></script>

  @stack('scripts')
</body>

</html>