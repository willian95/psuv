<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comunidad;

class ComunidadController extends Controller
{
    
    function comunidadesByParroquia($parroquia){

        return response()->json(Comunidad::where("parroquia_id", $parroquia)->get());

    }

}
