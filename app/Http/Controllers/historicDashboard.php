<?php

namespace App\Http\Controllers;

use App\Models\LigneCommande;
use App\Models\validation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class historicDashboard extends Controller
{


  public function historicDashboard()
  {
    $startOfWeek = Carbon::now()->startOfWeek();
    $endOfWeek = Carbon::now()->endOfWeek();

    $data = DB::table('ligne_commande as lc')
      ->join('commandes as c', 'lc.serial_cmd', '=', 'c.barcode')
      ->whereBetween('c.created_at', [$startOfWeek, $endOfWeek])
      ->select(
        DB::raw('DAYNAME(c.created_at) as day'),
        DB::raw('CASE
                    WHEN HOUR(c.created_at) BETWEEN 6 AND 13 THEN "Shift 1"
                    WHEN HOUR(c.created_at) BETWEEN 14 AND 21 THEN "Shift 2"
                    ELSE "Shift 3"
                END as shift'),
        'lc.serial_cmd',
        'lc.tube_id',
        'lc.quantity',
        DB::raw('(SELECT COUNT(*) FROM validations v
                  WHERE v.commande_id = lc.serial_cmd AND v.tube_id = lc.tube_id) as validated_count')
      )
      ->get();

    $structured = [];

    foreach ($data as $row) {
      $day = $row->day;
      $shift = $row->shift;

      if (!isset($structured[$day])) {
        $structured[$day] = [];
      }

      if (!isset($structured[$day][$shift])) {
        $structured[$day][$shift] = ['en_attente' => 0, 'partial' => 0, 'livree' => 0];
      }

      $validated = $row->validated_count;
      $total = $row->quantity;

      if ($validated >= $total) {
        $structured[$day][$shift]['livree'] += $total;
      } elseif ($validated > 0) {
        $structured[$day][$shift]['partial'] += $validated;
        $structured[$day][$shift]['en_attente'] += ($total - $validated);
      } else {
        $structured[$day][$shift]['en_attente'] += $total;
      }

    }

    $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
    $shifts = ["Shift 1", "Shift 2", "Shift 3"];
    $statuses = ["en_attente", "partial", "livree"];

    $chartData = [];
    $chartLabels = $days; // 7 day labels only

    $statuses = ['en_attente', 'partial', 'livree'];
    $colors = [
      'en_attente' => 'rgba(255, 99, 132, 0.8)',
      'partial' => 'rgba(255, 206, 86, 0.8)',
      'livree' => 'rgba(75, 192, 192, 0.8)',
    ];

    $chartData = [];

    foreach ($shifts as $shiftIndex => $shift) {
      foreach ($statuses as $status) {
        $dataset = [
          'label' => "$status - $shift",
          'data' => [],
          'backgroundColor' => $colors[$status],
          'stack' => "stack_$shiftIndex", // stack by shift
        ];

        foreach ($days as $day) {
          $value = $structured[$day][$shift][$status] ?? 0;
          $dataset['data'][] = $value;
        }

        $chartData[] = $dataset;
      }
    }

    return view('adminPage.dashboard', [
      'chartLabels' => $chartLabels,
      'chartData' => $chartData,
    ]);

  }


}
