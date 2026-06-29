@extends('layouts.main')

@section('title', 'Manajemen Pengguna')

@section('page-header')
  <div class="page-header">
    <div class="page-header-left d-flex align-items-center">
      <div class="page-header-title">
        <h5 class="m-b-10">Manajemen Pengguna</h5>
      </div>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item">Manajemen Pengguna</li>
      </ul>
    </div>
  </div>
@endsection

@section('content')
  <div class="row">

    <div class="col-md-4">
      <div class="card stretch stretch-full">
        <div class="card-body">
          <div class="d-flex align-items-center gap-3">
            <div class="avatar-text avatar-lg bg-soft-primary text-primary">
              <i class="feather-users"></i>
            </div>
            <div>
              <div class="fs-4 fw-bold text-dark">{{ number_format($total) }}</div>
              <p class="fs-13 text-muted mb-0">Total Pengguna</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card stretch stretch-full">
        <div class="card-body">
          <div class="d-flex align-items-center gap-3">
            <div class="avatar-text avatar-lg bg-soft-danger text-danger">
              <i class="feather-shield"></i>
            </div>
            <div>
              <div class="fs-4 fw-bold text-dark">{{ number_format($totalAdmin) }}</div>
              <p class="fs-13 text-muted mb-0">Administrator</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card stretch stretch-full">
        <div class="card-body">
          <div class="d-flex align-items-center gap-3">
            <div class="avatar-text avatar-lg bg-soft-success text-success">
              <i class="feather-user"></i>
            </div>
            <div>
              <div class="fs-4 fw-bold text-dark">{{ number_format($totalUser) }}</div>
              <p class="fs-13 text-muted mb-0">Pengguna Biasa</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="card stretch stretch-full">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
          <h5 class="card-title mb-0">Daftar Pengguna</h5>
          <form action="{{ route('admin.users') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap">
            {{-- Filter Role --}}
            <select name="role" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
              <option value="">Semua Role</option>
              <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
              <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
            </select>
            {{-- Search --}}
            <div class="input-group" style="max-width: 250px;">
              <span class="input-group-text bg-white border-end-0">
                <i class="feather-search text-muted"></i>
              </span>
              <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nama / email..."
                value="{{ request('search') }}">
            </div>
          </form>
        </div>

        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="bg-light">
                <tr>
                  <th style="width:50px" class="text-center">No</th>
                  <th>Pengguna</th>
                  <th>Metode Login</th>
                  <th>Bergabung</th>
                  <th class="text-center">Role</th>
                  <th class="text-center" style="width:100px">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($users as $index => $user)
                  <tr>
                    <td class="text-center">{{ $users->firstItem() + $index }}</td>
                    <td>
                      <div class="d-flex align-items-center gap-3">
                        <div class="avatar-text avatar-md bg-soft-primary text-primary">
                          {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                          <p class="fw-medium mb-0 fs-13">
                            {{ $user->name }}
                            @if($user->id === auth()->id())
                              <span class="badge bg-soft-primary text-primary fs-10 ms-1">Anda</span>
                            @endif
                          </p>
                          <small class="text-muted">{{ $user->email }}</small>
                        </div>
                      </div>
                    </td>
                    <td>
                      @if($user->google_id)
                        <span class="badge bg-soft-danger text-danger">
                          <i class="feather-globe me-1"></i> Google
                        </span>
                      @else
                        <span class="badge bg-soft-secondary text-secondary">
                          <i class="feather-lock me-1"></i> Password
                        </span>
                      @endif
                    </td>
                    <td>{{ $user->created_at->format('d M Y') }}</td>
                    <td class="text-center">
                      @if($user->role === 'admin')
                        <span class="badge bg-soft-danger text-danger px-3 py-2">
                          <i class="feather-shield me-1"></i> Admin
                        </span>
                      @else
                        <span class="badge bg-soft-success text-success px-3 py-2">
                          <i class="feather-user me-1"></i> User
                        </span>
                      @endif
                    </td>
                    <td class="text-center">
                      <div class="d-flex align-items-center justify-content-center gap-2">
                        {{-- Tombol ubah role --}}
                        @if($user->id !== auth()->id())
                          <button type="button" class="btn btn-sm btn-light btn-ubah-role"
                            style="width:32px; height:32px; padding:0; display:inline-flex; align-items:center; justify-content:center;"
                            data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-role="{{ $user->role }}"
                            data-url="{{ route('admin.users.updateRole', $user->id) }}" title="Ubah Role">
                            <i class="feather-refresh-cw"></i>
                          </button>
                          <button type="button" class="btn btn-sm btn-danger btn-hapus-user"
                            style="width:32px; height:32px; padding:0; display:inline-flex; align-items:center; justify-content:center;"
                            data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                            data-url="{{ route('admin.users.destroy', $user->id) }}" title="Hapus">
                            <i class="feather-trash-2"></i>
                          </button>
                        @else
                          <span class="text-muted fs-11">—</span>
                        @endif
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                      <i class="feather-users fs-1 d-block mb-2"></i>
                      Tidak ada pengguna ditemukan.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <div class="card-footer d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
          <div class="w-100 text-center text-md-start" style="flex: 1;">
            <small class="text-muted">
              Menampilkan {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }}
              dari {{ $users->total() }} pengguna
            </small>
          </div>
          <div class="d-flex justify-content-center" style="flex: 1;">
            {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
          </div>
          <div class="d-none d-md-block" style="flex: 1;"></div>
        </div>

      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
  <script>
    const token = document.querySelector('meta[name="csrf-token"]').content;

    document.addEventListener('click', function (e) {
      const btn = e.target.closest('.btn-ubah-role');
      if (!btn) return;

      const id = btn.getAttribute('data-id');
      const name = btn.getAttribute('data-name');
      const role = btn.getAttribute('data-role');
      const url = btn.getAttribute('data-url');
      const newRole = role === 'admin' ? 'user' : 'admin';

      Swal.fire({
        title: 'Ubah Role Pengguna?',
        html: `Ubah role <b>${name}</b> dari <b>${role}</b> menjadi <b>${newRole}</b>?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Ubah!',
        cancelButtonText: 'Batal'
      }).then(function (result) {
        if (!result.isConfirmed) return;

        Swal.fire({
          title: 'Memproses...',
          allowOutsideClick: false,
          allowEscapeKey: false,
          showConfirmButton: false,
          didOpen: () => Swal.showLoading()
        });

        fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': token,
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: new URLSearchParams({ '_token': token, '_method': 'PATCH', 'role': newRole })
        })
          .then(r => r.json())
          .then(function (data) {
            if (data.success) {
              Swal.fire({
                title: 'Berhasil!', text: data.message, icon: 'success',
                timer: 1500, showConfirmButton: false
              }).then(() => window.location.reload());
            } else {
              Swal.fire('Gagal!', data.message, 'error');
            }
          })
          .catch(err => Swal.fire('Error!', err.message, 'error'));
      });
    });

    document.addEventListener('click', function (e) {
      const btn = e.target.closest('.btn-hapus-user');
      if (!btn) return;

      const name = btn.getAttribute('data-name');
      const url = btn.getAttribute('data-url');
      const row = btn.closest('tr');

      Swal.fire({
        title: 'Hapus Pengguna?',
        html: `Akun <b>${name}</b> akan dihapus secara permanen!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d32f2f',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
      }).then(function (result) {
        if (!result.isConfirmed) return;

        Swal.fire({
          title: 'Menghapus...',
          allowOutsideClick: false,
          allowEscapeKey: false,
          showConfirmButton: false,
          didOpen: () => Swal.showLoading()
        });

        fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': token,
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: new URLSearchParams({ '_token': token, '_method': 'DELETE' })
        })
          .then(r => r.json())
          .then(function (data) {
            if (data.success) {
              row.style.transition = 'opacity 0.4s ease';
              row.style.opacity = '0';
              setTimeout(function () {
                row.remove();
                const tbody = document.querySelector('table tbody');
                if (tbody && tbody.querySelectorAll('tr').length === 0) {
                  tbody.innerHTML = `<tr><td colspan="6" class="text-center py-5 text-muted">
                    <i class="feather-users fs-1 d-block mb-2"></i>Tidak ada pengguna ditemukan.</td></tr>`;
                }
              }, 400);

              Swal.fire({
                title: 'Berhasil!', text: data.message, icon: 'success',
                timer: 1500, showConfirmButton: false
              });
            } else {
              Swal.fire('Gagal!', data.message, 'error');
            }
          })
          .catch(err => Swal.fire('Error!', err.message, 'error'));
      });
    });
  </script>
@endpush