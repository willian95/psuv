<?php

namespace App\Http\Controllers;

use App\Models\Calle;
use App\Models\CensoVivienda;
use App\Models\Comunidad;
use App\Models\JefeFamilia;
use App\Models\Municipio;
use App\Models\Parroquia;
use App\Models\PersonalCaracterizacion;
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
        $data = [];
        $entidad = "Municipios";

        $condition = "";

        if($request->municipio == 0){
            $data = $this->allMunicipios();
        }

        if($request->municipio > 0){

            $condition .= " raas_municipio.id = ".$request->municipio;
            $data = $this->selectedMunicipio($request);
            $entidad = "Parroquias";
        }

        if($request->parroquia > 0){

            $condition .= " AND raas_parroquia.id = ".$request->parroquia;
            $data = $this->selectedParroquia($request);
            $entidad = "Comunidades";
        }

        if($request->comunidad > 0){

            $condition .= " AND raas_comunidad.id = ".$request->comunidad;
            $data  = $this->selectedComunidad($request);
            $entidad = "Calles";
        }

        if($request->calle > 0){

            $condition .= " AND raas_calle.id = ".$request->calle;
            $data = $this->selectedCalle($request);
            $entidad = "Calle";
        }

        $jefesFamiliaCount = $this->jefeFamiliaCount($condition);
        $mujeresCount = $this->mujeresCount($condition);
        $hombresCount = $this->hombresCount($condition);
        $ninosCount = $this->ninosCount($condition);
        $ninasCount = $this->ninasCount($condition);

        $response = [
            "jefesFamiliaCount" => $jefesFamiliaCount,
            "mujeresCount" => $mujeresCount,
            "hombresCount" => $hombresCount,
            "ninosCount" => $ninosCount,
            "ninasCount" => $ninasCount,
            "data" => $data,
            "entidad" => $entidad
        ];

        return response()->json($response);

    }

    private function selectedCalle($request){

        $entity = Calle::where("id",$request->calle)->orderBy("nombre")->first();
        $casasCount = CensoVivienda::whereIn("raas_calle_id", $entity->id)->whereIn("tipo_vivienda",["CASA", "QUINTA"])->count();
        $anexosCount = CensoVivienda::whereIn("raas_calle_id", $entity->id)->where("tipo_vivienda", "ANEXO")->count();
        $familiasSum = CensoVivienda::where("raas_calle_id", $entity->id)->sum("cantidad_familias");
        $habitantesCount = DB::table("raas_personal_caracterizacion")
        ->join("raas_jefe_familia", "raas_personal_caracterizacion.raas_jefe_familia_id", "=", "raas_jefe_familia.id")
        ->join("raas_jefe_calle", "raas_jefe_familia.raas_jefe_calle_id", "=", "raas_jefe_calle.id")
        ->join("raas_calle", "raas_jefe_calle.raas_calle_id", "=", "raas_calle.id")
        ->where("raas_calle.id", $entity->id)
        ->count();
        
        $jefesFamiliaCount = $this->jefeFamiliaCount("raas_calle.id = ".$entity->id);
        $mujeresCount = $this->mujeresCount("raas_calle.id = ".$entity->id);
        $hombresCount = $this->hombresCount("raas_calle.id = ".$entity->id);
        $ninosCount = $this->ninosCount("raas_calle.id = ".$entity->id);
        $ninasCount = $this->ninasCount("raas_calle.id = ".$entity->id);

        return [
            [
                "entity" => $entity->nombre,
                "casas" => $casasCount,
                "anexos" => $anexosCount,
                "habitantes" => $habitantesCount,
                "familias" => $familiasSum,
                "jefesFamilias" => $jefesFamiliaCount,
                "mujeres" => $mujeresCount,
                "hombres" => $hombresCount,
                "ninos" => $ninosCount,
                "ninas" => $ninasCount
            ]
        ];

    }

    private function selectedComunidad($request){

        $entities = Calle::where("raas_comunidad_id",$request->comunidad)->orderBy("nombre")->get();
        $data = [];

        foreach($entities as $entity){

            $casasCount = CensoVivienda::whereIn("raas_calle_id", $entity->id)->whereIn("tipo_vivienda",["CASA", "QUINTA"])->count();
            $anexosCount = CensoVivienda::whereIn("raas_calle_id", $entity->id)->where("tipo_vivienda", "ANEXO")->count();
            $familiasSum = CensoVivienda::where("raas_calle_id", $entity->id)->sum("cantidad_familias");
            $habitantesCount = DB::table("raas_personal_caracterizacion")
            ->join("raas_jefe_familia", "raas_personal_caracterizacion.raas_jefe_familia_id", "=", "raas_jefe_familia.id")
            ->join("raas_jefe_calle", "raas_jefe_familia.raas_jefe_calle_id", "=", "raas_jefe_calle.id")
            ->join("raas_calle", "raas_jefe_calle.raas_calle_id", "=", "raas_calle.id")
            ->where("raas_calle.id", $entity->id)
            ->count();
            
            $jefesFamiliaCount = $this->jefeFamiliaCount("raas_calle.id = ".$entity->id);
            $mujeresCount = $this->mujeresCount("raas_calle.id = ".$entity->id);
            $hombresCount = $this->hombresCount("raas_calle.id = ".$entity->id);
            $ninosCount = $this->ninosCount("raas_calle.id = ".$entity->id);
            $ninasCount = $this->ninasCount("raas_calle.id = ".$entity->id);


            $data[] = [
                "entity" => $entity->nombre,
                "casas" => $casasCount,
                "anexos" => $anexosCount,
                "habitantes" => $habitantesCount,
                "familias" => $familiasSum,
                "jefesFamilias" => $jefesFamiliaCount,
                "mujeres" => $mujeresCount,
                "hombres" => $hombresCount,
                "ninos" => $ninosCount,
                "ninas" => $ninasCount
            ];

        }

        return $data;

    }

    private function selectedParroquia($request){

        $entities = Comunidad::where("raas_parroquia_id",$request->parroquia)->orderBy("nombre")->get();
        $data = [];

        foreach($entities as $entity){

            $callesId = $this->arrayCallesByComunidad($entity->id);

            $casasCount = CensoVivienda::whereIn("raas_calle_id", $callesId)->whereIn("tipo_vivienda",["CASA", "QUINTA"])->count();
            $anexosCount = CensoVivienda::whereIn("raas_calle_id", $callesId)->where("tipo_vivienda", "ANEXO")->count();
            $familiasSum = CensoVivienda::whereIn("raas_calle_id", $callesId)->sum("cantidad_familias");
            $habitantesCount = DB::table("raas_personal_caracterizacion")
            ->join("raas_jefe_familia", "raas_personal_caracterizacion.raas_jefe_familia_id", "=", "raas_jefe_familia.id")
            ->join("raas_jefe_calle", "raas_jefe_familia.raas_jefe_calle_id", "=", "raas_jefe_calle.id")
            ->join("raas_calle", "raas_jefe_calle.raas_calle_id", "=", "raas_calle.id")
            ->whereIn("raas_calle.id", $callesId)
            ->count();
            
            $jefesFamiliaCount = $callesId ? $this->jefeFamiliaCount("raas_calle.id IN (".implode(",", $callesId).")") : 0;
            $mujeresCount =  $callesId ? $this->mujeresCount("raas_calle.id IN (".implode(",", $callesId).")") : 0;
            $hombresCount =  $callesId ? $this->hombresCount("raas_calle.id IN (".implode(",", $callesId).")") : 0;
            $ninosCount =  $callesId ? $this->ninosCount("raas_calle.id IN (".implode(",", $callesId).")") : 0;
            $ninasCount =  $callesId ? $this->ninasCount("raas_calle.id IN (".implode(",", $callesId).")") : 0;

            $data[] = [
                "entity" => $entity->nombre,
                "casas" => $casasCount,
                "anexos" => $anexosCount,
                "habitantes" => $habitantesCount,
                "familias" => $familiasSum,
                "jefesFamilias" => $jefesFamiliaCount,
                "mujeres" => $mujeresCount,
                "hombres" => $hombresCount,
                "ninos" => $ninosCount,
                "ninas" => $ninasCount
            ];

        }

        return $data;

    }

    private function selectedMunicipio($request){

        $entities = Parroquia::where("raas_municipio_id",$request->municipio)->orderBy("nombre")->get();
        $data = [];

        foreach($entities as $entity){

            $callesId = $this->arrayCallesByParroquia($entity->id);

            $casasCount = CensoVivienda::whereIn("raas_calle_id", $callesId)->whereIn("tipo_vivienda",["CASA", "QUINTA"])->count();
            $anexosCount = CensoVivienda::whereIn("raas_calle_id", $callesId)->where("tipo_vivienda", "ANEXO")->count();
            $familiasSum = CensoVivienda::whereIn("raas_calle_id", $callesId)->sum("cantidad_familias");
            $habitantesCount = DB::table("raas_personal_caracterizacion")
            ->join("raas_jefe_familia", "raas_personal_caracterizacion.raas_jefe_familia_id", "=", "raas_jefe_familia.id")
            ->join("raas_jefe_calle", "raas_jefe_familia.raas_jefe_calle_id", "=", "raas_jefe_calle.id")
            ->join("raas_calle", "raas_jefe_calle.raas_calle_id", "=", "raas_calle.id")
            ->whereIn("raas_calle.id", $callesId)
            ->count();
            
            $jefesFamiliaCount = $callesId ? $this->jefeFamiliaCount("raas_calle.id IN (".implode(",", $callesId).")") : 0;
            $mujeresCount =  $callesId ? $this->mujeresCount("raas_calle.id IN (".implode(",", $callesId).")") : 0;
            $hombresCount =  $callesId ? $this->hombresCount("raas_calle.id IN (".implode(",", $callesId).")") : 0;
            $ninosCount =  $callesId ? $this->ninosCount("raas_calle.id IN (".implode(",", $callesId).")") : 0;
            $ninasCount =  $callesId ? $this->ninasCount("raas_calle.id IN (".implode(",", $callesId).")") : 0;

            $data[] = [
                "entity" => $entity->nombre,
                "casas" => $casasCount,
                "anexos" => $anexosCount,
                "habitantes" => $habitantesCount,
                "familias" => $familiasSum,
                "jefesFamilias" => $jefesFamiliaCount,
                "mujeres" => $mujeresCount,
                "hombres" => $hombresCount,
                "ninos" => $ninosCount,
                "ninas" => $ninasCount
            ];

        }

        return $data;

    }

    private function allMunicipios(){

        $entities = Municipio::orderBy("nombre")->where("raas_estado_id", 9)->get();
        $data = [];

        foreach($entities as $entity){

            $callesId = $this->arrayCallesByMunicipio($entity->id);

            $casasCount = CensoVivienda::whereIn("raas_calle_id", $callesId)->whereIn("tipo_vivienda",["CASA", "QUINTA"])->count();
            $anexosCount = CensoVivienda::whereIn("raas_calle_id", $callesId)->where("tipo_vivienda", "ANEXO")->count();
            $familiasSum = CensoVivienda::whereIn("raas_calle_id", $callesId)->sum("cantidad_familias");
            $habitantesCount = DB::table("raas_personal_caracterizacion")
            ->join("raas_jefe_familia", "raas_personal_caracterizacion.raas_jefe_familia_id", "=", "raas_jefe_familia.id")
            ->join("raas_jefe_calle", "raas_jefe_familia.raas_jefe_calle_id", "=", "raas_jefe_calle.id")
            ->join("raas_calle", "raas_jefe_calle.raas_calle_id", "=", "raas_calle.id")
            ->whereIn("raas_calle.id", $callesId)
            ->count();

            $jefesFamiliaCount = $callesId ? $this->jefeFamiliaCount("raas_calle.id IN (".implode(",", $callesId).")") : 0;
            $mujeresCount =  $callesId ? $this->mujeresCount("raas_calle.id IN (".implode(",", $callesId).")") : 0;
            $hombresCount =  $callesId ? $this->hombresCount("raas_calle.id IN (".implode(",", $callesId).")") : 0;
            $ninosCount =  $callesId ? $this->ninosCount("raas_calle.id IN (".implode(",", $callesId).")") : 0;
            $ninasCount =  $callesId ? $this->ninasCount("raas_calle.id IN (".implode(",", $callesId).")") : 0;


            $data[] = [
                "entity" => $entity->nombre,
    
                "casas" => $casasCount,
                "anexos" => $anexosCount,
                "habitantes" => $habitantesCount,
                "familias" => $familiasSum,
                "jefesFamilias" => $jefesFamiliaCount,
                "mujeres" => $mujeresCount,
                "hombres" => $hombresCount,
                "ninos" => $ninosCount,
                "ninas" => $ninasCount
            ];

        }

        return $data;

    }

    private function arrayCallesByComunidad($comunidadId){

        return Calle::where("raas_comunidad_id", $comunidadId)->pluck("id")->toArray();

    }

    private function arrayCallesByParroquia($parroquiaId){

        return Calle::join("raas_comunidad", "raas_calle.raas_comunidad_id", "=", "raas_comunidad.id")
        ->join("raas_parroquia", "raas_comunidad.raas_parroquia_id", "=", "raas_parroquia.id")
        ->where("raas_parroquia.id", $parroquiaId)->pluck("raas_calle.id")->toArray();

    }

    private function arrayCallesByMunicipio($municipioId){

        return Calle::join("raas_comunidad", "raas_calle.raas_comunidad_id", "=", "raas_comunidad.id")
        ->join("raas_parroquia", "raas_comunidad.raas_parroquia_id", "=", "raas_parroquia.id")
        ->join("raas_municipio", "raas_parroquia.raas_municipio_id", "=", "raas_municipio.id")
        ->where("raas_municipio.id", $municipioId)->pluck("raas_calle.id")->toArray();

    }

    private function jefeFamiliaCount($condition = null){

        $jefesFamiliaCount = DB::table("raas_jefe_familia")
        ->join("raas_jefe_calle", "raas_jefe_familia.raas_jefe_calle_id", "=", "raas_jefe_calle.id")
        ->join("raas_calle", "raas_jefe_calle.raas_calle_id", "=", "raas_calle.id")
        ->join("raas_comunidad", "raas_calle.raas_comunidad_id", "=", "raas_comunidad.id")
        ->join("raas_parroquia", "raas_comunidad.raas_parroquia_id", "=", "raas_parroquia.id")
        ->join("raas_municipio", "raas_parroquia.raas_municipio_id", "=", "raas_municipio.id")
        ->whereNull("raas_jefe_familia.deleted_at")
        ->whereRaw($condition ? $condition : '1=1')
        ->count();

        return $jefesFamiliaCount;
    }

    private function mujeresCount($condition = null){

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

        return $mujeresCount;
    }

    private function hombresCount($condition = null){

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

        return $hombresCount;
    }

    private function ninosCount($condition = null){

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

        return $ninosCount;
    }

    private function ninasCount($condition = null){

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

        return $ninasCount;
    }

}
