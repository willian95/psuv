<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Estado;

class EstadoController extends Controller
{
    public function all()
    {
        $estados = Estado::orderBy('nombre')->get();

        return response()->json($estados);
    }
}
