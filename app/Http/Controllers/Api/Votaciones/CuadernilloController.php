<?php

namespace App\Http\Controllers\Api\Votaciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CentroVotacion;
use App\Models\PersonalCaracterizacion;
use App\Models\Votacion;
use App\Models\Eleccion;
use App\Models\Elector;
use App\Models\JefeUbch;
use App\Models\CuadernilloExportJob;
use PDF;

class CuadernilloController extends Controller
{
    
    function centrosVotacion(Request $request){

        $centros = CentroVotacion::where("parroquia_id", $request->parroquia_id)->get();
        return response()->json(["centros" => $centros]);

    }

    function generatePDF($centro_votacion_id){

        $this->cargarElectoresEnVotacion($centro_votacion_id);

        $electores = Votacion::where("centro_votacion_id", $centro_votacion_id)->with("elector")->get();
        $votaciones = $this->organizar($electores);
        $jefeUbch = JefeUbch::where("centro_votacion_id", $centro_votacion_id)->with("personalCaracterizacion")->first();
        $centroVotacion = CentroVotacion::with("parroquia", "parroquia.municipio")->find($centro_votacion_id);
        
        $pdf = PDF::loadView('pdf\cuadernillo\cuadernillo', ["votaciones" => $votaciones, "jefeUbch" => $jefeUbch, "centroVotacion" => $centroVotacion]);
        return $pdf->download('cuadernillo.pdf');

    }       

    function cargarElectoresEnVotacion($centroVotacionId){

        if(Votacion::where("centro_votacion_id", $centroVotacionId)->count() > 0){
            return;
        }

        $eleccion = Eleccion::orderBy("id", "desc")->first();
        $electores = Elector::where("centro_votacion_id", $centroVotacionId)->orderBy("cedula", "asc")->get();

        $index = 1;
        foreach($electores as $elector){

            if(Votacion::where("elector_id", $elector->id)->count() == 0){

                $votacion = new Votacion;
                $votacion->codigo_cuadernillo = $index;
                $votacion->eleccion_id = $eleccion->id;
                $votacion->elector_id = $elector->id;
                $votacion->centro_votacion_id = $elector->centro_votacion_id;
                $votacion->save();

                $index++;
            }

        }

    }

    function organizar($electores){

        $votaciones = [];

        foreach($electores as $elector){

            $votaciones[] = [

                "codigo_cuadernillo" => $elector->codigo_cuadernillo,
                "cedula" => $elector->elector->cedula,
                "nombre_completo" => $elector->elector->primer_nombre." ".$elector->elector->primer_apellido,
                "caracterizacion" => PersonalCaracterizacion::where("nacionalidad", $elector->elector->nacionalidad)->where("cedula", $elector->elector->cedula)->count()

            ];

        }

        return $votaciones;

    }

    function countElectores($centroVotacionId){

        $electoresCount = Elector::where("centro_votacion_id", $centroVotacionId)->orderBy("cedula", "asc")->count();
        return response()->json(["amount" => $electoresCount]);

    }

    function storeExportJob(Request $request){

        $cuadernillo = new CuadernilloExportJob;
        $cuadernillo->centro_votacion_id = $request->centroVotacion;
        $cuadernillo->pid = uniqid();
        $cuadernillo->email = $request->email;
        $cuadernillo->save();

        return response()->json(["success" => true, "msg" => "Proceso iniciado, le enviaremos un correo electrónico al finalizar"]);

    }

}
