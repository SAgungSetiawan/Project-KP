{{-- resources/views/dashboard/statistics-monthly.blade.php --}}
@extends('layouts.app')

@section('title', 'Statistik Bulanan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">📊 Statistik Perkembangan Klien</h3>
        <p class="text-muted mb-0">Analisis perkembangan klien per bulan</p>
    </div>
</div>

<!-- Filter Section -->
<div class="card-orrea mb-4">
    <div class="card-body">
        <form action="{{ route('dashboard.statistics') }}" method="GET" class="row g-3">
            <input type="hidden" name="type" value="monthly">
            <div class="col-md-4">
                <label class="form-label">Tahun</label>
                <select name="year" class="form-select" onchange="this.form.submit()">
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Bulan</label>
                <select name="month" class="form-select" onchange="this.form.submit()">
                    @php
                        $monthNames = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                    @endphp
                    @foreach($monthNames as $key => $name)
                        <option value="{{ $key }}" {{ $month == $key ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-orrea me-2">Terapkan</button>
                <a href="{{ route('dashboard.statistics', ['type' => 'yearly', 'year' => $year]) }}" 
                   class="btn btn-outline-secondary">
                    Lihat Tahunan
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Chart -->
<div class="chart-container mb-4">
    <h5 class="mb-4">Grafik Pertumbuhan Klien {{ $data['month'] }} {{ $data['year'] }}</h5>
    <canvas id="monthlyChart" height="100"></canvas>
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
            <div class="stat-number text-orrea-primary">{{ $data['summary']['daily_avg'] }}</div>
            <div class="stat-label">Rata-rata per Hari</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="bg-orrea-light p-3 rounded text-center">
            <div class="stat-number text-orrea-primary">{{ $data['summary']['highest_value'] }}</div>
            <div class="stat-label">Tertinggi (tgl {{ $data['summary']['highest_day'] }})</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="bg-orrea-light p-3 rounded text-center">
            <div class="stat-number text-orrea-primary">{{ $data['summary']['achievement'] }}%</div>
            <div class="stat-label">Pencapaian Target</div>
        </div>
    </div>
</div>

<!-- Detailed Table -->
<div class="card-orrea">
    <div class="card-body">
        <h5 class="card-title mb-3">Detail Per Tanggal - {{ $data['month'] }} {{ $data['year'] }}</h5>
        <div class="row">
            @foreach(array_chunk($data['days'], 7, true) as $week)
            <div class="col-md-4 mb-3">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Klien Baru</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($week as $day)
                            <tr class="{{ $day['new_clients'] == 0 ? 'table-secondary' : '' }}">
                                <td>{{ $day['day'] }} {{ $data['month'] }}</td>
                                <td class="text-center">
                                    @if($day['new_clients'] > 0)
                                        <span class="badge bg-success">{{ $day['new_clients'] }}</span>
                                    @else
                                        <span class="text-muted">0</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Summary -->
        <div class="alert alert-info mt-3">
            <h6>📋 Summary Bulan {{ $data['month'] }}:</h6>
            <ul class="mb-0">
                <li>Total klien baru: <strong>{{ $data['summary']['total_new'] }}</strong> klien</li>
                <li>Hari dengan klien terbanyak: <strong>Tanggal {{ $data['summary']['highest_day'] }}</strong> ({{ $data['summary']['highest_value'] }} klien)</li>
                <li>Hari tanpa klien baru: <strong>{{ $data['summary']['zero_days'] }}</strong> hari</li>
                <li>Target bulanan: <strong>50 klien</strong> (Tercapai: {{ $data['summary']['achievement'] }}%)</li>
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script>
const monthlyData = @json($data);
const days = [];
const clients = [];

// Siapkan data untuk chart (hanya 31 hari pertama)
for (let i = 1; i <= 31; i++) {
    if (monthlyData.days[i]) {
        days.push(monthlyData.days[i].day);
        clients.push(monthlyData.days[i].new_clients);
    } else {
        break;
    }
}

const ctx = document.getElementById('monthlyChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: days,
        datasets: [{
            label: 'Klien Baru',
            data: clients,
            backgroundColor: 'rgba(44, 90, 160, 0.1)',
            borderColor: 'rgba(44, 90, 160, 1)',
            borderWidth: 2,
            tension: 0.3,
            fill: true
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
                title: {
                    display: true,
                    text: 'Tanggal'
                },
                grid: {
                    display: false
                }
            }
        }
    }
});
</script>
@endpush
@endsection