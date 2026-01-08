@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="task-force">
                <div class="stat-number">{{ $totalClients ?? $stats['total_clients'] ?? 0 }}</div>
                <div class="stat-label">Total Klien</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="task-force">
                <div class="stat-number">{{ $newClientsThisMonth ?? $stats['new_this_month'] ?? 0 }}</div>
                <div class="stat-label">Klien Baru ({{ now()->translatedFormat('F') }})</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="task-force">
                <div class="stat-number">{{ $activeClients ?? $stats['active_clients'] ?? 0 }}</div>
                <div class="stat-label">Klien Aktif</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="task-force">
                <div class="stat-number">{{ $InactiveClients ?? $stats['Inactive_clients'] ?? 0 }}</div>
                <div class="stat-label">Klien Non Aktif</div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row">
        <!-- Monthly Chart -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Statistik Bulanan ({{ date('Y') }})
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>

 <!-- Recent Client -->
<div class="col-md-6">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                Recent Client 
            </h6>
            <span class="badge badge-primary">{{ $recentClients->count() }} Clients</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentClients as $client)
                        <tr>
                            <td>
                                <div class="font-weight-bold">{{ $client->name }}</div>
                                <small class="text-muted">{{ Str::limit($client->email, 20) }}</small>
                            </td>
                            <td>
                                <span class="font-weight-bold">{{ $client->category }}</span>
                            </td>
                            <td>
                                <small>{{ \Carbon\Carbon::parse($client->join_date)->format('d/m/Y') }}</small>
                            </td>
                            <td>
                                <span class="badge badge-{{ $client->status == 'active' ? 'active' : 'inactive' }}">
                                    {{ $client->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-center mt-9">
                <a href="{{ route('data-client.index') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-list mr-1"></i> Lihat Semua Client
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-xl shadow p-6 mb-8">
    <h2 class="text-xl font-bold mb-4">Quick Actions</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('add-clients.create') }}" 
           class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-indigo-500 hover:bg-indigo-50 transition">
            <i class="fas fa-plus text-3xl text-gray-400 mb-2"></i>
            <p class="font-semibold">Tambah Client Baru</p>
            <p class="text-sm text-gray-500">Tambahkan client baru</p>
        </a>
        
        <a href="{{ route('data-client.index') }}" 
           class="border-2 border-gray-300 rounded-lg p-6 text-center hover:border-indigo-500 hover:bg-indigo-50 transition">
            <i class="fas fa-list text-3xl text-gray-400 mb-2"></i>
            <p class="font-semibold">Kelola Data Client</p>
            <p class="text-sm text-gray-500">Lihat dan edit semua client</p>
        </a>
        
        <a href="{{ route('statistik.index') }}" 
           class="border-2 border-gray-300 rounded-lg p-6 text-center hover:border-indigo-500 hover:bg-indigo-50 transition">
            <i class="fas fa-chart-bar text-3xl text-gray-400 mb-2"></i>
            <p class="font-semibold">Lihat Statistik</p>
            <p class="text-sm text-gray-500">Analisis pertumbuhan dan performa</p>
        </a>
    </div>
</div>


<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    
    // Data dari controller - pastikan tidak ada nilai minus
    const monthNames = @json(array_column($monthlyStats, 'name'));
    const monthTotals = @json(array_column($monthlyStats, 'total')).map(total => Math.max(0, total));
    
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: monthNames,
            datasets: [{
                label: 'Klien Baru',
                data: monthTotals,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    min: 0, // PASTIKAN MINIMUM 0
                    ticks: {
                        // Pastikan tidak menampilkan nilai minus
                        callback: function(value) {
                            return value >= 0 ? value : 0;
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            // Pastikan tooltip tidak menampilkan nilai minus
                            const value = Math.max(0, context.parsed.y);
                            return `Klien Baru: ${value}`;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection