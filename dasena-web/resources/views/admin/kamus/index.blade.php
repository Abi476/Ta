@extends('layouts.main')

@section('title', 'Kamus Normalisasi (Kata Slang)')

@section('page-header')
  <div class="page-header">
    <div class="page-header-left d-flex align-items-center">
      <div class="page-header-title">
        <h5 class="m-b-10">Kamus Normalisasi (Slang / Singkatan)</h5>
      </div>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item">Kamus Normalisasi</li>
      </ul>
    </div>
    <div class="page-header-right ms-auto d-flex align-items-center gap-2 flex-nowrap">
      <a href="{{ route('admin.kamus.template') }}"
        class="btn btn-outline-secondary d-flex align-items-center gap-2 text-nowrap">
        <i class="feather-download"></i> Unduh Template
      </a>
      <button class="btn btn-outline-success d-flex align-items-center gap-2 text-nowrap" data-bs-toggle="modal"
        data-bs-target="#importKamusModal">
        <i class="feather-upload"></i> Import CSV/Excel
      </button>
      <button class="btn btn-outline-primary d-flex align-items-center gap-2 text-nowrap" data-bs-toggle="modal"
        data-bs-target="#tambahKataModal">
        <i class="feather-plus"></i> Tambah Kata Baru
      </button>
    </div>
  </div>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card stretch stretch-full">

        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
          <h5 class="card-title mb-0">Daftar Kamus Normalisasi</h5>
          <form action="{{ route('admin.kamus') }}" method="GET" style="max-width: 280px; width: 100%;">
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0">
                <i class="feather-search text-muted"></i>
              </span>
              <input type="text" name="search" class="form-control border-start-0 ps-0"
                placeholder="Cari kata tidak baku..." value="{{ request('search') }}">
            </div>
          </form>
        </div>

        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="bg-light">
                <tr>
                  <th style="width: 50px;" class="text-center">No</th>
                  <th>Kata Tidak Baku (Slang/Singkatan)</th>
                  <th>Menjadi <i class="feather-arrow-right mx-2 text-muted"></i> Kata Baku</th>
                  <th>Tanggal Ditambahkan</th>
                  <th class="text-center" style="width: 100px;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($kamus as $index => $item)
                  <tr>
                    <td class="text-center">{{ $kamus->firstItem() + $index }}</td>
                    <td>
                      <span class="badge bg-soft-danger text-danger fs-14 py-2 px-3">
                        {{ $item->kata_tidak_baku }}
                      </span>
                    </td>
                    <td>
                      <span class="badge bg-soft-success text-success fs-14 py-2 px-3">
                        {{ $item->kata_baku }}
                      </span>
                    </td>
                    <td>{{ $item->created_at->format('d M Y') }}</td>
                    <td class="text-center">
                      <div class="d-flex align-items-center justify-content-center gap-2">
                        <button type="button" class="btn btn-sm btn-light"
                          style="width:32px; height:32px; padding:0; display:inline-flex; align-items:center; justify-content:center;"
                          data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}" title="Edit">
                          <i class="feather-edit-2"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger btn-hapus"
                          style="width:32px; height:32px; padding:0; display:inline-flex; align-items:center; justify-content:center;"
                          data-url="{{ route('admin.kamus.destroy', $item->id) }}" title="Hapus">
                          <i class="feather-trash-2"></i>
                        </button>
                      </div>
                    </td>
                  </tr>

                  <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                        <form action="{{ route('admin.kamus.update', $item->id) }}" method="POST">
                          @csrf @method('PUT')
                          <div class="modal-header">
                            <h5 class="modal-title fw-bold">Edit Kamus Normalisasi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>
                          <div class="modal-body text-start">
                            <div class="mb-3">
                              <label class="form-label fw-bold">Kata Tidak Baku</label>
                              <input type="text" name="kata_tidak_baku" class="form-control"
                                value="{{ $item->kata_tidak_baku }}" required>
                            </div>
                            <div class="text-center mb-3 text-muted">
                              <i class="feather-arrow-down fs-4"></i>
                            </div>
                            <div class="mb-3">
                              <label class="form-label fw-bold">Kata Baku</label>
                              <input type="text" name="kata_baku" class="form-control" value="{{ $item->kata_baku }}"
                                required>
                            </div>
                          </div>
                          <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                              <i class="feather-save"></i> Perbarui
                            </button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>

                @empty
                  <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                      <i class="feather-database fs-1 d-block mb-2"></i>
                      Belum ada data kamus normalisasi.
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
              Menampilkan {{ $kamus->firstItem() ?? 0 }}–{{ $kamus->lastItem() ?? 0 }}
              dari {{ $kamus->total() }} kata
            </small>
          </div>
          <div class="d-flex justify-content-center" style="flex: 1;">
            {{ $kamus->appends(request()->query())->links('pagination::bootstrap-5') }}
          </div>
          <div class="d-none d-md-block" style="flex: 1;"></div>
        </div>

      </div>
    </div>
  </div>

  <div class="modal fade" id="tambahKataModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form action="{{ route('admin.kamus.store') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title fw-bold">Tambah Kamus Normalisasi</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
              <i class="feather-info fs-4 me-2"></i>
              <div>Berfungsi untuk memperbaiki kata gaul/singkatan agar dikenali oleh AI.</div>
            </div>
            <div class="mb-3">
              <label class="form-label fw-bold">Kata Tidak Baku (Singkatan / Slang)</label>
              <input type="text" name="kata_tidak_baku" class="form-control" placeholder="Contoh: dmkr, tdk, gblg"
                required>
            </div>
            <div class="text-center mb-3 text-muted">
              <i class="feather-arrow-down fs-4"></i>
            </div>
            <div class="mb-3">
              <label class="form-label fw-bold">Kata Baku (Perbaikan)</label>
              <input type="text" name="kata_baku" class="form-control" placeholder="Contoh: damkar, tidak, bodoh"
                required>
            </div>
          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
              <i class="feather-save"></i> Simpan Kata
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="importKamusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold">Import Kamus dari File</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-info d-flex gap-2 mb-4">
            <i class="feather-info fs-5 mt-1 flex-shrink-0"></i>
            <div>
              <p class="mb-1 fw-medium">Format file: <b>CSV, XLSX, XLS</b> — Maks. 5 MB</p>
              <p class="mb-0">Kolom wajib berurutan:<br>
                <b>Kolom A:</b> kata_tidak_baku &nbsp;|&nbsp; <b>Kolom B:</b> kata_baku
              </p>
            </div>
          </div>

          <div class="border border-dashed border-2 border-primary rounded-3 text-center p-4 mb-3"
            style="background-color: #f8f9fa; cursor: pointer;"
            onclick="document.getElementById('fileImportKamus').click()">
            <div id="importEmptyState">
              <i class="feather-upload-cloud fs-1 text-primary mb-2 d-block"></i>
              <p class="fw-medium mb-1">Klik untuk pilih file</p>
              <p class="text-muted small mb-0">CSV, XLSX, XLS — Maks. 5 MB</p>
            </div>
            <div id="importFilledState" class="d-none">
              <i class="feather-file-text fs-1 text-success mb-2 d-block"></i>
              <p class="fw-medium mb-1" id="importFileName">-</p>
              <p class="text-muted small mb-0" id="importFileSize">-</p>
            </div>
            <input type="file" id="fileImportKamus" class="d-none" accept=".csv,.xlsx,.xls">
          </div>

          <a href="{{ route('admin.kamus.template') }}"
            class="btn btn-light btn-sm w-100 d-flex align-items-center justify-content-center gap-2">
            <i class="feather-download"></i> Unduh Template CSV
          </a>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-primary d-flex align-items-center gap-2" id="btnProsesImport" disabled>
            <i class="feather-upload"></i> Mulai Import
          </button>
        </div>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
  <script>

    document.addEventListener('DOMContentLoaded', function () {
      document.querySelectorAll('.modal').forEach(function (modal) {
        document.body.appendChild(modal);
      });

      @if(session('success'))
        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: "{{ session('success') }}",
          showConfirmButton: false,
          timer: 2000
        });
      @endif
          });

    // ===== HAPUS =====
    document.addEventListener('click', function (e) {
      const btn = e.target.closest('.btn-hapus');
      if (!btn) return;
      konfirmasiHapusKamus(btn.getAttribute('data-url'), btn.closest('tr'));
    });

    function konfirmasiHapusKamus(actionUrl, row) {
      const token = document.querySelector('meta[name="csrf-token"]').content;

      Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: 'Data kata ini akan dihapus dari kamus secara permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d32f2f',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
      }).then(function (result) {
        if (!result.isConfirmed) return;

        Swal.fire({
          title: 'Sedang Menghapus...',
          text: 'Mohon tunggu sebentar...',
          allowOutsideClick: false,
          allowEscapeKey: false,
          showConfirmButton: false,
          didOpen: () => Swal.showLoading()
        });

        fetch(actionUrl, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': token
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
                  tbody.innerHTML = `<tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                          <i class="feather-database fs-1 d-block mb-2"></i>
                          Belum ada data kamus normalisasi yang ditambahkan.
                        </td>
                      </tr>`;
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
          .catch(err => Swal.fire('Gagal!', 'Error: ' + err.message, 'error'));
      });
    }

    const fileImportInput = document.getElementById('fileImportKamus');
    const btnImport = document.getElementById('btnProsesImport');

    fileImportInput.addEventListener('change', function () {
      const file = this.files[0];
      if (!file) return;

      document.getElementById('importEmptyState').classList.add('d-none');
      document.getElementById('importFilledState').classList.remove('d-none');
      document.getElementById('importFileName').innerText = file.name;

      let size = file.size / 1024;
      let unit = 'KB';
      if (size > 1024) { size = size / 1024; unit = 'MB'; }
      document.getElementById('importFileSize').innerText = size.toFixed(2) + ' ' + unit;

      btnImport.disabled = false;
    });

    btnImport.addEventListener('click', function () {
      const file = fileImportInput.files[0];
      const token = document.querySelector('meta[name="csrf-token"]').content;
      if (!file) return;

      bootstrap.Modal.getInstance(document.getElementById('importKamusModal')).hide();

      Swal.fire({
        title: 'Sedang Mengimpor Data...',
        text: 'Mohon tunggu, sistem sedang memproses file...',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => Swal.showLoading()
      });

      const formData = new FormData();
      formData.append('_token', token);
      formData.append('file_import', file);

      fetch('{{ route('admin.kamus.import') }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': token },
        body: formData
      })
        .then(r => r.json())
        .then(function (data) {
          if (data.success) {
            Swal.fire({
              title: 'Import Berhasil!',
              html: `<p>${data.message}</p>
                    <div class="d-flex justify-content-center gap-4 mt-3">
                      <div class="text-center">
                        <div class="fs-3 fw-bold text-success">${data.stats.berhasil}</div>
                        <small class="text-muted">Ditambahkan</small>
                      </div>
                      <div class="text-center">
                        <div class="fs-3 fw-bold text-warning">${data.stats.duplikat}</div>
                        <small class="text-muted">Duplikat</small>
                      </div>
                      <div class="text-center">
                        <div class="fs-3 fw-bold text-secondary">${data.stats.kosong}</div>
                        <small class="text-muted">Baris Kosong</small>
                      </div>
                    </div>`,
              icon: 'success',
              confirmButtonColor: '#3085d6',
              confirmButtonText: 'Lihat Data'
            }).then(() => window.location.reload());
          } else {
            Swal.fire('Gagal!', data.message, 'error');
          }
        })
        .catch(err => Swal.fire('Gagal!', 'Error: ' + err.message, 'error'));
    });
  </script>
@endpush