<?php

namespace App\Http\Controllers\Api\Clap;

use App\Exports\LoteFamiliarExport;
use App\Http\Controllers\Controller;
use App\Imports\LoteFamiliarImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class LoteCalleController extends Controller
{
    
    function store(Request $request){
        
        $path = $request->file('nucleo')->store(uniqid());
        $import = (new LoteFamiliarImport)->forCalleId($request->calle);
        Excel::import($import, $path);
        if($import->tempRows){
            //dd($import->tempRows);
            return Excel::download(new LoteFamiliarExport(
                $import->tempRows
            ), "Cedulas.xlsx");
        }
        return redirect()->to("clap/lote-calle");

    }

}
