<nav class="nxl-navigation">
  <div class="navbar-wrapper">
    <div class="m-header">
      <a href="{{ route('dashboard') }}" class="b-brand">
        <img src="{{ asset('assets/user/img/logo/logo.svg') }}" alt="" class="logo logo-lg" />
        <img src="{{ asset('assets/admin/images/logo-abbr.png') }}" alt="" class="logo logo-sm" />
      </a>
    </div>

    <div class="navbar-content">
      <ul class="nxl-navbar">
        <li class="nxl-item nxl-caption">
          <label>ANALISIS & VISUALISASI</label>
        </li>

        <li class="nxl-item">
          <a href="{{ route('dashboard') }}" class="nxl-link">
            <span class="nxl-micon"><i class="feather-layout"></i></span>
            <span class="nxl-mtext">Dashboard</span>
          </a>
        </li>

        <li class="nxl-item">
          <a href="{{ route('hasilanalisis') }}" class="nxl-link">
            <span class="nxl-micon"><i class="feather-bar-chart-2"></i></span>
            <span class="nxl-mtext">Hasil Analisis</span>
          </a>
        </li>

        <li class="nxl-item">
          <a href="{{ route('uji.klasifikasi') }}" class="nxl-link">
            <span class="nxl-micon"><i class="feather-message-square"></i></span>
            <span class="nxl-mtext">Uji Klasifikasi Teks</span>
          </a>
        </li>

        @if(auth()->check() && auth()->user()->role === 'admin')  
          <li class="nxl-item nxl-caption">
          <label>MANAJEMEN & SISTEM</label>
        </li>

          <li class="nxl-item">
            <a href="{{ route('admin.upfile') }}" class="nxl-link">
              <span class="nxl-micon"><i class="feather-upload"></i></span>
              <span class="nxl-mtext">Upload Dataset</span>
            </a>
          </li>

          <li class="nxl-item">
            <a href="{{ route('admin.preprocessing') }}" class="nxl-link">
              <span class="nxl-micon"><i class="feather-tool"></i></span>
              <span class="nxl-mtext">Preprocessing</span>
            </a>
          </li>

          <li class="nxl-item">
            <a href="{{ route('admin.kamus') }}" class="nxl-link">
              <span class="nxl-micon"><i class="feather-book"></i></span>
              <span class="nxl-mtext">Manajemen Kamus</span>
            </a>
          </li>

          <li class="nxl-item">
            <a href="{{ route('admin.users') }}" class="nxl-link">
              <span class="nxl-micon"><i class="feather-users"></i></span>
              <span class="nxl-mtext">Manajemen User</span>
            </a>
          </li>

          <li class="nxl-item">
            <a href="{{ route('admin.messages.index') }}" class="nxl-link">
              <span class="nxl-micon"><i class="feather-inbox"></i></span>
              <span class="nxl-mtext">Pesan Masuk</span>
            </a>
          </li>

        @endif
      </ul>
    </div>
  </div>
</nav>