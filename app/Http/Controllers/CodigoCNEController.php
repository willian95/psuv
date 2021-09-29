<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CodigoCNEImport;

class CodigoCNEController extends Controller
{
    
    function importCodigos(){

        Excel::import(new CodigoCNEImport, 'excel/codigos_CNE.ods');

    }

}
