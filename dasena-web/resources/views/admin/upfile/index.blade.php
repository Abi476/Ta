@extends('layouts.main')

@section('title', 'Upload Dataset')

@section('page-header')
  <div class="page-header">
    <div class="page-header-left d-flex align-items-center">
      <div class="page-header-title">
        <h5 class="m-b-10">Upload Dataset</h5>
      </div>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item">Upload File</li>
      </ul>
    </div>
  </div>
@endsection

@section('content')
  <div class="row">
    <div class="col-xxl-4 col-lg-5">
      <div class="card stretch stretch-full">
        <div class="card-header">
          <h5 class="card-title">Panduan Unggah Data</h5>
        </div>
        <div class="card-body">
          <div class="alert alert-primary mb-4" role="alert">
            <div class="d-flex gap-2 align-items-center">
              <i class="feather-info fs-5"></i>
              <span>Pastikan format file sesuai dengan ketentuan sistem Dasena.</span>
            </div>
          </div>

          <h6 class="fw-bold mb-2">Ketentuan File:</h6>
          <ul class="list-unstyled mb-4">
            <li class="mb-2 d-flex align-items-center gap-2 text-muted">
              <i class="feather-check-circle text-success"></i> Format: <b>.CSV, .XLSX, .XLS</b>
            </li>
            <li class="mb-2 d-flex align-items-center gap-2 text-muted">
              <i class="feather-check-circle text-success"></i> Maksimal: <b>10 MB</b>
            </li>
            <li class="mb-2 text-muted">
              <i class="feather-check-circle text-success me-1"></i> Kolom Berurutan: <br>
              <span class="ms-4"><b>Keyword, Tanggal, Teks</b></span>
            </li>
          </ul>

          <a href="{{ route('admin.upfile.template') }}"
            class="btn btn-light w-100 d-flex align-items-center justify-content-center gap-2">
            <i class="feather-download"></i> Unduh Template Excel
          </a>
        </div>
      </div>
    </div>

    <div class="col-xxl-8 col-lg-7">
      <div class="card stretch stretch-full">
        <div class="card-header">
          <h5 class="card-title">Form Unggah Dataset</h5>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.upfile.process') }}" method="POST" enctype="multipart/form-data" id="formUpload">
            @csrf
            <div class="border border-dashed border-2 border-primary rounded-3 text-center p-5 mb-4"
              style="background-color: #f8f9fa;">

              <input type="file" class="form-control d-none" id="fileUpload" name="file_dataset"
                accept=".csv, .xlsx, .xls" required>

              <div id="uploadEmptyState">
                <div class="avatar-text avatar-xl bg-soft-primary text-primary mx-auto mb-3">
                  <i class="feather-upload-cloud fs-2"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Pilih File atau Seret ke Sini</h5>
                <p class="text-muted mb-4">Mendukung file Excel dan CSV.</p>
                <label for="fileUpload" class="btn btn-primary m-0" style="cursor: pointer;">
                  <i class="feather-folder-plus me-2"></i> Telusuri File
                </label>
              </div>

              <div id="uploadFilledState" class="d-none">
                <div class="avatar-text avatar-xl bg-soft-success text-success mx-auto mb-3">
                  <i class="feather-file-text fs-2"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1" id="displayFileName">nama_file.csv</h5>
                <p class="text-muted mb-4" id="displayFileSize">0 KB</p>
                <label for="fileUpload" class="btn btn-outline-secondary m-0" style="cursor: pointer;">
                  <i class="feather-refresh-cw me-2"></i> Ganti File
                </label>
              </div>

            </div>

            <div class="mb-4">
              <label for="batchName" class="form-label fw-medium">Nama Batch / Keterangan (Opsional)</label>
              <input type="text" class="form-control" id="batchName" name="batch_name"
                placeholder="Contoh: Data Komentar Damkar Januari 2025">
            </div>

            {{-- PERBAIKAN TOMBOL --}}
            <div class="d-flex flex-column align-items-end gap-2">
              <button type="submit" class="btn btn-success w-100 w-md-auto text-center" style="max-width: 250px;">
                <i class="feather-upload me-2"></i> Mulai Unggah & Proses
              </button>
              <button type="reset" class="btn btn-light w-100 w-md-auto text-center" style="max-width: 250px;"
                onclick="resetUploadState()">
                Batal
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-12">
      <div class="card stretch stretch-full">
        <div class="card-header">
          <h5 class="card-title">Riwayat Unggahan Dataset</h5>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="bg-light">
                <tr>
                  <th>Nama File</th>
                  <th>Keterangan</th>
                  <th>Tanggal Unggah</th>
                  <th>Total Baris</th>
                  <th>Status</th>
                  <th class="text-end">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($datasets as $data)
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <i class="feather-file-text text-success fs-5"></i>
                        <span class="fw-medium">{{ $data->file_name }}</span>
                      </div>
                    </td>
                    <td>{{ $data->batch_name ?? '-' }}</td>
                    <td>{{ $data->created_at->format('d M Y, H:i') }}</td>
                    <td>{{ number_format($data->total_rows, 0, ',', '.') }} baris</td>
                    <td>
                      @if($data->status == 'Pending')
                        <span class="badge bg-soft-warning text-warning">Pending</span>
                      @elseif($data->status == 'Processing')
                        <span class="badge bg-soft-info text-info">Memproses</span>
                      @else
                        <span class="badge bg-soft-success text-success">Selesai Diproses</span>
                      @endif
                    </td>
                    <td class="text-end">
                      <button type="button" class="btn btn-sm btn-danger btn-hapus"
                        data-url="{{ route('admin.upfile.destroy', $data->id) }}"
                        style="width:32px; height:32px; padding:0; display:inline-flex; align-items:center; justify-content:center;"
                        title="Hapus">
                        <i class="feather-trash-2"></i>
                      </button>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center text-muted py-4">Belum ada riwayat unggahan dataset.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
  <script>
    document.getElementById('fileUpload').addEventListener('change', function (e) {
      const file = e.target.files[0];
      if (!file) return;
      document.getElementById('uploadEmptyState').classList.add('d-none');
      document.getElementById('uploadFilledState').classList.remove('d-none');
      document.getElementById('displayFileName').innerText = file.name;
      let size = file.size / 1024;
      let unit = 'KB';
      if (size > 1024) { size = size / 1024; unit = 'MB'; }
      document.getElementById('displayFileSize').innerText = size.toFixed(2) + ' ' + unit;
    });

    function resetUploadState() {
      document.getElementById('uploadEmptyState').classList.remove('d-none');
      document.getElementById('uploadFilledState').classList.add('d-none');
    }

    document.addEventListener('click', function (e) {
      const btn = e.target.closest('.btn-hapus');
      if (!btn) return;
      const actionUrl = btn.getAttribute('data-url');
      const row = btn.closest('tr');
      konfirmasiHapus(actionUrl, row);
    });

    function konfirmasiHapus(actionUrl, row) {
      const token = document.querySelector('meta[name="csrf-token"]').content;

      Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: 'Seluruh baris komentar dari file ini akan ikut terhapus permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d32f2f',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
      }).then(function (result) {
        if (!result.isConfirmed) return;

        Swal.fire({
          title: 'Sedang Menghapus Data...',
          text: 'Mohon tunggu, sistem sedang membersihkan data...',
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
                  tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">Belum ada riwayat unggahan dataset.</td></tr>';
                }
              }, 400);

              Swal.fire({
                title: 'Berhasil!',
                text: data.message,
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
              });
            } else {
              Swal.fire('Gagal!', data.message, 'error');
            }
          })
          .catch(function (err) {
            Swal.fire('Gagal!', 'Error: ' + err.message, 'error');
          });
      });
    }

    document.addEventListener('DOMContentLoaded', function () {
      @if(session('success'))
        Swal.fire({ title: "Berhasil!", text: "{!! session('success') !!}", icon: "success", confirmButtonColor: '#3085d6' });
      @endif
      @if(session('error'))
        Swal.fire({ title: "Gagal!", text: "{!! session('error') !!}", icon: "error", confirmButtonColor: '#d32f2f' });
      @endif

      document.getElementById('formUpload').addEventListener('submit', function () {
        Swal.fire({
          title: 'Memproses Excel/CSV...',
          text: 'Mohon tunggu, sistem sedang membaca baris data.',
          icon: 'info',
          allowOutsideClick: false,
          allowEscapeKey: false,
          showConfirmButton: false,
          didOpen: () => Swal.showLoading()
        });
      });
    });
  </script>
@endpush