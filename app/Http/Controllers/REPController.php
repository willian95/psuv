<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\REPImport;

class REPController extends Controller
{
    function importCodigos(){

        Excel::import(new REPImport, 'excel/rep.xlsx');

    }
}
