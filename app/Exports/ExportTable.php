<?php

namespace App\Exports;

use App\Models\ligneCommande;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportTable implements FromCollection
{

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ligneCommande::all();
    }
}
