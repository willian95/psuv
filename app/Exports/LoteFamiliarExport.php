<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LoteFamiliarExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public $rows;

    public function __construct($rows)
    {
        $this->rows = $rows;
    }

    public function view(): View
    {
        
        return view('exports.loteFamiliar', [
            'results' => $this->rows
        ]);
    }
}
