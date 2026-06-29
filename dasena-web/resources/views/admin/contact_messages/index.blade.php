@extends('layouts.main')

@section('title', 'Pesan Masuk')

@section('page-header')
  <div class="page-header">
    <div class="page-header-left d-flex align-items-center">
      <div class="page-header-title">
        <h5 class="m-b-10">Pesan Masuk</h5>
      </div>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item">Pesan Masuk</li>
      </ul>
    </div>
  </div>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    
    <div class="card stretch stretch-full border-0 shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3 bg-white border-bottom py-3">
        <h5 class="card-title mb-0 fw-bold text-dark">Daftar Pesan Masuk</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="bg-white border-bottom">
              <tr>
                <th style="width: 60px;" class="text-center text-uppercase fs-12 text-muted fw-bolder py-3">No</th>
                <th class="text-uppercase fs-12 text-muted fw-bolder py-3">Pengirim</th>
                <th class="text-center text-uppercase fs-12 text-muted fw-bolder py-3">Email</th>
                <th class="text-center text-uppercase fs-12 text-muted fw-bolder py-3">Subjek</th>
                <th class="text-center text-uppercase fs-12 text-muted fw-bolder py-3">Tanggal</th>
                <th class="text-center text-uppercase fs-12 text-muted fw-bolder py-3">Status</th>
                <th class="text-center text-uppercase fs-12 text-muted fw-bolder py-3" style="width: 120px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($messages as $key => $msg)
                <tr class="{{ !$msg->is_read ? 'bg-light' : '' }}">
                  <td class="text-center text-dark fs-14">{{ $messages->firstItem() + $key }}</td>
                  
                  <td>
                    <div class="d-flex align-items-center gap-3">
                      <div class="avatar-text bg-primary-subtle text-primary fw-bolder rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-size: 14px;">
                        {{ strtoupper(substr($msg->name, 0, 1)) }}
                      </div>
                      <span class="{{ !$msg->is_read ? 'fw-bold text-dark' : 'text-dark fw-medium' }} fs-14">
                        {{ Str::limit($msg->name, 25) }}
                      </span>
                    </div>
                  </td>
                  
                  <td class="text-center text-muted fs-14">{{ Str::limit($msg->email, 25) }}</td>
                  
                  <td class="text-center">
                    <span class="{{ !$msg->is_read ? 'fw-bold text-dark' : 'text-muted' }} fs-14">
                      {{ Str::limit($msg->subject, 35) }}
                    </span>
                  </td>
                  
                  <td class="text-center text-dark fs-14">
                    {{ $msg->created_at->format('d M Y') }}
                  </td>
                  
                  <td class="text-center align-middle">
                    @if($msg->is_replied)
                      <span class="badge bg-success-subtle text-success fs-12 border border-success-subtle d-inline-flex align-items-center justify-content-center gap-1" style="border-radius: 4px; min-width: 95px; height: 30px;">
                        <i class="feather-check"></i> Dibalas
                      </span>
                    @elseif(!$msg->is_read)
                      <span class="badge bg-danger-subtle text-danger fs-12 border border-danger-subtle d-inline-flex align-items-center justify-content-center gap-1" style="border-radius: 4px; min-width: 95px; height: 30px;">
                        <i class="feather-star"></i> Baru
                      </span>
                    @else
                      <span class="badge bg-warning-subtle text-warning fs-12 border border-warning-subtle d-inline-flex align-items-center justify-content-center gap-1" style="border-radius: 4px; min-width: 95px; height: 30px;">
                        <i class="feather-clock"></i> Belum
                      </span>
                    @endif
                  </td>
                  
                  <td class="text-center">
                    <div class="d-flex align-items-center justify-content-center gap-2">
                      <a href="{{ route('admin.messages.show', $msg->id) }}" class="btn btn-sm btn-light border"
                         style="width:32px; height:32px; padding:0; display:inline-flex; align-items:center; justify-content:center; border-radius: 4px;"
                         title="Lihat Detail">
                        <i class="feather-eye text-secondary"></i>
                      </a>
                      
                      <form action="{{ route('admin.messages.destroy', $msg->id) }}" method="POST" class="d-inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-sm btn-danger btn-hapus"
                                style="width:32px; height:32px; padding:0; display:inline-flex; align-items:center; justify-content:center; border-radius: 4px;"
                                title="Hapus">
                          <i class="feather-trash-2 text-white"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-5 text-muted">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                      <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="feather-inbox fs-3 text-secondary"></i>
                      </div>
                      <h6 class="fw-bolder mb-1">Tidak ada pesan</h6>
                      <p class="fs-13 mb-0">Kotak masuk Anda saat ini kosong.</p>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      
      @if($messages->hasPages() || $messages->total() > 0)
        <div class="card-footer bg-white border-top d-flex flex-column flex-md-row justify-content-between align-items-center py-3 gap-3">
          <div class="w-100 text-center text-md-start" style="flex: 1;">
            <small class="text-muted fs-13">
              Menampilkan {{ $messages->firstItem() ?? 0 }}–{{ $messages->lastItem() ?? 0 }} dari {{ $messages->total() }} pesan
            </small>
          </div>
          <div class="d-flex justify-content-center pagination-sm m-0" style="flex: 1;">
            {{ $messages->links('pagination::bootstrap-5') }}
          </div>
          <div class="d-none d-md-block" style="flex: 1;"></div>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      @if(session('success'))
        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: "{{ session('success') }}",
          showConfirmButton: false,
          timer: 2000,
          customClass: {
            popup: 'rounded-3 shadow-sm border-0'
          }
        });
      @endif
    });

   document.addEventListener('click', function (e) {
      const btn = e.target.closest('.btn-hapus');
      if (!btn) return;
      
      e.preventDefault();
      const row = btn.closest('tr');
      const senderNameElement = row.querySelector('td:nth-child(2) span');
      const senderName = senderNameElement ? senderNameElement.innerText.trim() : 'Pesan ini';
      
      Swal.fire({
        title: 'Hapus Pesan?',
        html: `Pesan dari <b>${senderName}</b> akan dihapus secara permanen!`, 
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d32f2f', 
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'

      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            title: 'Menghapus...',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => Swal.showLoading()
          });
          btn.closest('form').submit();
        }
      });
    });
  </script>
@endpush