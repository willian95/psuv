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

        $data = DB::select("SELECT mu.nombre municipio, pa.nombre parroquia, cedula, nombre_apellido, telefono_principal
        FROM public_original.censo_enlace_municipal em
        left join public_original.raas_personal_caracterizacion pc on pc.id=em.raas_personal_caracterizacion_id
        left join public_original.raas_municipio mu on mu.id=em.raas_municipio_id
        left join public_original.raas_parroquia pa on pa.raas_municipio_id=mu.id
        where em.deleted_at is null ".$this->condition."
        order by municipio, parroquia, cedula;");

        

        return view('exports.listados.jefeEnlaceMunicipal', ["data" => $data]);
    }

}
