@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Detail Client</h3>
            <p class="text-muted mb-0">Informasi lengkap data client</p>
        </div>
        <div>
            <a href="{{ route('data-client.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('data-client.edit', $client->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
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
                    <span class="badge {{ $client->status == 'active' ? 'badge-success' : 'badge-secondary' }}">
                        {{ ucfirst($client->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td width="30%"><strong><i class="fas fa-user text-primary"></i> Nama Lengkap</strong></td>
                                <td>: {{ $client->nama }}</td>
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
                                    @if($client->telepon)
                                        <a href="tel:{{ $client->telepon }}">{{ $client->telepon }}</a>
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $client->telepon) }}" 
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
                                <td><strong><i class="fas fa-building text-warning"></i> Perusahaan</strong></td>
                                <td>: {{ $client->perusahaan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong><i class="fas fa-map-marker-alt text-danger"></i> Alamat</strong></td>
                                <td>: {{ $client->alamat ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong><i class="fas fa-toggle-on text-primary"></i> Status</strong></td>
                                <td>: 
                                    <span class="badge {{ $client->status == 'active' ? 'badge-success' : 'badge-secondary' }}">
                                        {{ ucfirst($client->status) }}
                                    </span>
                                </td>
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
                        <strong>{{ $client->updated_at->format('d F Y H:i') }}</strong>
                        <br>
                        <small class="text-muted">{{ $client->updated_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>

            <!-- Card Aksi Cepat -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt"></i> Aksi Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('data-client.edit', $client->id) }}" 
                       class="btn btn-warning btn-block mb-2">
                        <i class="fas fa-edit"></i> Edit Data
                    </a>

                    <form action="{{ route('data-client.update-status', $client->id) }}" method="POST" class="mb-2">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $client->status == 'active' ? 'inactive' : 'active' }}">
                        <button type="submit" class="btn btn-info btn-block">
                            <i class="fas fa-sync-alt"></i>
                            {{ $client->status == 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>

                    <button type="button" 
                            class="btn btn-danger btn-block" 
                            data-toggle="modal" 
                            data-target="#deleteModal">
                        <i class="fas fa-trash"></i> Hapus Client
                    </button>
                </div>
            </div>

            <!-- Card Statistik -->
            <div class="card shadow">
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
</div>

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
