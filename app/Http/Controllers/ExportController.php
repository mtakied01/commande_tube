<?php

namespace App\Http\Controllers;

use App\Exports\ExportTable;
use App\Models\LigneCommande;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\YourExportClass;

class ExportController extends Controller
{
    public function excel()
    {
        $data = [['DPN', 'Quantité', 'Statut', 'Description']];
        $ligne_cmd = LigneCommande::where('statut','en attente')->with('tubes');
        foreach ($ligne_cmd as $value) {
            array_push($data, $value);
        }   
        $filename = 'export.xlsx';

        return Excel::download(new ExportTable($data), $filename);
        // return response()->json(['message' => 'Excel export functionality not implemented yet.']);
    }
    public function pdf()
    {
        return response()->json(['message' => 'Pdf export functionality not implemented yet.']);
    }
}
