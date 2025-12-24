@extends('layouts.app')

@section('content')


<div class="container-fluid">
    <!-- Header -->
  

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Klien</h6>
            
            <!-- Pencarian -->
            <form action="{{ route('data-client.index') }}" method="GET" class="d-flex">
                <div class="input-group">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Cari klien..." 
                           value="{{ request('search') }}"
                           style="width: 300px;">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                        @if(request('search'))
                            <a href="{{ route('data-client.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Tabel Klien -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Brand</th>
                            <th>Nomor Telepon</th>
                            <th>Kategori</th>
                            <th>Notes</th>
                            <th>Status</th>
                            <th>Tanggal Bergabung</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $client)
                            <tr>
                                <td>{{ ($clients->currentPage() - 1) * $clients->perPage() + $loop->iteration }}</td>
                                <td>{{ $client->nama_brand }}</td>
                                <td>{{ $client->phone }}</td>
                                <td>{{ $client->category }}</td>
                                <td>{{ $client->notes }}</td>
                             
                                <td>
                                    <span class="badge badge-{{ $client->status == 'active' ? 'active' : 'inactive' }}">
                                        {{ ucfirst($client->status) }}
                                    </span>
                                </td>
                                <td>{{ $client->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <!-- Tombol Lihat -->
                                        <a href="{{ route('data-client.show', $client->id) }}" 
                                           class="btn btn-info btn-sm" 
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('data-client.edit', $client->id) }}" 
                                           class="btn btn-warning btn-sm" 
                                           title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        
                                        <!-- Tombol Hapus -->
                                        <button type="button" 
                                                class="btn btn-danger btn-sm" 
                                                title="Hapus"
                                                data-toggle="modal" 
                                                data-target="#deleteModal{{ $client->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Modal Konfirmasi Hapus -->
                                    <div class="modal fade" id="deleteModal{{ $client->id }}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Apakah Anda yakin ingin menghapus klien 
                                                    <strong>{{ $client->nama }}</strong>?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <form action="{{ route('data-client.destroy', $client->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    @if(request('search'))
                                        Tidak ada klien yang sesuai dengan pencarian
                                    @else
                                        Belum ada data klien
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

           <!-- Pagination -->
@if($clients->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted">
            Menampilkan {{ $clients->firstItem() }} - {{ $clients->lastItem() }} dari {{ $clients->total() }} klien
        </div>
        <nav aria-label="Page navigation">
            {{ $clients->withQueryString()->links('pagination.bootstrap-4') }}
        </nav>
    </div>
@endif

@push('styles')
<style>
    .btn-group .btn {
        margin-right: 5px;
    }
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    .table th {
        white-space: nowrap;
    }
    .badge {
        font-size: 0.85em;
        padding: 0.4em 0.8em;
    }
</style>
@endpush
@endsection