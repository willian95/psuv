<?php

namespace App\Http\Controllers\Api\Reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CentroVotacion;
use App\Models\MetasUbch;
use App\Models\PersonalCaracterizacion;
use Rap2hpoutre\FastExcel\FastExcel;

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

        $centroVotacionMetas = CentroVotacion::where("id", $centroVotacion)->with("metasUbchs", "personalCaracterizacions", "parroquia", "parroquia.municipio")->orderBy("nombre")->get();

        return ["metas" => $metas, "personalCaracterizacion" => $personalCaracterizacion, "centroVotacionMetas" => $centroVotacionMetas];

    }

    function selectedParroquia($parroquia){
        
        $metas = MetasUbch::where("parroquia_id", $parroquia)->sum("meta");
        $personalCaracterizacion = PersonalCaracterizacion::where("parroquia_id", $parroquia)->count();

        $centroVotacionMetas = CentroVotacion::where("parroquia_id", $parroquia)->with("metasUbchs", "personalCaracterizacions", "parroquia", "parroquia.municipio")->orderBy("nombre")->get();

        return ["metas" => $metas, "personalCaracterizacion" => $personalCaracterizacion, "centroVotacionMetas" => $centroVotacionMetas];

    }

    function selectedMunicipio($municipio){

        $metas = MetasUbch::where("municipio_id", $municipio)->sum("meta");
        $personalCaracterizacion = PersonalCaracterizacion::where("municipio_id", $municipio)->count();

        $centroVotacionMetas = CentroVotacion::with("metasUbchs", "personalCaracterizacions", "parroquia", "parroquia.municipio")->whereHas("parroquia", function($q) use($municipio){
            $q->where("municipio_id", $municipio);  
        })->orderBy("nombre")->get();

        return ["metas" => $metas, "personalCaracterizacion" => $personalCaracterizacion, "centroVotacionMetas" => $centroVotacionMetas];
    
    }

    function selectedAll(){

        $metas = MetasUbch::sum("meta");
        $personalCaracterizacion = PersonalCaracterizacion::count();

        $centroVotacionMetas = CentroVotacion::with("metasUbchs", "personalCaracterizacions",  "parroquia", "parroquia.municipio")->orderBy("nombre")->get();

        return ["metas" => $metas, "personalCaracterizacion" => $personalCaracterizacion, "centroVotacionMetas" => $centroVotacionMetas];
    
    }

    function download(Request $request){

        ini_set("memory_limit", -1);

        $data = null;

        if($request->centroVotacion != "0"){
     
            $data = $this->selectedCentroVotacionDownload($request->centroVotacion);
        }
        else if($request->parroquia != "0"){
            
            $data = $this->selectedParroquiaDownload($request->parroquia);
        }
        else if($request->municipio != "0"){
      
            $data = $this->selectedMunicipioDownload($request->municipio);
        }  
        else if($request->municipio == "0"){
            
            $data = $this->selectedAllDownload();
        }  

        return  (new FastExcel($data))->download('ReporteCarga.xlsx', function ($meta) {
            
            return [
                'MUNICIPIO' => $meta->parroquia->municipio->nombre,
                'PARROQUIA' => $meta->parroquia->nombre,
                'CENTRO VOTACION' => $meta->nombre,
                'META' => $meta->metasUbchs[0]->meta,
                'CARGA' => count($meta->personalCaracterizacions),
                'PENDIENTE' => $meta->metasUbchs[0]->meta - count($meta->personalCaracterizacions)
            ];
        });

    }

    function selectedCentroVotacionDownload($centroVotacion){

        $centroVotacionMetas = CentroVotacion::where("id", $centroVotacion)->with("metasUbchs", "personalCaracterizacions", "parroquia", "parroquia.municipio")->orderBy("nombre")->get();

        return$centroVotacionMetas;

    }

    function selectedParroquiaDownload($parroquia){
        

        $centroVotacionMetas = CentroVotacion::where("parroquia_id", $parroquia)->with("metasUbchs", "personalCaracterizacions", "parroquia", "parroquia.municipio")->orderBy("nombre")->get();

        return $centroVotacionMetas;

    }

    function selectedMunicipioDownload($municipio){

        $centroVotacionMetas = CentroVotacion::with("metasUbchs", "personalCaracterizacions", "parroquia", "parroquia.municipio")->whereHas("parroquia", function($q) use($municipio){
            $q->where("municipio_id", $municipio);  
        })->orderBy("nombre")->get();

        return $centroVotacionMetas;
    
    }

    function selectedAllDownload(){

        $centroVotacionMetas = CentroVotacion::with("metasUbchs", "personalCaracterizacions",  "parroquia", "parroquia.municipio")->orderBy("nombre")->get();

        return $centroVotacionMetas;
    
    }

}
