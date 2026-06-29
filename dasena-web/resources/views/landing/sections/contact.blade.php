<section id="contact" class="contact-section contact-style-3 py-5 bg-light">
  <div class="container">
    <div class="row g-4 align-items-center">

      <div class="col-lg-5">
        <div class="left-wrapper pe-lg-4">

          <span class="sub-title text-danger fw-bold text-uppercase" style="letter-spacing: 1px;">
            Hubungi Kami
          </span>

          <h3 class="fw-bold my-3 text-dark">
            Ada Kritik, Saran, <br>
            atau Pertanyaan Tentang Platform?
          </h3>

          <p class="desc text-muted mb-4">
            Kami siap membantu kamu dalam menggunakan platform
            Analisis Sentimen Damkar dengan cepat dan responsif.
          </p>

          <div class="single-item d-flex align-items-center bg-white p-3 rounded shadow-sm mb-3 border">
            <div class="icon bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
              <i class="lni lni-envelope fs-4"></i>
            </div>
            <div class="text">
              <h5 class="mb-1 fw-bold text-dark fs-6">Email</h5>
              <p class="mb-0 text-muted small">dasena.sistem@gmail.com</p>
            </div>
          </div>

          <div class="single-item d-flex align-items-center bg-white p-3 rounded shadow-sm border">
            <div class="icon bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
              <i class="lni lni-map-marker fs-4"></i>
            </div>
            <div class="text">
              <h5 class="mb-1 fw-bold text-dark fs-6">Lokasi</h5>
              <p class="mb-0 text-muted small">Indonesia</p>
            </div>
          </div>
        </div>
      </div>

    
      <div class="col-lg-7">
        <div class="contact-form-wrapper bg-white p-4 p-md-5 rounded shadow-sm border">
          <h4 class="fw-bold text-dark mb-2">Kirim Pesan</h4>
          <p class="form-desc text-muted mb-4">
            Isi formulir di bawah ini dan kami akan segera menghubungi kamu.
          </p>

          <form action="{{ route('contact.send') }}" method="POST" id="contactForm" novalidate>
            @csrf
            <div class="row g-3 mb-3">
            
              <div class="col-md-6">
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0"><i class="lni lni-user text-muted"></i></span>
                  <input type="text" class="form-control border-start-0 ps-0 bg-light" name="name" id="name" placeholder="Nama Lengkap">
                </div>
              </div>

              <div class="col-md-6">
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0"><i class="lni lni-envelope text-muted"></i></span>
                  <input type="email" class="form-control border-start-0 ps-0 bg-light" name="email" id="email" placeholder="Alamat Email">
                </div>
              </div>
            </div>

            <div class="input-group mb-3">
              <span class="input-group-text bg-light border-end-0"><i class="lni lni-tag text-muted"></i></span>
              <input type="text" class="form-control border-start-0 ps-0 bg-light" name="subject" id="subject" placeholder="Subjek Pesan">
            </div>

            <div class="input-group mb-4">
              <span class="input-group-text bg-light border-end-0 align-items-start pt-2"><i class="lni lni-comments-alt text-muted"></i></span>
              <textarea class="form-control border-start-0 ps-0 bg-light" name="message" id="message" rows="5" placeholder="Tulis pesanmu di sini..."></textarea>
            </div>

            <div class="form-button">
              <button type="submit" class="btn btn-danger w-100 py-2 fw-bold text-uppercase" style="letter-spacing: 1px;">
                Kirim Pesan
              </button>
            </div>

          </form>

        </div>
      </div>

    </div>
  </div>
</section>

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const form = document.getElementById('contactForm');

      if (!form) {
        console.error('Form contactForm tidak ditemukan!');
        return;
      }

      form.addEventListener('submit', function (e) {
        e.preventDefault();

        let name = document.getElementById('name').value.trim();
        let email = document.getElementById('email').value.trim();
        let subject = document.getElementById('subject').value.trim();
        let message = document.getElementById('message').value.trim();

        if (!name || !email || !subject || !message) {
          Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: 'Isi semua bagian form dengan lengkap.',
            confirmButtonColor: '#dc3545'
          });
          return;
        }

        let formData = new FormData(this);
        let submitBtn = this.querySelector('button[type="submit"]');
        let originalText = submitBtn.innerHTML;

        submitBtn.innerHTML = 'Mengirim... <i class="lni lni-spinner lni-spin ms-2"></i>';
        submitBtn.disabled = true;

        fetch(this.action, {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        })
          .then(async response => {
            const data = await response.json();

            if (!response.ok) {
              throw new Error(data.message || 'Terjadi kesalahan pada server.');
            }
            return data;
          })
          .then(data => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;

            if (data.success) {
              Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                confirmButtonColor: '#dc3545'
              });
              form.reset();
            }
          })
          .catch(error => {
            console.error('Error:', error);
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;

            Swal.fire({
              icon: 'error',
              title: 'Gagal',
              text: error.message || 'Terjadi kesalahan saat mengirim pesan.',
              confirmButtonColor: '#dc3545'
            });
          });
      });
    });
  </script>
@endpush