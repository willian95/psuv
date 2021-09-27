<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PartidoPolitico;

class PartidoPoliticoController extends Controller
{
    function all(){

        return response()->json(PartidoPolitico::all());

    }
}
