<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StatistikController extends Controller
{
    public function index(Request $request)
    {
        $viewType = $request->get('view_type', 'monthly');
        $year     = (int) $request->get('year', now()->year);
        $month    = (int) $request->get('month', now()->month);

        $years = Client::selectRaw('YEAR(start_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $months = [
            1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
            5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
            9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
        ];

        /* =====================
         | MODE BULANAN
         ===================== */
        if ($viewType === 'monthly') {

            $daysInMonth = Carbon::create($year, $month)->daysInMonth;

            // Bulan ini
            $currentMonthData = [];
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $currentMonthData[] = Client::whereDate(
                    'start_date',
                    Carbon::create($year, $month, $d)
                )->count();
            }

            // Bulan lalu
            $prev = Carbon::create($year, $month, 1)->subMonth();
            $prevMonthData = [];

            for ($d = 1; $d <= $daysInMonth; $d++) {
                $prevMonthData[] = Client::whereDate(
                    'start_date',
                    Carbon::create($prev->year, $prev->month, $d)
                )->count();
            }

            $totalCurrent = array_sum($currentMonthData);
            $totalPrev    = array_sum($prevMonthData);

            $growth = $totalPrev > 0
                ? round((($totalCurrent - $totalPrev) / $totalPrev) * 100, 1)
                : null;

            $averageDaily = round($totalCurrent / $daysInMonth, 2);

            $peakValue = max($currentMonthData);
            $peakDay   = array_search($peakValue, $currentMonthData) + 1;

            $data = [
    'year'             => $year,
    'month'            => $month,
    'month_name'       => $months[$month],
    'total'            => $totalCurrent,
    'average_monthly'  => round($totalCurrent / $daysInMonth, 2),

    'highest_month' => [
        'total' => $peakValue,
        'month' => 'Tanggal ' . $peakDay
    ],

    'growth' => $growth,
];


                
            

            $chartData = [
                'labels' => range(1, $daysInMonth),
                'datasets' => [
                    [
                        'label' => 'Bulan Ini',
                        'data'  => $currentMonthData,
                        'borderColor' => '#4e73df',
                        'backgroundColor' => 'rgba(78,115,223,0.1)',
                    ],
                    [
                        'label' => 'Bulan Lalu',
                        'data'  => $prevMonthData,
                        'borderColor' => '#e74a3b',
                        'backgroundColor' => 'rgba(231,74,59,0.1)',
                        'borderDash' => [5,5],
                    ],
                ]
            ];
        }

        /* =====================
         | MODE TAHUNAN
         ===================== */
        else {

            $currentYearData = [];
            $prevYearData    = [];

            for ($m = 1; $m <= 12; $m++) {
                $currentYearData[] = Client::whereYear('start_date', $year)
                    ->whereMonth('start_date', $m)
                    ->count();

                $prevYearData[] = Client::whereYear('start_date', $year - 1)
                    ->whereMonth('start_date', $m)
                    ->count();
            }

            $totalYear     = array_sum($currentYearData);
            $totalPrevYear = array_sum($prevYearData);

            $growthYear = $totalPrevYear > 0
                ? round((($totalYear - $totalPrevYear) / $totalPrevYear) * 100, 1)
                : null;
$maxValue = max($currentYearData);
$bestMonthIndex = array_search($maxValue, $currentYearData); // 0-based

$data = [
    'year'        => $year,
    'total'       => $totalYear,
    'average'     => round($totalYear / 12, 2),
    'max'         => $maxValue,
    'highest_month' => $months[$bestMonthIndex + 1], // +1 karena bulan mulai dari 1
    'growth_year' => $growthYear,
];


            $chartData = [
                'labels' => array_values($months),
                'datasets' => [
                    [
                        'label' => 'Tahun ' . $year,
                        'data'  => $currentYearData,
                        'borderColor' => '#1cc88a',
                        'backgroundColor' => 'rgba(28,200,138,0.1)',
                    ],
                    [
                        'label' => 'Tahun ' . ($year - 1),
                        'data'  => $prevYearData,
                        'borderColor' => '#858796',
                        'backgroundColor' => 'rgba(133,135,150,0.1)',
                        'borderDash' => [5,5],
                    ],
                ]
            ];
        }

        return view('statistik.index', compact(
            'viewType','year','month','years','months','data','chartData'
        ));
    }
}
