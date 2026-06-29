<header class="nxl-header">

  <style>
    /* Efek stabilo kuning saat teks ditemukan */
    .highlight-search {
      background-color: #ffeb3b;
      color: #000;
      font-weight: bold;
      padding: 0 2px;
      border-radius: 3px;
      transition: background-color 0.3s ease;
    }
  </style>

  <div class="header-wrapper">
    <div class="header-left d-flex align-items-center gap-4">
      <a href="javascript:void(0);" class="nxl-head-mobile-toggler" id="mobile-collapse">
        <div class="hamburger hamburger--arrowturn">
          <div class="hamburger-box">
            <div class="hamburger-inner"></div>
          </div>
        </div>
      </a>

      <div class="nxl-navigation-toggle">
        <a href="javascript:void(0);" id="menu-mini-button">
          <i class="feather-align-left"></i>
        </a>
        <a href="javascript:void(0);" id="menu-expend-button" style="display: none">
          <i class="feather-arrow-right"></i>
        </a>
      </div>
    </div>

    <div class="header-right ms-auto">
      <div class="d-flex align-items-center">

        <div class="dropdown nxl-h-item nxl-header-search">
          <a href="javascript:void(0);" class="nxl-head-link me-0" data-bs-toggle="dropdown"
            data-bs-auto-close="outside">
            <i class="feather-search"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-search-dropdown">
            <div class="input-group search-form">
              <span class="input-group-text">
                <i class="feather-search fs-6 text-muted"></i>
              </span>
              <input type="text" id="searchInput" class="form-control search-input-field"
                placeholder="Search on page...." autocomplete="off" />
              <span class="input-group-text">
                <button type="button" class="btn-close" onclick="clearSearch()"></button>
              </span>
            </div>
          </div>
        </div>

        <div class="nxl-h-item d-none d-sm-flex">
          <div class="full-screen-switcher">
            <a href="javascript:void(0);" class="nxl-head-link me-0" onclick="$('body').fullScreenHelper('toggle');">
              <i class="feather-maximize maximize"></i>
              <i class="feather-minimize minimize"></i>
            </a>
          </div>
        </div>

        <div class="nxl-h-item dark-light-theme">
          <a href="javascript:void(0);" class="nxl-head-link me-0 dark-button">
            <i class="feather-moon"></i>
          </a>
          <a href="javascript:void(0);" class="nxl-head-link me-0 light-button" style="display: none">
            <i class="feather-sun"></i>
          </a>
        </div>

        <div class="dropdown nxl-h-item ms-3">
          <a href="javascript:void(0);" data-bs-toggle="dropdown" role="button" data-bs-auto-close="outside">
            <div
              class="d-flex align-items-center justify-content-center bg-primary text-white rounded-circle user-avtar me-0"
              style="width: 38px; height: 38px;">
              <i class="feather-user fs-5"></i>
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-user-dropdown">
            <div class="dropdown-header">
              <div class="d-flex align-items-center">
                <div
                  class="d-flex align-items-center justify-content-center bg-primary text-white rounded-circle user-avtar me-3"
                  style="width: 45px; height: 45px;">
                  <i class="feather-user fs-4"></i>
                </div>
                <div>
                  <h6 class="text-dark mb-0">{{ Auth::user()->name ?? 'Admin' }}</h6>
                  <span class="fs-12 fw-medium text-muted">{{ Auth::user()->email ?? 'admin@example.com' }}</span>
                </div>
              </div>
            </div>
            <div class="dropdown-divider"></div>
            <a href="{{ route('profile.edit') }}" class="dropdown-item">
              <i class="feather-user"></i>
              <span>Profile Details</span>
            </a>

            <div class="dropdown-divider"></div>
            <a href="{{ route('logout') }}" class="dropdown-item"
              onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="feather-log-out"></i>
              <span>Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>

<script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/mark.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {

    // fitur search
    const searchInput = document.getElementById('searchInput');
    const contentArea = document.querySelector('.main-content');

    if (contentArea && searchInput) {
      const markInstance = new Mark(contentArea);

      searchInput.addEventListener('input', function () {
        const keyword = this.value;
        markInstance.unmark({
          done: function () {
            if (keyword.length > 0) {
              markInstance.mark(keyword, {
                className: 'highlight-search',
                separateWordSearch: false,
                accuracy: "partially",
                done: function (totalMatches) {
                  if (totalMatches > 0) {
                    const firstMatch = document.querySelector('.highlight-search');
                    if (firstMatch) {
                      firstMatch.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                  }
                }
              });
            }
          }
        });
      });
    }

    // thema
    const darkBtn = document.querySelector('.dark-button');
    const lightBtn = document.querySelector('.light-button');
    const htmlEl = document.documentElement;
    const bodyEl = document.body;

    function applyTheme(theme) {
      if (theme === 'dark') {
        htmlEl.setAttribute('data-bs-theme', 'dark');
        bodyEl.classList.add('dark-mode');
        bodyEl.setAttribute('data-theme', 'dark');
        if (darkBtn) darkBtn.style.display = 'none';
        if (lightBtn) lightBtn.style.display = 'flex';
        localStorage.setItem('dasena_theme', 'dark');
      } else {

        htmlEl.setAttribute('data-bs-theme', 'light');
        bodyEl.classList.remove('dark-mode');
        bodyEl.setAttribute('data-theme', 'light');
        if (darkBtn) darkBtn.style.display = 'flex';
        if (lightBtn) lightBtn.style.display = 'none';
        localStorage.setItem('dasena_theme', 'light');
      }
    }

    const savedTheme = localStorage.getItem('dasena_theme') || 'light';
    applyTheme(savedTheme);

    if (darkBtn) {
      darkBtn.addEventListener('click', function () { applyTheme('dark'); });
    }
    if (lightBtn) {
      lightBtn.addEventListener('click', function () { applyTheme('light'); });
    }

  });

  function clearSearch() {
    const searchInput = document.getElementById('searchInput');
    const contentArea = document.querySelector('.main-content');
    if (contentArea && searchInput) {
      const markInstance = new Mark(contentArea);
      searchInput.value = '';
      markInstance.unmark();
    }
  }
</script>