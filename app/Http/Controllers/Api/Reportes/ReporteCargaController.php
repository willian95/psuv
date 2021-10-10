<?php

namespace App\Http\Controllers\Api\Reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CentroVotacion;
use App\Models\MetasUbch;
use App\Models\PersonalCaracterizacion;


class ReporteCargaController extends Controller
{

    function generate(Request $request){

        if($request->centroVotacion != "0"){
       
            $data = $this->selectedCentroVotacion($request->centroVotacion);
        }
        else if($request->parroquia != "0"){
           
            $data = $this->selectedParroquia($request->parroquia);
        }
        else if($request->municipio != "0"){
            $data = $this->selectedMunicipio($request->municipio);
        }  
        else if($request->municipio == "0"){
            $data = $this->selectedAll();
        }  


        return response()->json($data);

    }

    function selectedCentroVotacion($centroVotacion){

        $metas = MetasUbch::where("centro_votacion_id", $centroVotacion)->sum("meta");
        $personalCaracterizacion = PersonalCaracterizacion::where("centro_votacion_id", $centroVotacion)->count();

        $centroVotacionMetas = CentroVotacion::where("id", $centroVotacion)->with("metasUbchs", "personalCaracterizacions")->get();

        return ["metas" => $metas, "personalCaracterizacion" => $personalCaracterizacion, "centroVotacionMetas" => $centroVotacionMetas];

    }

    function selectedParroquia($parroquia){
        
        $metas = MetasUbch::where("parroquia_id", $parroquia)->sum("meta");
        $personalCaracterizacion = PersonalCaracterizacion::where("parroquia_id", $parroquia)->count();

        $centroVotacionMetas = CentroVotacion::where("parroquia_id", $parroquia)->with("metasUbchs", "personalCaracterizacions")->get();

        return ["metas" => $metas, "personalCaracterizacion" => $personalCaracterizacion, "centroVotacionMetas" => $centroVotacionMetas];

    }

    function selectedMunicipio($municipio){

        $metas = MetasUbch::where("municipio_id", $municipio)->sum("meta");
        $personalCaracterizacion = PersonalCaracterizacion::where("municipio_id", $municipio)->count();

        $centroVotacionMetas = CentroVotacion::with("metasUbchs", "personalCaracterizacions")->whereHas("parroquia", function($q) use($municipio){
            $q->where("municipio_id", $municipio);  
        })->get();

        return ["metas" => $metas, "personalCaracterizacion" => $personalCaracterizacion, "centroVotacionMetas" => $centroVotacionMetas];
    
    }

    function selectedAll(){

        $metas = MetasUbch::sum("meta");
        $personalCaracterizacion = PersonalCaracterizacion::count();

        $centroVotacionMetas = CentroVotacion::with("metasUbchs", "personalCaracterizacions")->get();

        return ["metas" => $metas, "personalCaracterizacion" => $personalCaracterizacion, "centroVotacionMetas" => $centroVotacionMetas];
    
    }

}
