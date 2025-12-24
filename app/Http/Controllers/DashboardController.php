<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Klien
        $totalClients = Client::count();
        
        // Klien Aktif
        $activeClients = Client::where('status', 'active')->count();
        $InactiveClients = Client::where('status', 'non active')->count();
        
        // Klien Baru bulan ini - AMBIL dari join_date
        $newClientsThisMonth = Client::whereMonth('join_date', date('m'))
            ->whereYear('join_date', date('Y'))
            ->count();
        
        // Klien Baru (dalam 30 hari) - AMBIL dari join_date
        $newClients = Client::where('join_date', '>=', now()->subDays(30))->count();
        
        // Statistik bulanan - AMBIL dari join_date TANPA MINUS
        $monthlyStats = $this->getMonthlyStats();
        
        // Recent Clients (5 data terbaru berdasarkan join_date)
        $recentClients = Client::orderBy('join_date', 'desc')
            ->take(2)
            ->get(['id', 'name', 'email', 'category', 'join_date', 'status']);
        
        return view('dashboard.index', compact(
            'totalClients',
            'activeClients',
            'newClientsThisMonth',
            'InactiveClients',
            'newClients',
            'monthlyStats',
            'recentClients'
        ));
    }
    
    private function getMonthlyStats()
    {
        $year = date('Y');
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Des'];
        
        $monthlyStats = [];
        
        // Ambil data dari database - GUNAKAN join_date
        $data = Client::select(
                DB::raw('MONTH(join_date) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('join_date', $year)
            ->whereNotNull('join_date') // Pastikan join_date tidak null
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');
        
        // Format data untuk chart - PASTIKAN TIDAK ADA MINUS
        foreach ($months as $index => $monthName) {
            $monthNumber = $index + 1;
            $total = isset($data[$monthNumber]) ? $data[$monthNumber]->total : 0;
            
            // PASTIKAN NILAI TIDAK MINUS - pakai max(0, $total)
            $total = max(0, $total);
            
            // Jika ada data aneh yang menyebabkan minus, kita set ke 0
            if ($total < 0) {
                $total = 0;
            }
            
            $monthlyStats[] = [
                'name' => $monthName,
                'total' => $total
            ];
        }
        
        return $monthlyStats;
    }
}