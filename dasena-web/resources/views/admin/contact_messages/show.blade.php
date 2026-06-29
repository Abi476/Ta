@extends('layouts.main')

@section('title', 'Detail Pesan')

@section('page-header')
  <div class="page-header">
    <div class="page-header-left d-flex align-items-center">
      <div class="page-header-title">
        <h5 class="m-b-10">Detail Pesan</h5>
      </div>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.messages.index') }}">Pesan Masuk</a></li>
        <li class="breadcrumb-item">Detail</li>
      </ul>
    </div>
  </div>
@endsection

@section('content')
  <div class="row">
    <div class="col-lg-7 mb-4 mb-lg-0">
      <div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0 d-flex align-items-center">
            <i class="feather-mail text-primary me-2"></i> Isi Pesan
          </h5>
          <span
            class="badge {{ $message->is_replied ? 'bg-success' : 'bg-warning' }} rounded-pill px-3 py-2 fs-12 d-inline-flex align-items-center gap-1">
            <i class="feather-{{ $message->is_replied ? 'check-circle' : 'clock' }}"></i>
            {{ $message->is_replied ? 'Sudah Dibalas' : 'Belum Dibalas' }}
          </span>
        </div>

        <div class="card-body">
          <div class="row mb-4">
            <div class="col-md-7 mb-3 mb-md-0">
              <h6 class="text-muted mb-1 d-flex align-items-center fs-12 text-uppercase fw-bolder">
                <i class="feather-user me-2"></i>Pengirim
              </h6>
              <p class="fw-bold mb-0 fs-14 text-dark">{{ $message->name }}</p>
              <a href="mailto:{{ $message->email }}"
                class="text-primary text-decoration-none fs-13">{{ $message->email }}</a>
            </div>
            <div class="col-md-5">
              <h6 class="text-muted mb-1 d-flex align-items-center fs-12 text-uppercase fw-bolder">
                <i class="feather-calendar me-2"></i>Tanggal Masuk
              </h6>
              <p class="fw-bold mb-0 fs-14 text-dark">{{ $message->created_at->format('d F Y') }}</p>
              <span class="text-muted fs-13">{{ $message->created_at->format('H:i') }} WIB</span>
            </div>
          </div>

          <hr class="border-dashed mb-4">

          <div class="mb-4">
            <h6 class="text-muted mb-2 d-flex align-items-center fs-12 text-uppercase fw-bolder">
              <i class="feather-tag me-2"></i>Subjek
            </h6>
            <h5 class="fw-bolder text-dark mb-0">{{ $message->subject }}</h5>
          </div>

          <div>
            <h6 class="text-muted mb-2 d-flex align-items-center fs-12 text-uppercase fw-bolder">
              <i class="feather-message-square me-2"></i>Pesan Warga
            </h6>
            <div class="p-4 bg-light rounded border border-secondary-subtle"
              style="white-space: pre-line; font-size: 14px; line-height: 1.7; color: #333;">{{ $message->message }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="card h-100">
        <div class="card-header">
          <h5 class="card-title mb-0 d-flex align-items-center">
            <i class="feather-corner-up-left text-primary me-2"></i> Tindak Lanjut Balasan
          </h5>
        </div>
        <div class="card-body">

          <form id="form-reply" action="{{ route('admin.messages.reply', $message->id) }}" method="POST">
            @csrf
            <div class="mb-4">
              <label class="form-label fw-bold d-flex align-items-center">
                <i class="feather-edit-3 me-2 text-muted"></i>Isi Pesan Balasan Email
              </label>
              <textarea name="reply_text" class="form-control bg-light" rows="10"
                placeholder="Tulis pesan balasan Anda di sini. Pesan ini akan langsung dikirim ke alamat email warga: {{ $message->email }}..."
                required style="resize: none; line-height: 1.6;">{{ old('reply_text', $message->reply_text) }}</textarea>
              <small class="form-text text-muted mt-2 d-flex align-items-start">
                <i class="feather-info me-1 mt-1 text-primary"></i>
                <span>Balasan ini akan dikirim melalui SMTP server sistem ke email tujuan dan riwayatnya akan tersimpan di
                  database.</span>
              </small>
            </div>

            <button type="submit"
              class="btn btn-{{ $message->is_replied ? 'primary' : 'success' }} w-100 d-flex justify-content-center align-items-center gap-2 py-2">
              <i class="feather-{{ $message->is_replied ? 'refresh-cw' : 'send' }}"></i>
              {{ $message->is_replied ? 'Kirim Ulang Email Balasan' : 'Kirim Email Balasan' }}
            </button>
          </form>

        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {

      document.getElementById('form-reply').addEventListener('submit', function (e) {
        e.preventDefault();
        let form = this;

        Swal.fire({
          title: 'Kirim Email Balasan?',
          text: "Pesan ini akan langsung diteruskan ke alamat email warga yang bersangkutan.",
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, Kirim Sekarang!',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {

            Swal.fire({
              title: 'Mengirim Email...',
              text: 'Mohon tunggu sebentar, sistem sedang menghubungi server email.',
              allowOutsideClick: false,
              allowEscapeKey: false,
              showConfirmButton: false,
              didOpen: () => {
                Swal.showLoading();
              }
            });

            form.submit();
          }
        });
      });

      @if(session('success'))
        Swal.fire({
          icon: 'success',
          title: 'Berhasil Terkirim!',
          text: '{{ session('success') }}',
          confirmButtonColor: '#3085d6'
        });
      @endif

      @if(session('error'))
        Swal.fire({
          icon: 'error',
          title: 'Gagal Terkirim!',
          text: '{{ session('error') }}',
          confirmButtonColor: '#d33'
        });
      @endif

    });
  </script>
@endpush