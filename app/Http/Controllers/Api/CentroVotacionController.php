<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CentroVotacion;

class CentroVotacionController extends Controller
{
    
    function centroVotacionByParroquia($parroquia_id){

        return response()->json(CentroVotacion::where("parroquia_id", $parroquia_id)->get());

    }

    function centroVotacionByParroquiaNombre($parroquia_nombre){

        return response()->json(CentroVotacion::whereHas("parroquia",function($query) use($parroquia_nombre){
            $query->where("nombre", $parroquia_nombre);
        })->get());

    }

}
