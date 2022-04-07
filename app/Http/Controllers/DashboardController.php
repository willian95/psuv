<?php

namespace App\Http\Controllers;

use App\Models\JefeFamilia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    
    function generate(Request $request){

        ini_set('max_execution_time', 300);

        $jefesFamiliaCount = 0;
        $mujeresCount = 0;
        $hombresCount = 0;
        $ninosCount = 0;

        $condition = "";
        if($request->municipio > 0){

            $condition .= " raas_municipio.id = ".$request->municipio;

        }

        if($request->parroquia > 0){

            $condition .= " AND raas_parroquia.id = ".$request->parroquia;

        }

        if($request->comunidad > 0){

            $condition .= " AND raas_comunidad.id = ".$request->comunidad;

        }

        if($request->calle > 0){

            $condition .= " AND raas_calle.id = ".$request->calle;

        }

        $jefesFamiliaCount = DB::table("raas_jefe_familia")
        ->join("raas_jefe_calle", "raas_jefe_familia.raas_jefe_calle_id", "=", "raas_jefe_calle.id")
        ->join("raas_calle", "raas_jefe_calle.raas_calle_id", "=", "raas_calle.id")
        ->join("raas_comunidad", "raas_calle.raas_comunidad_id", "=", "raas_comunidad.id")
        ->join("raas_parroquia", "raas_comunidad.raas_parroquia_id", "=", "raas_parroquia.id")
        ->join("raas_municipio", "raas_parroquia.raas_municipio_id", "=", "raas_municipio.id")
        ->whereRaw($condition ? $condition : '1=1')
        ->count();

        $mujeresCount = DB::table("raas_personal_caracterizacion")
        ->join("raas_jefe_familia", "raas_personal_caracterizacion.raas_jefe_familia_id", "=", "raas_jefe_familia.id")
        ->join("raas_jefe_calle", "raas_jefe_familia.raas_jefe_calle_id", "=", "raas_jefe_calle.id")
        ->join("raas_calle", "raas_jefe_calle.raas_calle_id", "=", "raas_calle.id")
        ->join("raas_comunidad", "raas_calle.raas_comunidad_id", "=", "raas_comunidad.id")
        ->join("raas_parroquia", "raas_comunidad.raas_parroquia_id", "=", "raas_parroquia.id")
        ->join("raas_municipio", "raas_parroquia.raas_municipio_id", "=", "raas_municipio.id")
        ->where("raas_personal_caracterizacion.sexo", '=', 'femenino')
        ->whereRaw($condition ? $condition : '1=1')
        ->count();

        $hombresCount = DB::table("raas_personal_caracterizacion")
        ->join("raas_jefe_familia", "raas_personal_caracterizacion.raas_jefe_familia_id", "=", "raas_jefe_familia.id")
        ->join("raas_jefe_calle", "raas_jefe_familia.raas_jefe_calle_id", "=", "raas_jefe_calle.id")
        ->join("raas_calle", "raas_jefe_calle.raas_calle_id", "=", "raas_calle.id")
        ->join("raas_comunidad", "raas_calle.raas_comunidad_id", "=", "raas_comunidad.id")
        ->join("raas_parroquia", "raas_comunidad.raas_parroquia_id", "=", "raas_parroquia.id")
        ->join("raas_municipio", "raas_parroquia.raas_municipio_id", "=", "raas_municipio.id")
        ->where("raas_personal_caracterizacion.sexo", '=', 'masculino')
        ->whereRaw($condition ? $condition : '1=1')
        ->count();

        $ninosCount = DB::table("raas_personal_caracterizacion")
        ->join("raas_jefe_familia", "raas_personal_caracterizacion.raas_jefe_familia_id", "=", "raas_jefe_familia.id")
        ->join("raas_jefe_calle", "raas_jefe_familia.raas_jefe_calle_id", "=", "raas_jefe_calle.id")
        ->join("raas_calle", "raas_jefe_calle.raas_calle_id", "=", "raas_calle.id")
        ->join("raas_comunidad", "raas_calle.raas_comunidad_id", "=", "raas_comunidad.id")
        ->join("raas_parroquia", "raas_comunidad.raas_parroquia_id", "=", "raas_parroquia.id")
        ->join("raas_municipio", "raas_parroquia.raas_municipio_id", "=", "raas_municipio.id")
        ->where("raas_personal_caracterizacion.sexo", '=', 'masculino')
        ->whereRaw("date_part('year',age(fecha_nacimiento))<18")
        ->whereRaw($condition ? $condition : '1=1')
        ->count();

        $ninasCount = DB::table("raas_personal_caracterizacion")
        ->join("raas_jefe_familia", "raas_personal_caracterizacion.raas_jefe_familia_id", "=", "raas_jefe_familia.id")
        ->join("raas_jefe_calle", "raas_jefe_familia.raas_jefe_calle_id", "=", "raas_jefe_calle.id")
        ->join("raas_calle", "raas_jefe_calle.raas_calle_id", "=", "raas_calle.id")
        ->join("raas_comunidad", "raas_calle.raas_comunidad_id", "=", "raas_comunidad.id")
        ->join("raas_parroquia", "raas_comunidad.raas_parroquia_id", "=", "raas_parroquia.id")
        ->join("raas_municipio", "raas_parroquia.raas_municipio_id", "=", "raas_municipio.id")
        ->where("raas_personal_caracterizacion.sexo", '=', 'femenino')
        ->whereRaw("date_part('year',age(fecha_nacimiento))<18")
        ->whereRaw($condition ? $condition : '1=1')
        ->count();

        


        $response = [
            "jefesFamiliaCount" => $jefesFamiliaCount,
            "mujeresCount" => $mujeresCount,
            "hombresCount" => $hombresCount,
            "ninosCount" => $ninosCount,
            "ninasCount" => $ninasCount,
            "casasCount" => $casasCount
        ];

        return response()->json($response);

    }

}
