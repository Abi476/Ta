@extends('layouts.main')

@section('title', 'Hasil Analisis Sentimen')

@section('page-header')
  <div class="page-header">
    <div class="page-header-left d-flex align-items-center">
      <div class="page-header-title">
        <h5 class="m-b-10">Hasil Analisis Sentimen</h5>
      </div>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item">Hasil Analisis</li>
      </ul>
    </div>
    <div class="page-header-right ms-auto d-flex gap-2">
      @if(auth()->check() && auth()->user()->role === 'admin')
        <button type="button" class="btn btn-outline-primary d-flex align-items-center gap-2" id="btn-prediksi">
          <i class="feather-cpu"></i> <span id="text-prediksi">Klasifikasi Sentimen</span>
        </button>
        <a href="{{ route('hasilanalisis.export') }}" class="btn btn-outline-success d-flex align-items-center gap-2">
          <i class="feather-download"></i> Export File
        </a>
      @endif
    </div>
  </div>
@endsection

@section('content')
  <div class="row">
    <div class="col-12 mb-4">
      <div class="card">
        <div class="card-body">
          <form action="{{ route('hasilanalisis') }}" method="GET" class="m-0">
            <div class="row g-2 align-items-center">

              <div class="col-12 col-md-3">
                <select name="sentimen" class="form-select">
                  <option value="" {{ request('sentimen') == '' ? 'selected' : '' }} hidden disabled>Pilih Sentimen...
                  </option>
                  <option value="">Semua Sentimen</option>
                  <option value="Positif" {{ request('sentimen') == 'Positif' ? 'selected' : '' }}>Positif</option>
                  <option value="Negatif" {{ request('sentimen') == 'Negatif' ? 'selected' : '' }}>Negatif</option>
                  <option value="Netral" {{ request('sentimen') == 'Netral' ? 'selected' : '' }}>Netral</option>
                </select>
              </div>

              <div class="col-12 col-md-3">
                <select name="bulan" class="form-select">
                  <option value="" {{ request('bulan') == '' ? 'selected' : '' }} hidden disabled>Pilih Bulan...</option>
                  <option value="">Semua Bulan</option>
                  @foreach($bulanTersedia as $b)
                    <option value="{{ $b }}" {{ request('bulan') == $b ? 'selected' : '' }}>
                      {{ $namaBulan[$b] }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="col-12 col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari kata kunci komentar..."
                  value="{{ request('search') }}">
              </div>

              <div class="col-12 col-md-2 text-md-end mt-2 mt-md-0">
                <button type="submit"
                  class="btn btn-primary w-100 d-flex justify-content-center align-items-center gap-1">
                  <i class="feather-filter"></i> Terapkan
                </button>
              </div>

            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="card stretch stretch-full">
        <div class="card-body p-0">
          <div class="table-responsive">
            <!-- PERBAIKAN: Menambahkan min-width: 1000px agar tabel bisa di-scroll horizontal di HP -->
            <table class="table table-hover mb-0 align-middle" style="min-width: 1000px; width: 100%;">
              <thead class="bg-light">
                <tr>
                  <th style="width: 15%; white-space: nowrap;">Tanggal</th>
                  <th style="width: 35%;">Komentar Asli</th>
                  <th style="width: 30%;">Teks Bersih (Hasil Preprocessing)</th>
                  <th style="width: 12%; white-space: nowrap;" class="text-center align-middle">Sentimen</th>
                  <th style="width: 8%; white-space: nowrap;" class="text-center align-middle">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($items as $item)
                  <tr>
                    <td class="align-middle" style="white-space: nowrap;">
                      {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d M Y') : '-' }}
                    </td>
                    
                    <!-- PERBAIKAN: Menghapus tag HTML <td class="align-mid yang terpotong/error sebelumnya -->
                    <td class="text-wrap text-break align-middle" style="white-space: normal; min-width: 250px;">
                      {{ $item->teks }}
                    </td>
                    
                    <td class="text-wrap text-break align-middle" style="white-space: normal; min-width: 250px;">
                      <span class="text-muted fs-13">{{ $item->teks_stemmed }}</span>
                    </td>

                    <td class="text-center align-middle">
                      @if($item->sentimen == 'Positif')
                        <span class="badge bg-soft-success text-success">Positif</span>
                      @elseif($item->sentimen == 'Negatif')
                        <span class="badge bg-soft-danger text-danger">Negatif</span>
                      @elseif($item->sentimen == 'Netral')
                        <span class="badge bg-soft-warning text-warning">Netral</span>
                      @else
                        <span class="badge bg-soft-secondary text-secondary">Belum Dianalisis</span>
                      @endif
                    </td>

                    <td class="text-center align-middle">
                      <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal"
                        data-bs-target="#modalDetail{{ $item->id }}" title="Lihat Detail">
                        <i class="feather-search"></i>
                      </button>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                      <i class="feather-inbox fs-2 mb-2 d-block"></i>
                      Belum ada data yang selesai di-preprocessing atau sesuai filter.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <div class="card-footer d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 border-top mt-2">
          <div class="w-100 text-center text-md-start" style="flex: 1;">
            <small class="text-muted">
              Menampilkan {{ $items->firstItem() ?? 0 }}–{{ $items->lastItem() ?? 0 }}
              dari {{ $items->total() }} komentar
            </small>
          </div>
          <div class="d-flex justify-content-center" style="flex: 1;">
            {{ $items->appends(request()->query())->links('pagination::bootstrap-5') }}
          </div>
          <div class="d-none d-md-block" style="flex: 1;"></div>
        </div>
      </div>
    </div>
  </div>

  @foreach($items as $item)
    <div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $item->id }}"
      aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title fw-bold" id="modalLabel{{ $item->id }}">Detail Analisis Komentar</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">

            <div class="mb-3">
              <label class="fw-bold text-dark">Tanggal Masuk:</label>
              <p class="text-muted m-0">{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d F Y') : '-' }}</p>
            </div>

            <div class="mb-3">
              <label class="fw-bold text-dark">Komentar Asli:</label>
              <div class="p-3 bg-light rounded text-wrap" style="word-break: break-word;">
                {{ $item->teks }}
              </div>
            </div>

            <div class="mb-3">
              <label class="fw-bold text-dark">Teks Bersih (Hasil Preprocessing):</label>
              <div class="p-3 bg-light rounded text-wrap" style="word-break: break-word;">
                {{ $item->teks_stemmed ?? 'Belum ada hasil preprocessing' }}
              </div>
            </div>

            <div class="mb-2">
              <label class="fw-bold text-dark d-block mb-2">Hasil Prediksi Sentimen (Naive Bayes):</label>
              @if($item->sentimen == 'Positif')
                <span class="badge bg-soft-success text-success fs-14 py-2 px-3">Sentimen Positif</span>
              @elseif($item->sentimen == 'Negatif')
                <span class="badge bg-soft-danger text-danger fs-14 py-2 px-3">Sentimen Negatif</span>
              @elseif($item->sentimen == 'Netral')
                <span class="badge bg-soft-warning text-warning fs-14 py-2 px-3">Sentimen Netral</span>
              @else
                <span class="badge bg-soft-secondary text-secondary fs-14 py-2 px-3">Belum Dianalisis AI</span>
              @endif
            </div>

          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>
  @endforeach
@endsection

@push('scripts')
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      document.querySelectorAll('.modal').forEach(function (modal) {
        document.body.appendChild(modal);
      });
    });

    document.getElementById('btn-prediksi').addEventListener('click', function () {
      let btn = this;
      btn.blur();
      let originalContent = btn.innerHTML;
      btn.disabled = true;

      Swal.fire({
        title: 'Memproses Analisis',
        text: 'Mengecek data dan menjalankan algoritma AI...',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });

      fetch("{{ route('hasilanalisis.prediksi') }}", {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        }
      })
        .then(response => response.json())
        .then(data => {
          Swal.fire({
            icon: data.status === 'success' ? 'success' : 'info',
            title: data.status === 'success' ? 'Berhasil!' : 'Pemberitahuan',
            text: data.message,
            confirmButtonColor: data.status === 'success' ? '#3085d6' : '#dc3545',
            allowOutsideClick: false
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = "{{ route('hasilanalisis') }}";
            }
          });
        })
        .catch(error => {
          Swal.fire({
            icon: 'error',
            title: 'Kesalahan Sistem',
            text: 'Gagal terhubung ke server. Pastikan Flask API Anda sedang berjalan di terminal.',
            confirmButtonColor: '#d33'
          });
        })
        .finally(() => {
          btn.disabled = false;
          btn.innerHTML = originalContent;
        });
    });
  </script>
@endpush