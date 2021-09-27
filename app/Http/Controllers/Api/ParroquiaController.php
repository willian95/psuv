<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Parroquia;

class ParroquiaController extends Controller
{
    function parroquiasByMunicipio($municipio_id){

        return response()->json(Parroquia::where("municipio_id", $municipio_id)->get());

    }
}
