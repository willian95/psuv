<?php

namespace App\Exports\listados;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class JefeEnlaceMunicipal implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function forCondition($condition)
    {
        $this->condition = $condition;
        
        return $this;
    }

    public function view(): View
    {

        $data = DB::select("SELECT mu.nombre municipio, pa.nombre parroquia, cv.nombre ubch, cedula, nombre_apellido, telefono_principal
        FROM public_original.raas_jefe_ubch rju
        left join public_original.raas_centro_votacion cv on cv.id=rju.raas_centro_votacion_id
        left join public_original.raas_personal_caracterizacion pc on pc.id=rju.raas_personal_caracterizacion_id
        left join public_original.raas_parroquia pa on pa.id=cv.raas_parroquia_id
        left join public_original.raas_municipio mu on mu.id=pa.raas_municipio_id
        WHERE rju.deleted_at is null ".$this->condition."
        order by municipio, parroquia, cedula;");

        dump("SELECT mu.nombre municipio, pa.nombre parroquia, cv.nombre ubch, cedula, nombre_apellido, telefono_principal
        FROM public_original.raas_jefe_ubch rju
        left join public_original.raas_centro_votacion cv on cv.id=rju.raas_centro_votacion_id
        left join public_original.raas_personal_caracterizacion pc on pc.id=rju.raas_personal_caracterizacion_id
        left join public_original.raas_parroquia pa on pa.id=cv.raas_parroquia_id
        left join public_original.raas_municipio mu on mu.id=pa.raas_municipio_id
        WHERE rju.deleted_at is null ".$this->condition."
        order by municipio, parroquia, cedula;");

        return view('exports.listados.jefeEnlaceMunicipal', ["data" => $data]);
    }

}
