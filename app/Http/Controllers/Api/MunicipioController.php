<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Municipio;

class MunicipioController extends Controller
{
    function all(){

        return response()->json(Municipio::all());

    }
}
