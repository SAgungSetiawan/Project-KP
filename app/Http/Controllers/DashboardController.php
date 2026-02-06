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
       $activeClients = Client::all()
    ->filter(fn ($c) => $c->auto_status === 'aktif')
    ->count();


        $InactiveClients = Client::whereIn('status', ['non aktif', 'belum aktif'])->count();

        
        // Klien Baru bulan ini - AMBIL dari start_date
        $newClientsThisMonth = Client::whereMonth('start_date', date('m'))
            ->whereYear('start_date', date('Y'))
            ->count();
        
        // Klien Baru (dalam 30 hari) - AMBIL dari start_date
        $newClients = Client::where('created_at', '>=', now()->subDays(30))->count();
        
        // Statistik bulanan - AMBIL dari start_date TANPA MINUS
        $monthlyStats = $this->getMonthlyStats();
        
        // Recent Clients (5 data terbaru berdasarkan start_date)
        $recentClients = Client::orderBy('created_at', 'desc')
            ->take(2)
            ->get(['id', 'name','nama_brand', 'email', 'category', 'created_at', 'status']);
        
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
        
        // Ambil data dari database - GUNAKAN start_date
        $data = Client::select(
                DB::raw('MONTH(start_date) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('start_date', $year)
            ->whereNotNull('start_date') // Pastikan start_date tidak null
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