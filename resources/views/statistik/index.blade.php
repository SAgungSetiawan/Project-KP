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
                            Rata-rata {{ $data['month_name'] }} {{ $data['year'] }}
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
    {{ $data['average_monthly'] }}
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

    <!-- Tertinggi (Tanggal) -->
    <div class="col-md-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Tertinggi (Tanggal)
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
    {{ $data['highest_month']['total'] }}
</div>
<div class="text-xs text-muted">
    {{ $data['highest_month']['month'] }}
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

    <div class="col-md-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                    Total {{ $data['year'] }}
                </div>
                <div class="h5 font-weight-bold text-gray-800">
                    {{ $data['total'] }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                    Rata-rata per Bulan
                </div>
                <div class="h5 font-weight-bold text-gray-800">
                    {{ $data['average'] }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                    Tertinggi
                </div>
                <div class="h5 font-weight-bold text-gray-800">
                    {{ $data['max'] }}
                </div>
                <div class="text-xs text-muted">
                    {{ $data['highest_month'] }}
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
 <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
let statisticsChart;

document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('statisticsChart').getContext('2d');
    const chartData = @json($chartData);

    chartData.datasets.forEach(ds => {
        ds.fill = true;
        ds.tension = 0.35;
        ds.pointRadius = 4;
        ds.pointHoverRadius = 6;
        ds.borderWidth = 2;
    });

    statisticsChart = new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' },
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    formatter: v => v > 0 ? v : ''
                }
            },
            scales: {
                y: { beginAtZero: true }
            }
        },
        plugins: [ChartDataLabels]
    });
});



document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('statisticsChart').getContext('2d');
    const chartData = @json($chartData);

    chartData.datasets.forEach(ds => {
        ds.fill = true;
        ds.tension = 0.35;
        ds.pointRadius = 4;
        ds.pointHoverRadius = 6;
        ds.borderWidth = 2;
    });

    new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                },

                // 🔥 INI BAGIAN PENTING
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    offset: 4,
                    formatter: function(value) {
                        // biar 0 ga ditampilin
                        return value > 0 ? value : '';
                    },
                    font: {
                        weight: 'bold',
                        size: 11
                    },
                    color: '#333'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        stepSize: 1
                    }
                }
            }
        },

        // 🔥 WAJIB: daftarin pluginnya
        plugins: [ChartDataLabels]
    });
});
</script>

<script>
function exportChartAsPNG() {
    const link = document.createElement('a');
    link.download = 'statistik-klien.png';
    link.href = statisticsChart.toBase64Image();
    link.click();
}
</script>
<script>
function exportDataAsCSV() {
    const chartData = @json($chartData);

    let csv = 'Label,' + chartData.labels.join(',') + '\n';

    chartData.datasets.forEach(ds => {
        csv += ds.label + ',' + ds.data.join(',') + '\n';
    });

    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);

    const link = document.createElement('a');
    link.href = url;
    link.download = 'statistik-klien.csv';
    link.click();
}
</script>


@endpush
@endsection