@extends('layouts.main')

@section('title', 'Dashboard Dasena')

@section('page-header')
  <div class="page-header">
    <div class="page-header-left d-flex align-items-center">
      <div class="page-header-title">
        <h5 class="m-b-10">Dashboard</h5>
      </div>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item">Dashboard</li>
      </ul>
    </div>
  </div>
@endsection

@section('content')
  <div class="row">

    <div class="col-xxl-3 col-md-6">
      <div class="card stretch stretch-full">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between mb-4">
            <div class="d-flex gap-3 align-items-center">
              <div class="avatar-text avatar-lg bg-soft-success text-success">
                <i class="feather-smile"></i>
              </div>
              <div>
                <div class="fs-4 fw-bold text-dark">{{ number_format($stats['positif']) }}</div>
                <h3 class="fs-13 fw-semibold text-truncate-1-line">Sentimen Positif</h3>
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-between mb-1">
            <small class="text-muted">Proporsi</small>
            <small class="text-muted">
              {{ $stats['total'] > 0 ? number_format(($stats['positif'] / $stats['total']) * 100, 1) : 0 }}%
            </small>
          </div>
          <div class="progress ht-3">
            <div class="progress-bar bg-success"
              style="width: {{ $stats['total'] > 0 ? ($stats['positif'] / $stats['total']) * 100 : 0 }}%"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xxl-3 col-md-6">
      <div class="card stretch stretch-full">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between mb-4">
            <div class="d-flex gap-3 align-items-center">
              <div class="avatar-text avatar-lg bg-soft-warning text-warning">
                <i class="feather-meh"></i>
              </div>
              <div>
                <div class="fs-4 fw-bold text-dark">{{ number_format($stats['netral']) }}</div>
                <h3 class="fs-13 fw-semibold text-truncate-1-line">Sentimen Netral</h3>
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-between mb-1">
            <small class="text-muted">Proporsi</small>
            <small class="text-muted">
              {{ $stats['total'] > 0 ? number_format(($stats['netral'] / $stats['total']) * 100, 1) : 0 }}%
            </small>
          </div>
          <div class="progress ht-3">
            <div class="progress-bar bg-warning"
              style="width: {{ $stats['total'] > 0 ? ($stats['netral'] / $stats['total']) * 100 : 0 }}%"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xxl-3 col-md-6">
      <div class="card stretch stretch-full">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between mb-4">
            <div class="d-flex gap-3 align-items-center">
              <div class="avatar-text avatar-lg bg-soft-danger text-danger">
                <i class="feather-frown"></i>
              </div>
              <div>
                <div class="fs-4 fw-bold text-dark">{{ number_format($stats['negatif']) }}</div>
                <h3 class="fs-13 fw-semibold text-truncate-1-line">Sentimen Negatif</h3>
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-between mb-1">
            <small class="text-muted">Proporsi</small>
            <small class="text-muted">
              {{ $stats['total'] > 0 ? number_format(($stats['negatif'] / $stats['total']) * 100, 1) : 0 }}%
            </small>
          </div>
          <div class="progress ht-3">
            <div class="progress-bar bg-danger"
              style="width: {{ $stats['total'] > 0 ? ($stats['negatif'] / $stats['total']) * 100 : 0 }}%"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xxl-3 col-md-6">
      <div class="card stretch stretch-full">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between mb-4">
            <div class="d-flex gap-3 align-items-center">
              <div class="avatar-text avatar-lg bg-soft-primary text-primary">
                <i class="feather-message-square"></i>
              </div>
              <div>
                <div class="fs-4 fw-bold text-dark">{{ number_format($adminData['total_all_data']) }}</div>
                <h3 class="fs-13 fw-semibold text-truncate-1-line">Total Komentar</h3>
              </div>
            </div>
          </div>

          @php
            $persentaseKlasifikasi = $adminData['total_all_data'] > 0
              ? ($stats['total'] / $adminData['total_all_data']) * 100
              : 0;
          @endphp

          <div class="d-flex justify-content-between mb-1">
            <small class="text-muted">Terklasifikasi ({{ number_format($stats['total']) }})</small>
            <small class="text-muted">{{ number_format($persentaseKlasifikasi, 1) }}%</small>
          </div>
          <div class="progress ht-3">
            <div class="progress-bar bg-primary" style="width: {{ $persentaseKlasifikasi }}%"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="card stretch stretch-full">
        <div class="card-header">
          <h5 class="card-title d-flex align-items-center gap-2">
            <i class="feather-shield text-primary"></i> System Monitor
            <span class="badge bg-soft-primary text-primary fs-11 fw-medium">Admin Only</span>
          </h5>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-3 col-sm-6">
              <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-light">
                <div
                  class="avatar-text avatar-md {{ $adminData['flask_online'] ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }}">
                  <i class="feather-cpu"></i>
                </div>
                <div>
                  <small class="text-muted d-block">Flask API</small>
                  <span class="fw-bold fs-13 {{ $adminData['flask_online'] ? 'text-success' : 'text-danger' }}">
                    {{ $adminData['flask_online'] ? 'Online' : 'Offline' }}
                  </span>
                </div>
              </div>
            </div>

            <div class="col-md-3 col-sm-6">
              <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-light">
                <div class="avatar-text avatar-md bg-soft-info text-info">
                  <i class="feather-users"></i>
                </div>
                <div>
                  <small class="text-muted d-block">Total User</small>
                  <span class="fw-bold fs-13 text-dark">{{ number_format($adminData['total_users']) }} Akun</span>
                </div>
              </div>
            </div>

            <div class="col-md-3 col-sm-6">
              <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-light">
                <div class="avatar-text avatar-md bg-soft-warning text-warning">
                  <i class="feather-clock"></i>
                </div>
                <div>
                  <small class="text-muted d-block">Antrean Analisis ML</small>
                  <span class="fw-bold fs-13 text-dark">{{ number_format($adminData['pending_prep']) }} Data</span>
                </div>
              </div>
            </div>

            <div class="col-md-3 col-sm-6">
              <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-light">
                <div class="avatar-text avatar-md bg-soft-success text-success">
                  <i class="feather-activity"></i>
                </div>
                <div>
                  <small class="text-muted d-block">Prediksi Hari Ini</small>
                  <span class="fw-bold fs-13 text-dark">{{ number_format($adminData['prediksi_hari_ini']) }}
                    Analisis</span>
                </div>
              </div>
            </div>

            <div class="col-md-3 col-sm-6">
              <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-light">
                <div class="avatar-text avatar-md bg-soft-primary text-primary">
                  <i class="feather-book-open"></i>
                </div>
                <div>
                  <small class="text-muted d-block">Total Kamus</small>
                  <span class="fw-bold fs-13 text-dark">{{ number_format($adminData['total_kamus']) }} Kata</span>
                </div>
              </div>
            </div>

            <div class="col-md-3 col-sm-6">
              <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-light">
                <div class="avatar-text avatar-md bg-soft-secondary text-secondary">
                  <i class="feather-database"></i>
                </div>
                <div>
                  <small class="text-muted d-block">Total Data di DB</small>
                  <span class="fw-bold fs-13 text-dark">{{ number_format($adminData['total_all_data']) }} Baris</span>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="p-3 rounded-3 bg-light">
                <small class="text-muted d-block mb-2 fw-medium">Dataset Terbaru</small>
                @forelse($adminData['latest_datasets'] as $ds)
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="fs-12 text-truncate me-2" style="max-width: 200px;">
                      <i class="feather-file-text text-success me-1"></i>{{ $ds->file_name }}
                    </span>
                    <div class="d-flex align-items-center gap-2">
                      <span class="fs-11 text-muted">{{ $ds->created_at->format('d M Y') }}</span>
                      <span
                        class="badge {{ $ds->status == 'Selesai Diproses' ? 'bg-soft-success text-success' : ($ds->status == 'Processing' ? 'bg-soft-info text-info' : 'bg-soft-warning text-warning') }} fs-10">
                        {{ $ds->status }}
                      </span>
                    </div>
                  </div>
                @empty
                  <small class="text-muted">Belum ada dataset.</small>
                @endforelse
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xxl-8 col-lg-7">
      <div class="card stretch stretch-full">
        <div class="card-header">
          <h5 class="card-title">Tren Analisis Sentimen Tahun 2025</h5>
        </div>
        <div class="card-body">
          <div id="sentiment-trend-chart"></div>
        </div>
      </div>
    </div>

    <div class="col-xxl-4 col-lg-5">
      <div class="card stretch stretch-full">
        <div class="card-header">
          <h5 class="card-title">Distribusi Opini</h5>
        </div>
        <div class="card-body d-flex justify-content-center align-items-center">
          <div id="sentiment-donut-chart"></div>
        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="card stretch stretch-full">
        <div class="card-header d-flex justify-content-between align-items-center">
          <div>
            <h5 class="card-title">Topik Populer (Word Cloud)</h5>
            <span class="fs-12 text-muted">Berdasarkan frekuensi kemunculan kata terbanyak</span>
          </div>

          <ul class="nav nav-pills nav-sm" id="wordCloudTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="wc-positif-tab" data-bs-toggle="pill" data-bs-target="#wc-positif"
                type="button" role="tab">Positif</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="wc-netral-tab" data-bs-toggle="pill" data-bs-target="#wc-netral" type="button"
                role="tab">Netral</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="wc-negatif-tab" data-bs-toggle="pill" data-bs-target="#wc-negatif"
                type="button" role="tab">Negatif</button>
            </li>
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content" id="wordCloudTabsContent">
            <div class="tab-pane fade show active" id="wc-positif" role="tabpanel">
              <div id="word-cloud-positif" style="width: 100%; height: 350px;"></div>
            </div>
            <div class="tab-pane fade" id="wc-netral" role="tabpanel">
              <div id="word-cloud-netral" style="width: 100%; height: 350px;"></div>
            </div>
            <div class="tab-pane fade" id="wc-negatif" role="tabpanel">
              <div id="word-cloud-negatif" style="width: 100%; height: 350px;"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

      <div class="col-12">
        <div class="card stretch stretch-full">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">Aktivitas Komentar Terbaru</h5>
            <a href="{{ route('hasilanalisis') }}" class="btn btn-sm btn-light">Lihat Semua</a>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead class="bg-light">
                  <tr>
                    <th>Tanggal</th>
                    <th>Komentar</th>
                    <th>Hasil Sentimen</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($recentComments as $comment)
                    <tr>
                      {{-- <td class="text-nowrap">{{ $comment->created_at->format('d M Y') }}</td> --}}
                      <td class="text-nowrap">{{ \Carbon\Carbon::parse($comment->tanggal)->format('d M Y') }}</td>
                      <td class="text-truncate" style="max-width: 500px;">
                        {{ $comment->teks_cleansed ?? $comment->teks }}
                      </td>
                      <td>
                        @if($comment->sentimen == 'Positif')
                          <span class="badge bg-soft-success text-success">Positif</span>
                        @elseif($comment->sentimen == 'Negatif')
                          <span class="badge bg-soft-danger text-danger">Negatif</span>
                        @else
                          <span class="badge bg-soft-warning text-warning">Netral</span>
                        @endif
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="3" class="text-center text-muted py-4">Belum ada data komentar terklasifikasi.</td>
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
  <script src="https://cdn.anychart.com/releases/v8/js/anychart-base.min.js"></script>
  <script src="https://cdn.anychart.com/releases/v8/js/anychart-tag-cloud.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const rawTrend = @json($trendData ?? []);
      const stats = {
        positif: {{ $stats['positif'] ?? 0 }},
        netral: {{ $stats['netral'] ?? 0 }},
        negatif: {{ $stats['negatif'] ?? 0 }}
          };

      const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

      let chartData = {
        Positif: Array(12).fill(0),
        Netral: Array(12).fill(0),
        Negatif: Array(12).fill(0)
      };

      if (Array.isArray(rawTrend)) {
        rawTrend.forEach(function (item) {
          if (item.sentimen && item.bulan) {
            let sentimenKey = item.sentimen.charAt(0).toUpperCase() + item.sentimen.slice(1).toLowerCase();
            if (chartData[sentimenKey] !== undefined) {
              let indexBulan = parseInt(item.bulan) - 1;
              if (indexBulan >= 0 && indexBulan <= 11) {
                chartData[sentimenKey][indexBulan] = parseInt(item.jumlah);
              }
            }
          }
        });
      }

      if (document.querySelector('#sentiment-trend-chart')) {
        new ApexCharts(document.querySelector('#sentiment-trend-chart'), {
          series: [
            { name: 'Positif', data: chartData.Positif },
            { name: 'Netral', data: chartData.Netral },
            { name: 'Negatif', data: chartData.Negatif }
          ],
          chart: { height: 320, type: 'area', toolbar: { show: false }, fontFamily: 'inherit' },
          colors: ['#28a745', '#ffc107', '#dc3545'],
          stroke: { curve: 'smooth', width: 2 },
          fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.05 }
          },
          xaxis: { categories: months },
          tooltip: { y: { formatter: val => val + ' komentar' } },
          legend: { position: 'top' },
          dataLabels: { enabled: false }
        }).render();
      }

      if (document.querySelector('#sentiment-donut-chart')) {
        let totalData = stats.positif + stats.netral + stats.negatif;

        if (totalData === 0) {
          new ApexCharts(document.querySelector('#sentiment-donut-chart'), {
            series: [1],
            labels: ['Belum Ada Sentimen'],
            colors: ['#e9ecef'],
            chart: { type: 'donut', height: 320, fontFamily: 'inherit' },
            plotOptions: { pie: { donut: { size: '70%' } } },
            dataLabels: { enabled: false },
            tooltip: { enabled: false },
            legend: { position: 'bottom' }
          }).render();
        } else {
          new ApexCharts(document.querySelector('#sentiment-donut-chart'), {
            series: [stats.positif, stats.netral, stats.negatif],
            chart: { type: 'donut', height: 320, fontFamily: 'inherit' },
            labels: ['Positif', 'Netral', 'Negatif'],
            colors: ['#28a745', '#ffc107', '#dc3545'],
            plotOptions: { pie: { donut: { size: '70%' } } },
            legend: { position: 'bottom' },
            dataLabels: { enabled: false },
            tooltip: { y: { formatter: val => val + ' komentar' } }
          }).render();
        }
      }

      // cloud word
      const wcPositif = @json($wordCloudDataPositif ?? []);
      const wcNetral = @json($wordCloudDataNetral ?? []);
      const wcNegatif = @json($wordCloudDataNegatif ?? []);
    
      function renderWordCloud(containerId, data, colorPalette) {
        let container = document.querySelector(containerId);
        if (container) {
          if (data.length > 0) {
            container.innerHTML = ''; 
            var chart = anychart.tagCloud(data);
            chart.angles([0, 0, 0]);
            chart.colorRange(false);
            chart.tooltip().format("{%value} kali diucapkan");
            chart.colorScale(anychart.scales.ordinalColor().colors(colorPalette));
            chart.container(containerId.substring(1)); 
            chart.draw();
          } else {
            container.innerHTML = '<div class="d-flex h-100 align-items-center justify-content-center text-muted">Data kata belum tersedia.</div>';
          }
        }
      }

      renderWordCloud('#word-cloud-positif', wcPositif, ["#198754", "#20c997", "#28a745", "#5cb85c"]);

      document.getElementById('wc-netral-tab').addEventListener('shown.bs.tab', function () {
        if (document.querySelector('#word-cloud-netral').innerHTML.trim() === '') {
          renderWordCloud('#word-cloud-netral', wcNetral, ["#ffc107", "#fd7e14", "#ffc720"]);
        }
      });

      document.getElementById('wc-negatif-tab').addEventListener('shown.bs.tab', function () {
        if (document.querySelector('#word-cloud-negatif').innerHTML.trim() === '') {
          renderWordCloud('#word-cloud-negatif', wcNegatif, ["#dc3545", "#c82333", "#e83e8c"]); 
        }
      });

    });
  </script>
@endpush