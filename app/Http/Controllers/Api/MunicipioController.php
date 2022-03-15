<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Municipio as Model;
use Illuminate\Http\Request;

class MunicipioController extends Controller
{
    public function all(Request $request)
    {
        $municipio_id = $request->input('municipio_id');
        $result = Model::query();
        if ($municipio_id) {
            $query->where('id', $municipio_id);
        }
        $result->orderBy('nombre');
        $result = $result->get();

        return response()->json($result);
    }

    public function estado($estado_id)
    {
        $municipios = Model::where('raas_estado_id', $estado_id)->orderBy('nombre')->get();

        return response()->json($municipios);
    }
}
