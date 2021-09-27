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

}
