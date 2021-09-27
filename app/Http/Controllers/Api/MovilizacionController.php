<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movilizacion;

class MovilizacionController extends Controller
{
    
    function all(){

        return response()->json(Movilizacion::all());

    }

}
