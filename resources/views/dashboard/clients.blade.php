{{-- resources/views/dashboard/clients.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Client')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Data Client</h3>
        <p class="text-muted mb-0">Kelola semua data klien Anda</p>
    </div>
    <a href="{{ route('dashboard.add-client') }}" class="btn btn-orrea">
        <i class="bi bi-person-plus me-2"></i> Tambah Client
    </a>
</div>

<!-- Search and Filter -->
<div class="card-orrea mb-4">
    <div class="card-body">
        <form action="{{ route('dashboard.clients') }}" method="GET" class="row g-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" name="search" 
                           placeholder="Cari nama, telepon, atau email..." 
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>Baru</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-orrea w-100">Cari</button>
            </div>
        </form>
    </div>
</div>

<!-- Clients Table -->
<div class="card-orrea">
    <div class="card-body">
        <div class="table-client">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Telepon</th>
                            <th>Email</th>
                            <th>Kategori</th>
                            <th>Tanggal Daftar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $client)
                        <tr>
                            <td>#{{ str_pad($client->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->phone ?? '-' }}</td>
                            <td>{{ $client->email ?? '-' }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $client->category }}</span>
                            </td>
                            <td>{{ $client->join_date->format('d M Y') }}</td>
                            <td>
                                @if($client->status == 'new')
                                    <span class="badge badge-new">Baru</span>
                                @elseif($client->status == 'active')
                                    <span class="badge badge-active">Aktif</span>
                                @else
                                    <span class="badge badge-inactive">Non-Aktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('dashboard.edit-client', $client->id) }}" 
                                       class="btn btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('dashboard.delete-client', $client->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" 
                                                onclick="return confirm('Hapus client ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="bi bi-people display-4 text-muted mb-3"></i>
                                <p class="text-muted">Belum ada data client</p>
                                <a href="{{ route('dashboard.add-client') }}" class="btn btn-orrea">
                                    Tambah Client Pertama
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($clients->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $clients->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection