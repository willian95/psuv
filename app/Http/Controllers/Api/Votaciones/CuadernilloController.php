<?php

namespace App\Http\Controllers\Api\Votaciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CentroVotacion;
use App\Models\Votacion;
use App\Models\Eleccion;
use App\Models\Elector;

class CuadernilloController extends Controller
{
    
    function centrosVotacion(Request $request){

        $centros = CentroVotacion::where("parroquia_id", $request->parroquia_id)->get();
        return response()->json(["centros" => $centros]);

    }

    function generatePDF(Request $request){

        $centroVotacionId = $request->centro_votacion_id;
        $this->cargarElectoresEnVotacion($centroVotacionId);

        Votacion::whereHas("elector", function($q) use($centroVotacionId){
            $q->where("centro_votacion_id", $centroVotacionId);
        })->orderBy("codigo_cuadernillo")->get();

    }

    function cargarElectoresEnVotacion($centroVotacionId){

        $eleccion = Eleccion::orderBy("id", "desc")->first();
        $electores = Elector::where("centro_votacion_id", $centroVotacionId)->orderBy("cedula", "asc")->get();

        $index = 1;
        foreach($electores as $elector){

            if(Votacion::where("elector_id", $elector->id)->count() == 0){

                $votacion = new Votacion;
                $votacion->codigo_cuadernillo = $index;
                $votacion->eleccion_id = $eleccion->id;
                $votacion->save();

                $index++;
            }

        
        }

    }

}
