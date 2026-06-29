  @extends('layouts.main')

  @section('title', 'Preprocessing Data')

  @section('page-header')
    <div class="page-header">
      <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
          <h5 class="m-b-10">Preprocessing Data</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item">Preprocessing</li>
        </ul>
      </div>

      <div class="page-header-right ms-auto d-flex gap-2">
        <button type="button" id="btn-jalankan-preprocessing"
          class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
          <i class="feather-play"></i> Jalankan Preprocessing
        </button>
      </div>
    </div>
  @endsection

  @section('content')
    <div class="row">

      @if(session('duplikat_terhapus') !== null)
        <div class="col-12 mb-3">
          <div class="alert alert-success d-flex align-items-center mb-0" role="alert">
            <i class="feather-filter fs-4 me-2"></i>
            <div>
              <strong>Pembersihan Awal Selesai!</strong> Sistem berhasil menghapus <b>{{ session('duplikat_terhapus') }}</b>
              data duplikat dan <b>{{ session('irrelevant_terhapus') ?? 0 }}</b> data di luar konteks.
            </div>
          </div>
        </div>
      @endif

      <div class="col-12 mb-4">
        <div class="card bg-soft-primary border-primary border-dashed border-2">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div>
              @if($totalItems > 0 && $totalItems == $processedItems)
                <h5 class="fw-bold text-success mb-1">Status Preprocessing: Selesai</h5>
                <p class="text-muted mb-0">Semua dataset telah berhasil difilter, dibersihkan, dan distemming.</p>
              @elseif($processedItems > 0)
                <h5 class="fw-bold text-warning mb-1">Status Preprocessing: Berjalan</h5>
                <p class="text-muted mb-0">Sebagian data telah diproses. Tekan tombol Jalankan untuk melanjutkan.</p>
              @else
                <h5 class="fw-bold text-danger mb-1">Status Preprocessing: Menunggu</h5>
                <p class="text-muted mb-0">Belum ada teks yang diproses. Silakan jalankan mesin preprocessing.</p>
              @endif
            </div>
            <div class="text-end">
              <h3 class="fw-bold text-dark mb-0">
                {{ number_format($processedItems, 0, ',', '.') }} / {{ number_format($totalItems, 0, ',', '.') }}
              </h3>
              <span class="fs-12 text-muted">Komentar Diproses</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12">
        <div class="card stretch stretch-full">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">Log Pembersihan Teks</h5>
            <div class="search-form">
              <form action="{{ url()->current() }}" method="GET">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari teks asli..."
                  value="{{ request('search') }}">
              </form>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover table-bordered mb-0 align-middle" style="table-layout: fixed; width: 100%; min-width: 1200px;">
                <thead class="bg-light text-center">
                  <tr>
                    <th style="width: 18%;">Teks Asli (Kotor)</th>
                    <th style="width: 16%;">Cleansing & Case Folding</th>
                    <th style="width: 20%;">Tokenisasi</th>
                    <th style="width: 14%;">Normalisasi</th>
                    <th style="width: 14%;">Stopword Removal</th>
                    <th style="width: 18%;">Stemming (Teks Bersih)</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($datasetItems as $item)
                    <tr>
                      <td class="text-wrap text-break" style="white-space: normal; line-height: 1.5;">
                        <div>{{ $item->teks }}</div>
                        @if($item->word_count)
                          <div class="mt-2 text-muted" style="font-size: 0.75rem;">
                            <i class="feather-align-left"></i> {{ $item->word_count }} kata
                          </div>
                        @endif
                      </td>
                      <td class="text-wrap text-break" style="white-space: normal;">
                        <span class="text-muted fs-13">{{ $item->teks_cleansed ?? '-' }}</span>
                      </td>
                      <td class="text-wrap text-break" style="white-space: normal;">
                        @if($item->tokenisasi)
                          @php
                            $tokens = json_decode($item->tokenisasi, true);
                            if(json_last_error() !== JSON_ERROR_NONE) {
                                $tokens = explode(',', $item->tokenisasi);
                            }
                          @endphp
                          <div class="d-flex flex-wrap gap-1">
                            @foreach($tokens as $token)
                              @if(trim($token) !== '')
                                <span class="badge bg-light text-dark border fw-normal">{{ trim($token) }}</span>
                              @endif
                            @endforeach
                          </div>
                        @else
                          <span class="text-muted fs-13">-</span>
                        @endif
                      </td>
                      <td class="text-wrap text-break" style="white-space: normal;">
                        <span class="text-muted fs-13">{{ $item->teks_normalized ?? '-' }}</span>
                      </td>
                      <td class="text-wrap text-break" style="white-space: normal;">
                        <span class="text-muted fs-13">{{ $item->teks_stopword ?? '-' }}</span>
                      </td>
                      <td class="text-wrap text-break" style="white-space: normal; vertical-align: middle;">
                        @if($item->teks_stemmed)
                          <span class="badge bg-soft-primary text-primary fs-13 text-wrap text-start lh-base text-break d-block mb-2"
                            style="white-space: normal;">
                            {{ $item->teks_stemmed }}
                          </span>
    
                          @php
                            $final_word_count = str_word_count($item->teks_stemmed);
                            $reduksi = 0;
                            if($item->word_count && $item->word_count > 0){
                                $reduksi = round((($item->word_count - $final_word_count) / $item->word_count) * 100);
                            }
                          @endphp
                          <div class="text-success fw-bold" style="font-size: 0.75rem;">
                            <i class="feather-check-circle"></i> {{ $final_word_count }} kata bersih (Direduksi {{ $reduksi }}%)
                          </div>
                        @else
                          <span class="badge bg-soft-secondary text-secondary fs-13">Menunggu...</span>
                        @endif
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="6" class="text-center text-muted py-5">
                        <div class="d-flex flex-column align-items-center">
                          <i class="feather-inbox fs-2 mb-2"></i>
                          <span>Belum ada data teks. Silakan unggah dataset terlebih dahulu.</span>
                        </div>
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
                Menampilkan {{ $datasetItems->firstItem() ?? 0 }}–{{ $datasetItems->lastItem() ?? 0 }}
                dari {{ $datasetItems->total() }} data
              </small>
            </div>
            <div class="d-flex justify-content-center" style="flex: 1;">
              {{ $datasetItems->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
            <div class="d-none d-md-block" style="flex: 1;"></div>
          </div>
        </div>
      </div>
    </div>
  @endsection

  @push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>

    <script>
      $(document).ready(function () {
        @if(session('success'))
          Swal.fire({ title: "Berhasil!", text: "{!! session('success') !!}", icon: "success", confirmButtonColor: '#3085d6' });
        @endif
        @if(session('info'))
          Swal.fire({ title: "Informasi", text: "{!! session('info') !!}", icon: "info", confirmButtonColor: '#3085d6' });
        @endif
        @if(session('error'))
          Swal.fire({ title: "Gagal!", text: "{!! session('error') !!}", icon: "error", confirmButtonColor: '#d32f2f' });
        @endif

        $('#btn-jalankan-preprocessing').on('click', function (e) {
          e.preventDefault();
          $(this).prop('disabled', true);

          Swal.fire({
            title: 'Mohon Tunggu...',
            text: 'Sedang melakukan filtering dataset...',
            icon: 'info',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
              Swal.showLoading();

              $.ajax({
                url: "{{ route('admin.preprocessing.filterClean') }}",
                type: "POST",
                data: { _token: "{{ csrf_token() }}" },
                success: function (res) {

                  Swal.fire({
                    title: 'Memproses Data...',
                    html: 'Sedang melakukan preprocessing...<br><br>Jumlah data diproses: <b id="progress-text">{{ $processedItems }}</b>',
                    icon: 'info',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                      Swal.showLoading();
                      processBatch(); 
                    }
                  });

                },
                error: function (xhr) {
                  $('#btn-jalankan-preprocessing').prop('disabled', false);
                  Swal.fire('Error', 'Gagal memfilter data SQL', 'error');
                }
              });
            }
          });
        });

        function processBatch() {
          $.ajax({
            url: "{{ route('admin.preprocessing.process') }}",
            type: "POST",
            data: {
              _token: "{{ csrf_token() }}"
            },
            success: function (response) {
              if (response.status === 'completed') {
                Swal.fire({
                  title: "Selesai!",
                  text: "Semua dataset berhasil difilter dan diproses hingga stemming.",
                  icon: "success"
                }).then(() => {
                  location.reload();
                });
              } else if (response.status === 'processing') {
                $('#progress-text').text(response.processed + " / " + response.total);
                processBatch(); 
              }
            },
            error: function (xhr) {
              $('#btn-jalankan-preprocessing').prop('disabled', false);
              Swal.fire(
                'Error API',
                'Koneksi terputus atau batas waktu habis. Silakan cek terminal Flask!',
                'error'
              );
            }
          });
        }
      });
    </script>
  @endpush