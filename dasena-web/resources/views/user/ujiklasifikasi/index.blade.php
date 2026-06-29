@extends('layouts.main')

@section('title', 'Uji Klasifikasi Sentimen')

@section('page-header')
  <div class="page-header">
    <div class="page-header-left d-flex align-items-center">
      <div class="page-header-title">
        <h5 class="m-b-10">Uji Klasifikasi Sentimen</h5>
      </div>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item">Uji Klasifikasi</li>
      </ul>
    </div>
  </div>
@endsection

@section('content')
  <div class="row">

    <div class="col-lg-5">
      <div class="card stretch stretch-full">
        <div class="card-header">
          <h5 class="card-title">Masukkan Teks Ulasan</h5>
        </div>
        <div class="card-body">
          <p class="text-muted fs-12 mb-4">
            Ketikkan kalimat ulasan atau komentar terkait kinerja Pemadam Kebakaran untuk melihat hasil klasifikasi
            algoritma <b>Naive Bayes</b>.
          </p>

          <div class="mb-3">
            <textarea class="form-control" id="teks_komentar" rows="6"
              placeholder="Contoh: Respon petugas damkar sangat cepat dan sigap saat terjadi insiden kebakaran..."
              style="resize: none;"></textarea>
            <div class="invalid-feedback" id="pesanError">Teks tidak boleh kosong.</div>
          </div>

          <div class="alert alert-info d-flex gap-2 mb-4 py-2" role="alert">
            <i class="feather-check-circle fs-14 mt-1 flex-shrink-0"></i>
            <small>Model AI <b>Aktif</b> dan terhubung melalui Flask Server.</small>
          </div>

          <button type="button" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2"
            id="btnAnalisis">
            <i class="feather-cpu"></i> Analisis Sentimen
          </button>
        </div>
      </div>
    </div>

    <div class="col-lg-7">
      <div class="card stretch stretch-full">
        <div class="card-header">
          <h5 class="card-title d-flex align-items-center gap-2">
            <i class="feather-bar-chart-2 text-primary"></i> Hasil Analisis
          </h5>
        </div>
        <div class="card-body d-flex flex-column justify-content-center" style="min-height: 480px;">

          <div id="hasilStandby" class="text-center py-5">
            <div class="avatar-text avatar-xl bg-soft-secondary text-secondary mx-auto mb-3">
              <i class="feather-inbox fs-2"></i>
            </div>
            <h5 class="fw-bold text-secondary mb-2">Menunggu Input</h5>
            <p class="text-muted small mb-0 px-4">
              Ketikkan ulasan di kolom sebelah kiri lalu tekan <b>Analisis Sentimen</b> untuk melihat hasil prediksi AI.
            </p>
          </div>

          <div id="hasilLoading" class="d-none text-center py-5">
            <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
              <span class="visually-hidden">Loading...</span>
            </div>
            <h5 class="fw-medium text-primary mb-1">Sedang Menganalisis...</h5>
            <small class="text-muted">Model ML sedang memproses dan membersihkan teks Anda</small>
          </div>

          <div id="hasilSelesai" class="d-none">
            <div class="text-center mb-4">
              <div id="iconSentimen" class="avatar-text mx-auto mb-3 text-white"
                style="width:80px; height:80px; border-radius:50%; font-size:36px;">
              </div>
              <h3 id="teksSentimen" class="fw-bold mb-1"></h3>
              <small id="subSentimen" class="text-muted"></small>
            </div>

            <div class="mb-4 p-3 border rounded bg-white" id="boxConfidence">
              <h6 class="fw-bold mb-3 fs-13 text-dark border-bottom pb-2">Detail Probabilitas Prediksi Model</h6>

              <div class="mb-2">
                <div class="d-flex justify-content-between align-items-center mb-1 fs-12">
                  <span class="text-success fw-medium"><i class="feather-smile me-1"></i>Positif</span>
                  <span class="fw-bold" id="valPositif">0%</span>
                </div>
                <div class="progress" style="height: 8px;">
                  <div id="barPositif" class="progress-bar bg-success" style="width: 0%"></div>
                </div>
              </div>

              <div class="mb-2">
                <div class="d-flex justify-content-between align-items-center mb-1 fs-12">
                  <span class="text-warning fw-medium"><i class="feather-meh me-1"></i>Netral</span>
                  <span class="fw-bold" id="valNetral">0%</span>
                </div>
                <div class="progress" style="height: 8px;">
                  <div id="barNetral" class="progress-bar bg-warning" style="width: 0%"></div>
                </div>
              </div>

              <div class="mb-0">
                <div class="d-flex justify-content-between align-items-center mb-1 fs-12">
                  <span class="text-danger fw-medium"><i class="feather-frown me-1"></i>Negatif</span>
                  <span class="fw-bold" id="valNegatif">0%</span>
                </div>
                <div class="progress" style="height: 8px;">
                  <div id="barNegatif" class="progress-bar bg-danger" style="width: 0%"></div>
                </div>
              </div>
            </div>

            <div id="boxKutipan" class="p-3 rounded-3 mb-3 border-start border-4">
              <small class="text-muted d-block mb-1 fw-medium">Teks Asli:</small>
              <p class="mb-0 fst-italic text-dark" id="teksKutipan"></p>
            </div>

            <div class="p-3 rounded-3 mb-4 border-start border-4 border-secondary bg-soft-secondary">
              <small class="text-muted d-block mb-1 fw-medium">Hasil Preprocessing AI (Teks Bersih):</small>
              <p class="mb-0 text-dark fw-bold" id="teksStemmed"></p>
            </div>

            <div class="d-flex gap-2 justify-content-between mt-4 pt-3 border-top">
              <small class="text-muted d-flex align-items-center gap-1">
                <i class="feather-info"></i> Diproses dengan algoritma Naive Bayes
              </small>
              <button class="btn btn-sm btn-light d-flex align-items-center gap-1" onclick="resetHasil()">
                <i class="feather-refresh-cw"></i> Coba Lagi
              </button>
            </div>
          </div>

        </div>
      </div>
    </div>

  </div>
