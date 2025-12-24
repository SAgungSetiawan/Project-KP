{{-- resources/views/dashboard/statistics-yearly.blade.php --}}
@extends('layouts.app')

@section('title', 'Statistik Tahunan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">📊 Statistik Perkembangan Klien</h3>
        <p class="text-muted mb-0">Analisis perkembangan klien per tahun</p>
    </div>
</div>

<!-- Filter Section -->
<div class="card-orrea mb-4">
    <div class="card-body">
        <form action="{{ route('dashboard.statistics') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Tampilan</label>
                <select name="type" class="form-select" onchange="this.form.submit()">
                    <option value="yearly" {{ $type == 'yearly' ? 'selected' : '' }}>Tahun Ini</option>
                    <option value="monthly" {{ $type == 'monthly' ? 'selected' : '' }}>Bulan Ini</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tahun</label>
                <select name="year" class="form-select" onchange="this.form.submit()">
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <button type="submit" class="btn btn-orrea me-2">Terapkan</button>
                <button type="button" class="btn btn-outline-secondary me-2" onclick="printChart()">
                    <i class="bi bi-printer me-1"></i> Print
                </button>
                <a href="{{ route('dashboard.add-client') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-person-plus me-1"></i> Tambah Client
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Chart -->
<div class="chart-container mb-4">
    <h5 class="mb-4">Grafik Pertumbuhan Klien Tahun {{ $data['year'] }}</h5>
    <canvas id="statisticsChart" height="100"></canvas>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="bg-orrea-light p-3 rounded text-center">
            <div class="stat-number text-orrea-primary">{{ $data['summary']['total_new'] }}</div>
            <div class="stat-label">Total Klien Baru</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="bg-orrea-light p-3 rounded text-center">
            <div class="stat-number text-orrea-primary">{{ $data['summary']['monthly_avg'] }}</div>
            <div class="stat-label">Rata-rata per Bulan</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="bg-orrea-light p-3 rounded text-center">
            <div class="stat-number text-orrea-primary">{{ $data['summary']['highest_value'] }}</div>
            <div class="stat-label">Tertinggi ({{ $data['summary']['highest_month'] }})</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="bg-orrea-light p-3 rounded text-center">
            <div class="stat-number text-orrea-primary">
                @if($data['summary']['trend'] == 'up')
                    ↑ Meningkat
                @else
                    → Stabil
                @endif
            </div>
            <div class="stat-label">Trend Pertumbuhan</div>
        </div>
    </div>
</div>

<!-- Detailed Table -->
<div class="card-orrea">
    <div class="card-body">
        <h5 class="card-title mb-3">Detail Per Bulan</h5>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Klien Baru</th>
                        <th>Total Akumulasi</th>
                        <th>Progress</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['months'] as $month)
                        @if($month['name'])
                            <tr>
                                <td><strong>{{ $month['name'] }}</strong></td>
                                <td>{{ $month['new_clients'] }}</td>
                                <td>{{ $month['total'] }}</td>
                                <td>
                                    @php
                                        $percentage = $data['summary']['total_new'] > 0 
                                            ? round(($month['new_clients'] / $data['summary']['total_new']) * 100)
                                            : 0;
                                    @endphp
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-orrea-primary" 
                                             style="width: {{ $percentage }}%">
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $percentage }}%</small>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-active">
                        <td><strong>TOTAL</strong></td>
                        <td><strong>{{ $data['summary']['total_new'] }} klien</strong></td>
                        <td><strong>{{ end($data['months'])['total'] ?? 0 }} klien</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
const statsData = @json($data);
const months = [];
const newClients = [];

for (let i = 1; i <= 12; i++) {
    if (statsData.months[i]) {
        months.push(statsData.months[i].name);
        newClients.push(statsData.months[i].new_clients);
    }
}

const ctx = document.getElementById('statisticsChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: 'Klien Baru',
            data: newClients,
            backgroundColor: 'rgba(44, 90, 160, 0.7)',
            borderColor: 'rgba(44, 90, 160, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Jumlah Klien Baru'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

function printChart() {
    window.print();
}
</script>
@endpush
@endsection