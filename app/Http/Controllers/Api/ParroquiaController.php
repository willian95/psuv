<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Parroquia;

class ParroquiaController extends Controller
{
    public function parroquiasByMunicipio($municipio_id)
    {
        return response()->json(Parroquia::where('raas_municipio_id', $municipio_id)->orderBy('nombre')->get());
    }

    public function parroquiasByMunicipioNombre($municipio_nombre)
    {
        return response()->json(Parroquia::whereHas('municipio', function ($query) use ($municipio_nombre) {
            $query->where('nombre', $municipio_nombre);
        })->get());
    }
}
