@extends('layouts.main')

@section('title', 'Profile Details')

@section('page-header')
  <div class="page-header">
    <div class="page-header-left d-flex align-items-center">
      <div class="page-header-title">
        <h5 class="m-b-10">Profile Details</h5>
      </div>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item">Profile</li>
      </ul>
    </div>
  </div>
@endsection

@section('content')
  <div class="row">
    @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
      <div class="col-12 mb-4">
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
          <div class="d-flex align-items-center">
            <i class="feather-check-circle fs-4 me-2"></i>
            <strong>Berhasil!</strong> Perubahan Anda telah disimpan.
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
    @endif

    <div class="col-12 col-xl-6 mb-4">
      <div class="card stretch stretch-full shadow-sm border-0 h-100">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
          <div class="w-100">
            <h5 class="fw-bold mb-1">Informasi Profil</h5>
            <p class="text-muted fs-13 mb-0">Perbarui informasi profil dan alamat email akun Anda.</p>
          </div>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div class="mb-3">
              <label class="form-label fw-semibold">Nama Lengkap</label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                value="{{ old('name', $user->name) }}" required>
              @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
              <label class="form-label fw-semibold">Email</label>
              <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                value="{{ old('email', $user->email) }}" required>
              @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-danger px-4">Simpan Profil</button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-6 mb-4">
      <div class="card stretch stretch-full shadow-sm border-0 h-100">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
          <div class="w-100">
            <h5 class="fw-bold mb-1">Ubah Kata Sandi</h5>
            <p class="text-muted fs-13 mb-0">Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.</p>
          </div>
        </div>
        <div class="card-body">
          
          @if(Auth::user()->google_id)
            <div class="alert alert-info border-0 shadow-sm mt-2">
              <div class="d-flex">
                <i class="feather-info fs-4 me-3 text-info"></i>
                <div>
                  <h6 class="alert-heading fw-bold mb-1">Akun Terhubung dengan Google</h6>
                  <p class="mb-0 fs-13">Anda mendaftar menggunakan akun Google. Jika Anda ingin mengatur kata sandi manual, silakan <em>Logout</em> dan gunakan fitur <strong>Lupa Password</strong> di halaman masuk.</p>
                </div>
              </div>
            </div>
          @else
            <form method="POST" action="{{ route('password.update') }}">
              @csrf
              @method('put')

              <div class="mb-3">
                <label class="form-label fw-semibold">Kata Sandi Saat Ini</label>
                <input type="password"
                  class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                  name="current_password" required>
                @error('current_password', 'updatePassword') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold">Kata Sandi Baru</label>
                <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                  name="password" required>
                @error('password', 'updatePassword') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="mb-4">
                <label class="form-label fw-semibold">Konfirmasi Kata Sandi Baru</label>
                <input type="password" class="form-control" name="password_confirmation" required>
              </div>

              <button type="submit" class="btn btn-dark px-4">Perbarui Sandi</button>
            </form>
          @endif

        </div>
      </div>
    </div>

    <div class="col-12 mb-4">
      <div class="card stretch stretch-full shadow-sm border-0 border-top border-danger border-3">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
          <div class="w-100">
            <h5 class="fw-bold text-danger mb-1">Hapus Akun</h5>
            <p class="text-muted fs-13 mb-0">Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Masukkan kata sandi Anda untuk mengonfirmasi.</p>
          </div>
        </div>
        <div class="card-body">
          
          @if(Auth::user()->google_id)
            <div class="alert alert-warning border-0 shadow-sm mt-2">
              <div class="d-flex">
                <i class="feather-alert-triangle fs-4 me-3 text-warning"></i>
                <div>
                  <h6 class="alert-heading fw-bold mb-1">Akun Terhubung dengan Google</h6>
                  <p class="mb-0 fs-13">Demi keamanan, Anda harus memiliki kata sandi manual untuk menghapus akun. Silakan buat kata sandi terlebih dahulu menggunakan fitur <strong>Lupa Password</strong> di halaman masuk.</p>
                </div>
              </div>
            </div>
          @else
            <form method="POST" action="{{ route('profile.destroy') }}">
              @csrf
              @method('delete')

              <div class="mb-4" style="max-width: 400px;">
                <label class="form-label fw-semibold">Konfirmasi Kata Sandi</label>
                <input type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                  name="password" placeholder="Masukkan kata sandi..." required>
                @error('password', 'userDeletion') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <button type="submit" class="btn btn-danger px-4">Hapus Akun Permanen</button>
            </form>
          @endif

        </div>
      </div>
    </div>
  </div>
@endsection