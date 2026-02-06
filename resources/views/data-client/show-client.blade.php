@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
            <!-- Tombol Kembali -->
            <a href="{{ route('data-client.index') }}" class="btn btn-outline-secondary me-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            
            <!-- Judul di samping kanan tombol -->
            <div>
                <h3 class="mb-0">Detail Client</h3>
                <p class="text-muted mb-0">Informasi lengkap data client</p>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Card Informasi Utama -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Informasi Client
                    </h6>
                    
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td width="30%"><strong><i class="fas fa-user text-primary"></i> Nama Lengkap</strong></td>
                                <td>: {{ $client->name }}</td>
                            </tr>
                            <tr>
                                <td><strong><i class="fas fa-envelope text-info"></i> Email</strong></td>
                                <td>: 
                                    @if($client->email)
                                        <a href="mailto:{{ $client->email }}">{{ $client->email }}</a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong><i class="fas fa-phone text-success"></i> Telepon</strong></td>
                                <td>: 
                                    @if($client->phone)
                                        <a href="tel:{{ $client->phone }}">{{ $client->phone }}</a>
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $client->phone) }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-success ms-2">
                                            <i class="fab fa-whatsapp"></i> WhatsApp
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong><i class="fas fa-building text-warning"></i> Nama Brand</strong></td>
                                <td>: {{ $client->nama_brand ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong><i class="fas fa-map-marker-alt text-danger"></i> Alamat</strong></td>
                                <td>: {{ $client->address ?? '-' }}</td>
                            </tr>
                            
                            <tr>
                                <td><strong><i class="fas fa-tags text-secondary"></i> Kategori</strong></td>
                                <td>: {{ $client->category ?? '-' }}</td>
                            </tr>
                             <tr>
                                <td><strong><i class="fas fa-calendar-plus text-success"></i> Langganan Dimulai</strong></td>
                                <td>: {{ $client->start_date ?? '-' }}</td>
                            </tr>
                             <tr>
                                <td><strong><i class="fas fa-calendar-times text-danger"></i> Langganan Berakhir</strong></td>
                                <td>: {{ $client->expired_date ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong><i class="fas fa-toggle-on text-primary"></i> Status</strong></td>
                                <td>: 
                                    <span class="badge {{ $client->status == 'aktif' ? 'badge-active' : 'badge-inactive' }}">
                                        {{ ucfirst($client->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong><i class="fas fa-sticky-note text-warning"></i> Catatan</strong></td>
                                <td>: {{ $client->notes ?? '-' }}</td>
                            </tr>
            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Card Sidebar -->
        <div class="col-lg-4">
            <!-- Card Timeline -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-alt"></i> Timeline
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">
                            <i class="fas fa-calendar-plus"></i> Tanggal Bergabung
                        </small>
                        <strong>{{ $client->created_at->format('d F Y') }}</strong>
                        <br>
                        <small class="text-muted">{{ $client->created_at->diffForHumans() }}</small>
                    </div>
                    <hr>
                    <div>
                        <small class="text-muted d-block">
                            <i class="fas fa-clock"></i> Terakhir Diupdate
                        </small>
                        <strong>{{ $client->updated_at->format('d F Y H:i') }} WIB</strong>
                        <br>
                        <small class="text-muted">{{ $client->updated_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>

           <!-- Card Aksi Cepat -->
<div class="card shadow mb-2">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-bolt"></i> Aksi Cepat
        </h6>
    </div>
    <div class="card-body d-flex flex-column gap-2">
        <!-- Tombol 1: Edit Data -->
        <a href="{{ route('data-client.edit', $client->id) }}" 
           class="btn btn-warning">
            <i class="fas fa-edit me-2"></i> Edit Data
        </a>

        <!-- Tombol 2: Aktifkan/Nonaktifkan -->
        <button type="button" 
                class="btn btn-{{ $client->status === 'aktif' ? 'danger' : 'success' }}"
                data-bs-toggle="modal" 
                data-bs-target="#statusModal{{ $client->id }}">
            <i class="fas fa-sync-alt me-2"></i>
            {{ $client->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
        </button>

        <!-- Tombol 3: Hapus Client -->
        <button type="button" 
                class="btn btn-outline-danger"
                data-bs-toggle="modal" 
                data-bs-target="#deleteModal{{ $client->id }}">
            <i class="fas fa-trash me-2"></i> Hapus Client
        </button>
    </div>
</div>

<!-- Modal Konfirmasi Status -->
<div class="modal fade" id="statusModal{{ $client->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-{{ $client->status === 'aktif' ? 'warning' : 'primary' }} text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Konfirmasi Perubahan Status
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('data-client.update-status', $client->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="{{ $client->status === 'aktif' ? 'nonaktif' : 'aktif' }}">
                
                <div class="modal-body">
                    <!-- ... isi modal status sama seperti sebelumnya ... -->
                    <div class="text-center mb-4">
                        <div class="icon-wrapper mb-3">
                            <i class="fas fa-exchange-alt fa-3x text-{{ $client->status === 'aktif' ? 'warning' : 'primary' }}"></i>
                        </div>
                        
                        <h5 class="mb-2">
                            Ubah status 
                            <span class="text-{{ $client->status === 'aktif' ? 'danger' : 'success' }}">
                                {{ ucfirst($client->nama_brand) }}
                            </span>
                        </h5>
                        
                        <div class="status-change-display mb-3">
                            <div class="d-flex justify-content-center align-items-center">
                                <span class="badge bg-{{ $client->status === 'aktif' ? 'success' : 'danger' }} px-3 py-2 me-2">
                                    <i class="fas fa-{{ $client->status === 'aktif' ? 'check-circle' : 'times-circle' }} me-1"></i>
                                    {{ ucfirst($client->status) }}
                                </span>
                                <i class="fas fa-arrow-right mx-3 text-muted"></i>
                                <span class="badge bg-{{ $client->status === 'aktif' ? 'danger' : 'success' }} px-3 py-2">
                                    <i class="fas fa-{{ $client->status === 'aktif' ? 'times-circle' : 'check-circle' }} me-1"></i>
                                    {{ $client->status === 'aktif' ? 'Nonaktif' : 'Aktif' }}
                                </span>
                            </div>
                        </div>
                        
                        <p class="text-muted mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            Klien akan 
                            <strong>{{ $client->status === 'aktif' ? 'tidak dapat' : 'dapat' }}</strong> 
                            mengakses layanan setelah perubahan ini.
                        </p>
                    </div>
                    
                    <div class="alert alert-{{ $client->status === 'aktif' ? 'warning' : 'info' }} border-{{ $client->status === 'aktif' ? 'warning' : 'info' }}">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-{{ $client->status === 'aktif' ? 'exclamation-triangle' : 'lightbulb' }} fa-lg"></i>
                            </div>
                            <div>
                                <small>
                                    <strong>Perhatian:</strong> 
                                    {{ $client->status === 'aktif' 
                                        ? 'Klien aktif akan kehilangan akses ke semua layanan.' 
                                        : 'Klien akan mendapatkan akses penuh ke layanan.' }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-{{ $client->status === 'aktif' ? 'warning' : 'primary' }}">
                        <i class="fas fa-check me-1"></i> Ya, Ubah Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
                    

            <!-- Card Invoice -->
<!-- Card Invoice -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-file-invoice"></i> Invoice
        </h6>
    </div>

    <div class="card-body">
        @if($client->invoices->count())
            <ul class="list-group list-group-flush">
                @foreach($client->invoices as $invoice)
                    <li class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $invoice->invoice_number }}</strong><br>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}
                                    | {{ $invoice->file_original_name }}
                                    | {{ number_format($invoice->file_size / 1024, 2) }} KB
                                </small>
                            </div>
                            <div>
                                <a href="{{ route('invoices.show', $invoice->id) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                                <a href="{{ route('invoices.download', $invoice->id) }}"
                                   class="btn btn-sm btn-success">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-center text-muted">
                <i class="fas fa-file-invoice fa-2x mb-2"></i>
                <p class="mb-0">Belum ada invoice</p>
            </div>
        @endif
    </div>
</div>

            <!-- Card Statistik -->
            <!-- <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar"></i> Statistik
                    </h6>
                </div>
                <div class="card-body text-center">
                    <h2 class="text-primary">{{ $client->created_at->diffInDays(now()) }}</h2>
                    <small class="text-muted">Hari Bergabung</small>
                    <hr>
                    <p class="mb-0">
                        <i class="fas fa-id-badge text-success"></i>
                        Client ID: <strong>#{{ $client->id }}</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div> -->

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-exclamation-circle text-danger" style="font-size: 48px;"></i>
                </div>
                <p class="text-center">
                    Apakah Anda yakin ingin menghapus client<br>
                    <strong class="text-danger">{{ $client->nama }}</strong>?
                </p>
                <p class="text-center text-muted small">
                    <i class="fas fa-info-circle"></i>
                    Data yang dihapus tidak dapat dikembalikan.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Batal
                </button>
                <form action="{{ route('data-client.destroy', $client->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .btn-block {
        display: block;
        width: 100%;
    }
    .ms-2 {
        margin-left: 0.5rem;
    }
</style>
@endpush
@endsection
