<?php

namespace App\Http\Controllers;

use App\Models\LigneCommande;
use App\Models\validation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class historicDashboard extends Controller
{


  // public function historicDashboard()
  // {
  //   $startOfWeek = Carbon::now()->startOfWeek();
  //   $endOfWeek = Carbon::now()->endOfWeek();

  //   $data = DB::table('ligne_commande as lc')
  //     ->join('commandes as c', 'lc.serial_cmd', '=', 'c.barcode')
  //     ->whereBetween('c.created_at', [$startOfWeek, $endOfWeek])
  //     ->select(
  //       DB::raw('DAYNAME(c.created_at) as day'),
  //       DB::raw('CASE
  //                   WHEN HOUR(c.created_at) BETWEEN 6 AND 13 THEN "Shift 1"
  //                   WHEN HOUR(c.created_at) BETWEEN 14 AND 21 THEN "Shift 2"
  //                   ELSE "Shift 3"
  //               END as shift'),
  //       'lc.serial_cmd',
  //       'lc.tube_id',
  //       'lc.quantity',
  //       DB::raw('(SELECT COUNT(*) FROM validations v
  //                 WHERE v.commande_id = lc.serial_cmd AND v.tube_id = lc.tube_id) as validated_count')
  //     )
  //     ->get();

  //   $structured = [];

  //   foreach ($data as $row) {
  //     $day = $row->day;
  //     $shift = $row->shift;

  //     if (!isset($structured[$day])) {
  //       $structured[$day] = [];
  //     }

  //     if (!isset($structured[$day][$shift])) {
  //       $structured[$day][$shift] = ['en_attente' => 0, 'partial' => 0, 'livree' => 0];
  //     }

  //     $validated = $row->validated_count;
  //     $total = $row->quantity;

  //     if ($validated >= $total) {
  //       $structured[$day][$shift]['livree'] += $total;
  //     } elseif ($validated > 0) {
  //       $structured[$day][$shift]['partial'] += $validated;
  //       $structured[$day][$shift]['en_attente'] += ($total - $validated);
  //     } else {
  //       $structured[$day][$shift]['en_attente'] += $total;
  //     }

  //   }

  //   $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
  //   $shifts = ["Shift 1", "Shift 2", "Shift 3"];
  //   $statuses = ["en_attente", "partial", "livree"];

  //   $chartData = [];
  //   $chartLabels = $days; // 7 day labels only

  //   $statuses = ['en_attente', 'partial', 'livree'];
  //   $colors = [
  //     'en_attente' => 'rgba(255, 99, 132, 0.8)',
  //     'partial' => 'rgba(255, 206, 86, 0.8)',
  //     'livree' => 'rgba(75, 192, 192, 0.8)',
  //   ];

  //   $chartData = [];

  //   foreach ($shifts as $shiftIndex => $shift) {
  //     foreach ($statuses as $status) {
  //       $dataset = [
  //         'label' => "$status - $shift",
  //         'data' => [],
  //         'backgroundColor' => $colors[$status],
  //         'stack' => "stack_$shiftIndex", // stack by shift
  //       ];

  //       foreach ($days as $day) {
  //         $value = $structured[$day][$shift][$status] ?? 0;
  //         $dataset['data'][] = $value;
  //       }

  //       $chartData[] = $dataset;
  //     }
  //   }

  //   return view('adminPage.dashboard', [
  //     'chartLabels' => $chartLabels,
  //     'chartData' => $chartData,
  //   ]);

  // }



  public function index(Request $request)
  {
    $startDate = Carbon::now()->subDays(7); // Last 7 days
    $selectedDate = $request->input('selected_date', Carbon::now()->toDateString()); // Default to today if no date is selected


    // Define shifts
    $shifts = [
        'Morning' => ['start' => '00:00:00', 'end' => '08:00:00'],
        'Afternoon' => ['start' => '08:00:01', 'end' => '16:00:00'],
        'Night' => ['start' => '16:00:01', 'end' => '23:59:59'],
    ];
    
    $commandes = DB::table('commandes')
        ->selectRaw('
            DATE(created_at) as date, 
            CASE 
                WHEN HOUR(created_at) BETWEEN 0 AND 7 THEN "Morning"
                WHEN HOUR(created_at) BETWEEN 8 AND 15 THEN "Afternoon"
                ELSE "Night"
            END AS shift,
            COUNT(*) as total_commandes
        ')
        ->where('created_at', '>=', $startDate)
        ->groupBy(DB::raw('DATE(created_at), shift'))
        ->orderBy('date')
        ->get()
        ->keyBy(function ($item) {
            return $item->date . ' ' . $item->shift;
        });
    
    $validations = DB::table('validations')
        ->selectRaw('
            DATE(validated_at) as date, 
            CASE 
                WHEN HOUR(validated_at) BETWEEN 0 AND 7 THEN "Morning"
                WHEN HOUR(validated_at) BETWEEN 8 AND 15 THEN "Afternoon"
                ELSE "Night"
            END AS shift,
            COUNT(*) as total_validated
        ')
        ->where('validated_at', '>=', $startDate)
        ->groupBy(DB::raw('DATE(validated_at), shift'))
        ->orderBy('date')
        ->get()
        ->keyBy(function ($item) {
            return $item->date . ' ' . $item->shift;
        });
    
    // Merge data for the chart
    $chartData = [];
    $dates = [];
    
    foreach (range(0, 6) as $i) {
        $date = Carbon::now()->subDays(6 - $i)->toDateString();
        $dates[] = $date;
    
        $chartData[] = [
            'date' => $date,
            'commandes' => [
                'Morning' => $commandes[$date . ' Morning']->total_commandes ?? 0,
                'Afternoon' => $commandes[$date . ' Afternoon']->total_commandes ?? 0,
                'Night' => $commandes[$date . ' Night']->total_commandes ?? 0
            ],
            'validated' => [
                'Morning' => $validations[$date . ' Morning']->total_validated ?? 0,
                'Afternoon' => $validations[$date . ' Afternoon']->total_validated ?? 0,
                'Night' => $validations[$date . ' Night']->total_validated ?? 0
            ]
        ];
    }
    
    return view('adminPage.dashboard', compact('chartData', 'dates', 'selectedDate'));
    
    // return dd($chartData);
  }

}
