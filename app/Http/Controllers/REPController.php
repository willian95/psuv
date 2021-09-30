<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\REPImport;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\Elector;
use App\Models\CodigoCne;

class REPController extends Controller
{
    function importCodigos(){
        
        //Excel::queueImport(new REPImport, 'excel/rep.xlsx');
        $users = (new FastExcel)->import('excel/rep.xlsx', function ($row) {
            
            if(Elector::where("cedula", $row["CEDULA"])->count() == 0){
    
                $elector = new Elector;
                $elector->nacionalidad = $row["NAC"];
                $elector->cedula = $row["CEDULA"];
                $elector->primer_apellido = $row["APELLIDO 1"];
                $elector->segundo_apellido = $row["APELLIDO 2"];
                $elector->primer_nombre = $row["NOMBRE 1"];
                $elector->segundo_nombre = $row["NOMBRE 1"];
                $elector->sexo = $row["SEXO"];
                $elector->fecha_nacimiento = $row["FN"];
                $elector->estado_id = $row["camp 1"];
                $elector->municipio_id = $row["camp 2"];
                $elector->parroquia_id = $this->findParroquia($row["camp 2"], $row["camp 3"]);
                $elector->centro_votacion_id = $this->findCentroVotacion($row["CODIGO"]);
                $elector->save();
               
    
            }

        });
    }

    function findParroquia($municipio, $parroquia){

        $codigoParroquia = CodigoCne::where("cod_mcpo_cne", $municipio)->where("cod_pq_cne", $parroquia)->first();
        return $codigoParroquia->parroquia_id;

    }

    function findCentroVotacion($centroVotacion){

        $codigoParroquia = CodigoCne::where("cod_cv_cne", $centroVotacion)->first();
        return $codigoParroquia->centro_votacion_id;

    }
}