@endsection

@push('scripts')
  <script>
    const btnAnalisis = document.getElementById('btnAnalisis');
    btnAnalisis.addEventListener('click', function () {
      const teks = document.getElementById('teks_komentar').value.trim();
      const textarea = document.getElementById('teks_komentar');
      if (!teks) {
        textarea.classList.add('is-invalid');
        return;
      }
      textarea.classList.remove('is-invalid');

      document.getElementById('hasilStandby').classList.add('d-none');
      document.getElementById('hasilSelesai').classList.add('d-none');
      document.getElementById('hasilLoading').classList.remove('d-none');

      btnAnalisis.disabled = true;
      btnAnalisis.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Memproses...';

      fetch('{{ route('uji.klasifikasi.analisis') }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ teks: teks })
      })
        .then(function (r) {
          return r.json();
        })
        .then(function (data) {
          if (data.success) {
            tampilkanHasil(teks, data.sentimen, data.teks_stemmed, data.confidences);
          } else {
            document.getElementById('hasilLoading').classList.add('d-none');
            document.getElementById('hasilStandby').classList.remove('d-none');
            Swal.fire({
              icon: 'error',
              title: 'Gagal!',
              text: data.message ?? 'Terjadi kesalahan pada server.',
              confirmButtonColor: '#d32f2f'
            });
          }
        })
        .catch(function (err) {
          document.getElementById('hasilLoading').classList.add('d-none');
          document.getElementById('hasilStandby').classList.remove('d-none');
          Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Terjadi kesalahan: ' + err.message,
            confirmButtonColor: '#d32f2f'
          });
        })
        .finally(function () {
          btnAnalisis.disabled = false;
          btnAnalisis.innerHTML = '<i class="feather-cpu"></i> Analisis Sentimen';
        });
    });

    function tampilkanHasil(teks, sentimen, teksStemmed, confidences) {
      document.getElementById('hasilLoading').classList.add('d-none');
      document.getElementById('hasilSelesai').classList.remove('d-none');
      document.getElementById('teksKutipan').innerText = teks;
      document.getElementById('teksStemmed').innerText = teksStemmed;

      const icon = document.getElementById('iconSentimen');
      const teksEl = document.getElementById('teksSentimen');
      const subEl = document.getElementById('subSentimen');
      const box = document.getElementById('boxKutipan');

      icon.className = 'avatar-text mx-auto mb-3 text-white';
      icon.style.cssText = 'width:80px; height:80px; border-radius:50%; font-size:36px;';
      teksEl.className = 'fw-bold mb-1';

      if (sentimen === 'Positif') {
        icon.classList.add('bg-success');
        icon.innerHTML = '<i class="feather-smile"></i>';
        teksEl.classList.add('text-success');
        teksEl.innerText = 'Sentimen Positif';
        subEl.innerText = 'Ulasan ini mengandung sentimen yang baik.';
        box.className = 'p-3 rounded-3 mb-3 border-start border-4 border-success bg-soft-success';
      } else if (sentimen === 'Negatif') {
        icon.classList.add('bg-danger');
        icon.innerHTML = '<i class="feather-frown"></i>';
        teksEl.classList.add('text-danger');
        teksEl.innerText = 'Sentimen Negatif';
        subEl.innerText = 'Ulasan ini mengandung sentimen yang kurang baik.';
        box.className = 'p-3 rounded-3 mb-3 border-start border-4 border-danger bg-soft-danger';
      } else {
        icon.classList.add('bg-warning');
        icon.innerHTML = '<i class="feather-meh"></i>';
        teksEl.classList.add('text-warning');
        teksEl.innerText = 'Sentimen Netral';
        subEl.innerText = 'Ulasan ini tidak mengandung sentimen yang kuat.';
        box.className = 'p-3 rounded-3 mb-3 border-start border-4 border-warning bg-soft-warning';
      }

      setTimeout(() => {
        document.getElementById('barPositif').style.width = (confidences.Positif || 0) + '%';
        document.getElementById('barNetral').style.width = (confidences.Netral || 0) + '%';
        document.getElementById('barNegatif').style.width = (confidences.Negatif || 0) + '%';
      }, 100);

      document.getElementById('valPositif').innerText = (confidences.Positif || 0) + '%';
      document.getElementById('valNetral').innerText = (confidences.Netral || 0) + '%';
      document.getElementById('valNegatif').innerText = (confidences.Negatif || 0) + '%';
    }

    function resetHasil() {
      document.getElementById('hasilSelesai').classList.add('d-none');
      document.getElementById('hasilStandby').classList.remove('d-none');
      document.getElementById('teks_komentar').value = '';
      document.getElementById('teks_komentar').classList.remove('is-invalid');
      document.getElementById('barPositif').style.width = '0%';
      document.getElementById('barNetral').style.width = '0%';
      document.getElementById('barNegatif').style.width = '0%';
    }
  </script>
@endpush