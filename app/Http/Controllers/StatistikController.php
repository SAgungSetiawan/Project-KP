<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatistikController extends Controller
{
    /**
     * Menampilkan halaman statistik dengan filter
     */
    public function index(Request $request)
{
    $viewType = $request->get('view_type', 'monthly');
    $year = (int) $request->get('year', date('Y'));
    $month = (int) $request->get('month', date('m'));

    $years = range(date('Y') - 5, date('Y'));
    rsort($years);

    $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    if ($viewType === 'monthly') {
        $data = $this->getMonthlyStatistics($year, $month);
        $chartData = $this->prepareMonthlyChartData($data);

        $monthName = $months[$month] ?? 'Bulan Tidak Valid';
        $title = "Statistik Bulanan - {$monthName} {$year}";

        } elseif ($viewType === 'yearly') {
            $data = $this->getYearlyStatistics($year);
            $chartData = $this->prepareYearlyChartData($data);
            $title = "Statistik Tahunan - {$year}";
        }
        
        return view('statistik.index', compact(
            'viewType', 'year', 'month', 'data', 'chartData', 
            'years', 'months', 'title'
        ));
    }
    
    /**
     * Ambil data statistik bulanan
     */
    private function getMonthlyStatistics($year, $month)
    {
        $month = (int) $month;
        
        // Ambil jumlah hari dalam bulan
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        
        // Query data klien per hari dalam bulan tertentu
        $dailyStats = Client::select(
                DB::raw('DAY(join_date) as day'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('join_date', $year)
            ->whereMonth('join_date', $month)
            ->whereNotNull('join_date')
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->keyBy('day');
        
        // Siapkan array untuk semua hari dalam bulan
        $monthData = [];
        $totalClients = 0;
        $daysWithData = 0;
        $maxPerDay = 0;
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $count = isset($dailyStats[$day]) ? $dailyStats[$day]->total : 0;
            $totalClients += $count;
            
            if ($count > 0) {
                $daysWithData++;
            }
            
            if ($count > $maxPerDay) {
                $maxPerDay = $count;
            }
            
            $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $isWeekend = date('N', strtotime($date)) >= 6;
            
            $monthData[] = [
                'day' => $day,
                'date' => $date,
                'date_formatted' => sprintf('%02d', $day),
                'count' => $count,
                'is_weekend' => $isWeekend,
                'is_today' => ($year == date('Y') && $month == date('m') && $day == date('d'))
            ];
        }
        
        // Hitung rata-rata
        $averagePerDay = $daysInMonth > 0 ? round($totalClients / $daysInMonth, 2) : 0;
        $averageOnDaysWithData = $daysWithData > 0 ? round($totalClients / $daysWithData, 2) : 0;
        
        // Bulan dalam bahasa Indonesia
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        return [
            'month' => $month,
            'month_name' => $monthNames[$month] ?? 'Unknown',
            'year' => $year,
            'days_in_month' => $daysInMonth,
            'total_clients' => $totalClients,
            'average_per_day' => $averagePerDay,
            'average_on_days_with_data' => $averageOnDaysWithData,
            'max_per_day' => $maxPerDay,
            'days_with_data' => $daysWithData,
            'days_without_data' => $daysInMonth - $daysWithData,
            'daily_data' => $monthData
        ];
    }
    
    /**
     * Persiapkan data chart untuk bulanan
     */
    private function prepareMonthlyChartData($data)
    {
        $labels = [];
        $values = [];
        $backgroundColors = [];
        $borderColors = [];
        
        foreach ($data['daily_data'] as $day) {
            $labels[] = $day['date_formatted'];
            $values[] = $day['count'];
            
            // Warna berbeda untuk weekend
            if ($day['is_weekend']) {
                $backgroundColors[] = 'rgba(255, 159, 64, 0.5)'; // Orange for weekend
                $borderColors[] = 'rgba(255, 159, 64, 1)';
            } elseif ($day['is_today']) {
                $backgroundColors[] = 'rgba(75, 192, 192, 0.5)'; // Teal for today
                $borderColors[] = 'rgba(75, 192, 192, 1)';
            } else {
                $backgroundColors[] = 'rgba(54, 162, 235, 0.5)'; // Blue for weekdays
                $borderColors[] = 'rgba(54, 162, 235, 1)';
            }
        }
        
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Klien Baru',
                    'data' => $values,
                    'backgroundColor' => $backgroundColors,
                    'borderColor' => $borderColors,
                    'borderWidth' => 1
                ]
            ]
        ];
    }
    
    /**
     * Ambil data statistik tahunan
     */
    private function getYearlyStatistics($year)
    {
        // Query data klien per bulan dalam tahun tertentu
        $monthlyStats = Client::select(
                DB::raw('MONTH(join_date) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('join_date', $year)
            ->whereNotNull('join_date')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');
        
        // Bulan dalam format pendek Indonesia
        $monthNamesShort = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $monthNamesFull = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        // Siapkan array untuk semua bulan
        $yearData = [];
        $totalClients = 0;
        $monthsWithData = 0;
        $maxPerMonth = 0;
        $previousMonthCount = 0;
        
        for ($month = 1; $month <= 12; $month++) {
            $count = isset($monthlyStats[$month]) ? $monthlyStats[$month]->total : 0;
            $totalClients += $count;
            
            if ($count > 0) {
                $monthsWithData++;
            }
            
            if ($count > $maxPerMonth) {
                $maxPerMonth = $count;
            }
            
            // Hitung perubahan dari bulan sebelumnya
            $change = 0;
            $changePercent = 0;
            $trend = 'same';
            
            if ($month > 1 && $previousMonthCount > 0) {
                $change = $count - $previousMonthCount;
                $changePercent = round(($change / $previousMonthCount) * 100, 1);
                
                if ($change > 0) {
                    $trend = 'up';
                } elseif ($change < 0) {
                    $trend = 'down';
                }
            }
            
            $previousMonthCount = $count;
            
            $yearData[] = [
                'month' => $month,
                'month_short' => $monthNamesShort[$month - 1],
                'month_full' => $monthNamesFull[$month],
                'count' => $count,
                'change' => $change,
                'change_percent' => $changePercent,
                'trend' => $trend,
                'is_current_month' => ($year == date('Y') && $month == date('m'))
            ];
        }
        
        // Hitung rata-rata
        $averagePerMonth = 12 > 0 ? round($totalClients / 12, 2) : 0;
        $averageOnMonthsWithData = $monthsWithData > 0 ? round($totalClients / $monthsWithData, 2) : 0;
        
        // Cari bulan dengan performa terbaik
        $bestMonth = null;
        $worstMonth = null;
        
        foreach ($yearData as $monthData) {
            if ($bestMonth === null || $monthData['count'] > $bestMonth['count']) {
                $bestMonth = $monthData;
            }
            if ($worstMonth === null || $monthData['count'] < $worstMonth['count']) {
                $worstMonth = $monthData;
            }
        }
        
        return [
            'year' => $year,
            'total_clients' => $totalClients,
            'average_per_month' => $averagePerMonth,
            'average_on_months_with_data' => $averageOnMonthsWithData,
            'max_per_month' => $maxPerMonth,
            'months_with_data' => $monthsWithData,
            'months_without_data' => 12 - $monthsWithData,
            'best_month' => $bestMonth,
            'worst_month' => $worstMonth,
            'monthly_data' => $yearData
        ];
    }
    
    /**
     * Persiapkan data chart untuk tahunan
     */
    private function prepareYearlyChartData($data)
    {
        $labels = [];
        $values = [];
        $backgroundColors = [];
        $borderColors = [];
        
        foreach ($data['monthly_data'] as $month) {
            $labels[] = $month['month_short'];
            $values[] = $month['count'];
            
            // Warna berbeda berdasarkan performa
            if ($month['is_current_month']) {
                $backgroundColors[] = 'rgba(75, 192, 192, 0.5)'; // Teal for current month
                $borderColors[] = 'rgba(75, 192, 192, 1)';
            } elseif ($month['trend'] === 'up') {
                $backgroundColors[] = 'rgba(34, 197, 94, 0.5)'; // Green for growth
                $borderColors[] = 'rgba(34, 197, 94, 1)';
            } elseif ($month['trend'] === 'down') {
                $backgroundColors[] = 'rgba(239, 68, 68, 0.5)'; // Red for decline
                $borderColors[] = 'rgba(239, 68, 68, 1)';
            } else {
                $backgroundColors[] = 'rgba(59, 130, 246, 0.5)'; // Blue for stable
                $borderColors[] = 'rgba(59, 130, 246, 1)';
            }
        }
        
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Klien Baru',
                    'data' => $values,
                    'backgroundColor' => $backgroundColors,
                    'borderColor' => $borderColors,
                    'borderWidth' => 1
                ]
            ]
        ];
    }
    
    /**
     * API Endpoint untuk mendapatkan data statistik (opsional untuk AJAX)
     */
    public function getStatisticsData(Request $request)
    {
        $viewType = $request->get('view_type', 'monthly');
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        
        if ($viewType === 'monthly') {
            $data = $this->getMonthlyStatistics($year, $month);
            $chartData = $this->prepareMonthlyChartData($data);
        } elseif ($viewType === 'yearly') {
            $data = $this->getYearlyStatistics($year);
            $chartData = $this->prepareYearlyChartData($data);
        } else {
            return response()->json(['error' => 'Invalid view type'], 400);
        }
        
        return response()->json([
            'data' => $data,
            'chart_data' => $chartData,
            'view_type' => $viewType,
            'year' => $year,
            'month' => $month
        ]);
    }
    
    /**
     * Download data statistik sebagai CSV
     */
    public function downloadCSV(Request $request)
    {
        $viewType = $request->get('view_type', 'monthly');
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        
        if ($viewType === 'monthly') {
            $data = $this->getMonthlyStatistics($year, $month);
            $filename = "statistik-bulanan-{$year}-{$month}.csv";
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];
            
            $callback = function() use ($data) {
                $file = fopen('php://output', 'w');
                
                // Header CSV
                fputcsv($file, ['Tanggal', 'Jumlah Klien Baru', 'Hari', 'Status']);
                
                // Data
                foreach ($data['daily_data'] as $day) {
                    $status = $day['is_weekend'] ? 'Weekend' : 'Weekday';
                    if ($day['is_today']) {
                        $status = 'Hari Ini';
                    }
                    
                    fputcsv($file, [
                        $day['date'],
                        $day['count'],
                        "{$day['date_formatted']} {$data['month_name']}",
                        $status
                    ]);
                }
                
                // Summary
                fputcsv($file, []);
                fputcsv($file, ['SUMMARY', '']);
                fputcsv($file, ['Total Klien', $data['total_clients']]);
                fputcsv($file, ['Rata-rata per Hari', $data['average_per_day']]);
                fputcsv($file, ['Maksimal per Hari', $data['max_per_day']]);
                fputcsv($file, ['Hari dengan Data', $data['days_with_data']]);
                fputcsv($file, ['Hari tanpa Data', $data['days_without_data']]);
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
            
        } elseif ($viewType === 'yearly') {
            $data = $this->getYearlyStatistics($year);
            $filename = "statistik-tahunan-{$year}.csv";
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];
            
            $callback = function() use ($data) {
                $file = fopen('php://output', 'w');
                
                // Header CSV
                fputcsv($file, ['Bulan', 'Jumlah Klien Baru', 'Perubahan', 'Trend']);
                
                // Data
                foreach ($data['monthly_data'] as $month) {
                    $trend = '';
                    if ($month['trend'] === 'up') {
                        $trend = "↑ +{$month['change']} ({$month['change_percent']}%)";
                    } elseif ($month['trend'] === 'down') {
                        $trend = "↓ {$month['change']} ({$month['change_percent']}%)";
                    } else {
                        $trend = '→ Stabil';
                    }
                    
                    fputcsv($file, [
                        $month['month_full'],
                        $month['count'],
                        $month['change'],
                        $trend
                    ]);
                }
                
                // Summary
                fputcsv($file, []);
                fputcsv($file, ['SUMMARY', '']);
                fputcsv($file, ['Total Klien', $data['total_clients']]);
                fputcsv($file, ['Rata-rata per Bulan', $data['average_per_month']]);
                fputcsv($file, ['Maksimal per Bulan', $data['max_per_month']]);
                fputcsv($file, ['Bulan Terbaik', "{$data['best_month']['month_full']} ({$data['best_month']['count']} klien)"]);
                fputcsv($file, ['Bulan Terendah', "{$data['worst_month']['month_full']} ({$data['worst_month']['count']} klien)"]);
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
        }
        
        return back()->with('error', 'Tipe statistik tidak valid');
    }
}
