@extends('layouts.app')

@section('title', 'Statistik Klien')
@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Statistik Klien</h1>
            <p class="text-muted mb-0">Analisis data klien berdasarkan periode</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pilih Tampilan Statistik</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('statistik.index') }}" method="GET" id="statisticsForm">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Tipe Statistik</label>
                        <select name="view_type" class="form-select" id="viewType" onchange="toggleFilters()">
                            <option value="monthly" {{ $viewType == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                            <option value="yearly" {{ $viewType == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                        </select>
                    </div>

                    <!-- Month Filter (visible when monthly is selected) -->
                    <div class="col-md-4 mb-3" id="monthFilter">
                        <label class="form-label fw-bold">Pilih Bulan</label>
                        <select name="month" class="form-select">
                            @foreach($months as $key => $name)
                                <option value="{{ $key }}" {{ $month == $key ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Year Filter (always visible) -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Pilih Tahun</label>
                        <select name="year" class="form-select">
                            @foreach($years as $y)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-chart-bar me-2"></i> Tampilkan Statistik
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="resetFilters()">
                            <i class="fas fa-redo me-2"></i> Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    @if($viewType == 'monthly')
<div class="row mb-4">

    <!-- Total Bulan Ini -->
    <div class="col-md-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total {{ $data['month_name'] }} {{ $data['year'] }}
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $data['total'] ?? 0 }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rata-rata Tiap Bulan -->
    <div class="col-md-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Rata-rata Tiap Bulan
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $data['average_monthly'] ?? 0 }}
                        </div>
                        <div class="text-xs text-muted">
                            Rata-rata klien per bulan ({{ $data['year'] }})
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tertinggi (Bulan) -->
    <div class="col-md-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Tertinggi (Bulan)
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $data['highest_month']['total'] ?? 0 }}
                        </div>
                        <div class="text-xs text-muted">
                            {{ $data['highest_month']['month'] ?? '-' }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-trophy fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endif

    @if($viewType == 'yearly')
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total {{ $data['year'] }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $data['total'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Rata-rata per Bulan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $data['average'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Tertinggi (per bulan)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $data['max'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-trophy fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Bulan dengan Data
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ collect($data['months'] ?? [])->where('count', '>', 0)->count() }}
                            </div>
                            <div class="text-xs text-muted">
                                dari 12 bulan
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Chart Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                @if($viewType == 'monthly')
                    Grafik Statistik Bulan {{ $data['month_name'] ?? '' }} {{ $data['year'] ?? '' }}
                @else
                    Grafik Statistik Tahun {{ $data['year'] ?? '' }}
                @endif
            </h6>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-download me-1"></i> Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="exportChartAsPNG()">Download PNG</a></li>
                    <li><a class="dropdown-item" href="#" onclick="exportDataAsCSV()">Download CSV</a></li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="chart-container" style="height: 400px;">
                <canvas id="statisticsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                @if($viewType == 'monthly')
                    Detail Data Bulan {{ $data['month_name'] ?? '' }} {{ $data['year'] ?? '' }}
                @else
                    Detail Data Tahun {{ $data['year'] ?? '' }}
                @endif
            </h6>
        </div>
        <div class="card-body">
            @if($viewType == 'monthly')
            <div class="row">
                @php
                    $days = $data['days'] ?? [];
                    $chunks = array_chunk($days, 10, true);
                @endphp
                
                @foreach($chunks as $chunkIndex => $chunk)
                <div class="col-md-6 mb-4">
                    <div class="card border-light">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Hari {{ ($chunkIndex * 10) + 1 }} - {{ ($chunkIndex * 10) + count($chunk) }}</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="40%">Tanggal</th>
                                            <th width="30%">Klien Baru</th>
                                            <th width="30%">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($chunk as $index => $day)
                                        @php
                                            $dayNumber = ($chunkIndex * 10) + $index + 1;
                                        @endphp
                                        <tr class="{{ $day['is_weekend'] ? 'table-warning' : '' }}">
                                            <td>
                                                <i class="fas fa-calendar-day me-2 text-muted"></i>
                                                {{ $dayNumber }} {{ $data['month_name'] }}
                                                @if($day['is_weekend'])
                                                    <span class="badge bg-warning ms-2">Weekend</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($day['count'] > 0)
                                                    <span class="badge bg-success p-2">{{ $day['count'] }}</span>
                                                @else
                                                    <span class="text-muted">0</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($day['count'] > 0)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i> Ada Data
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-times me-1"></i> Tidak Ada
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            @if($viewType == 'yearly')
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Bulan</th>
                            <th>Jumlah Klien Baru</th>
                            <th>Persentase</th>
                            <th>Trend</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['months'] ?? [] as $month)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="month-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                         style="width: 40px; height: 40px;">
                                        {{ $loop->iteration }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $month['month'] }}</div>
                                        <small class="text-muted">Bulan ke-{{ $loop->iteration }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold fs-5">{{ $month['count'] }}</div>
                            </td>
                            <td>
                                @php
                                    $percentage = $data['total'] > 0 ? ($month['count'] / $data['total']) * 100 : 0;
                                @endphp
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: {{ min(100, $percentage) }}%"
                                         aria-valuenow="{{ $percentage }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        {{ number_format($percentage, 1) }}%
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($loop->index > 0)
                                    @php
                                        $prevCount = $data['months'][$loop->index - 1]['count'];
                                        $diff = $month['count'] - $prevCount;
                                    @endphp
                                    @if($diff > 0)
                                        <span class="badge bg-success">
                                            <i class="fas fa-arrow-up me-1"></i> +{{ $diff }}
                                        </span>
                                    @elseif($diff < 0)
                                        <span class="badge bg-danger">
                                            <i class="fas fa-arrow-down me-1"></i> {{ $diff }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-minus me-1"></i> Sama
                                        </span>
                                    @endif
                                @else
                                    <span class="badge bg-info">Awal Tahun</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.chart-container {
    position: relative;
}
.month-icon {
    font-weight: bold;
    font-size: 16px;
}
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}
.progress {
    border-radius: 10px;
    overflow: hidden;
}
</style>
@endpush

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('statisticsChart').getContext('2d');
    const chartData = @json($chartData);

    // Paksa dataset agar cocok untuk LINE chart
    chartData.datasets.forEach(dataset => {
        dataset.fill = true;
        dataset.tension = 0.3;
        dataset.pointRadius = 4;
        dataset.pointHoverRadius = 6;
        dataset.borderWidth = 2;

        // Kalau sebelumnya backgroundColor array (bar), ubah jadi satu warna
        if (Array.isArray(dataset.backgroundColor)) {
            dataset.backgroundColor = 'rgba(75, 192, 192, 0.15)';
            dataset.borderColor = 'rgb(75, 192, 192)';
        }
    });

    new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = Math.max(0, context.parsed.y);
                            return `Klien Baru: ${value}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    min: 0,
                    ticks: {
                        precision: 0
                    },
                    title: {
                        display: true,
                        text: 'Jumlah Klien Baru'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: @if($viewType == 'monthly') 'Hari' @else 'Bulan' @endif
                    }
                }
            }
        }
    });
});
</script>

@endpush
@endsection