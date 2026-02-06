@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
        <!-- <a href="{{ route('data-client.index') }}" class="btn btn-secondary me-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a> -->
        <h1 class="h3 mb-0 text-gray-800 ">Tambah Client Baru</h1>
    </div>

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Client</h6>
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

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route('add-clients.store') }}" 
      method="POST" 
      enctype="multipart/form-data">

                @csrf
                
                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <!-- Nama -->
                        <div class="form-group">
                            <label for="name">Nama Client</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nama Brand -->
                        <div class="form-group">
                            <label for="name">Nama Brand <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="nama_brand" 
                                   name="nama_brand" 
                                   value="{{ old('nama_brand') }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Telepon -->
                        <div class="form-group">
                            <label for="phone">Telepon <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}"
                                   required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        

                        <!-- Kategori -->
                        <div class="form-group">
                            <label for="category">Kategori <span class="text-danger">*</span></label>
                            <select class="form-control @error('category') is-invalid @enderror" 
                                    id="category" 
                                    name="category" 
                                    required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" 
                                            {{ old('category') == $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <!-- Alamat -->
                        <div class="form-group">
                            <label for="address">Alamat</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="1">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tanggal Bergabung -->
                        <div class="form-group">
                            <label for="start_date">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" 
                                   name="start_date" 
                                   value="{{ old('start_date', date('Y-m-d')) }}"
                                   required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Durasi Langganan -->
<div class="form-group">
    <label for="duration">Durasi Langganan <span class="text-danger">*</span></label>
    <select class="form-control @error('duration') is-invalid @enderror"
            id="duration"
            name="duration"
            required>
        <option value="">Pilih Durasi</option>
        <option value="1" {{ old('duration') == 1 ? 'selected' : '' }}>1 Bulan</option>
        <option value="2" {{ old('duration') == 2 ? 'selected' : '' }}>2 Bulan</option>
        <option value="3" {{ old('duration') == 3 ? 'selected' : '' }}>3 Bulan</option>
        <option value="6" {{ old('duration') == 6 ? 'selected' : '' }}>6 Bulan</option>
        <option value="12" {{ old('duration') == 12 ? 'selected' : '' }}>12 Bulan</option>
    </select>
    @error('duration')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                        

                    

                        <!-- Catatan -->
                        <div class="form-group">
                            <label for="notes">Catatan</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="1">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Upload Invoice -->
<div class="form-group">
    <label for="invoice_file">Upload Invoice</label>
    <input type="file"
           class="form-control @error('invoice_file') is-invalid @enderror"
           id="invoice_file"
           name="invoice_file"
           accept=".pdf,.jpg,.jpeg,.png">
    @error('invoice_file')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="text-muted">
        Format: PDF, JPG, PNG (Max 2MB)
    </small>
</div>

                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Client
                            </button>
                            <button type="reset" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .required-field::after {
        content: " *";
        color: red;
    }
    .form-group label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
    // Set tanggal hari ini sebagai default jika belum diisi
    document.addEventListener('DOMContentLoaded', function() {
        const joinDateInput = document.getElementById('start_date');
        if (!joinDateInput.value) {
            joinDateInput.value = new Date().toISOString().split('T')[0];
        }
    });
</script>
@endpush
@endsection