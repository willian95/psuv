<?php

namespace App\Exports\listados;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class JefeCalle implements FromView
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

        $data = DB::select("SELECT mu.nombre municipio, pa.nombre parroquia, rc.nombre comunidad, rca.nombre calle, cedula, nombre_apellido, telefono_principal
        FROM public_original.raas_jefe_calle rjca
        left join public_original.raas_calle rca on rca.id=rjca.raas_calle_id
        left join public_original.raas_personal_caracterizacion pc on pc.id=rjca.raas_personal_caracterizacion_id
        left join public_original.raas_comunidad rc on rc.id=rca.raas_comunidad_id
        left join public_original.raas_parroquia pa on pa.id=rc.raas_parroquia_id
        left join public_original.raas_municipio mu on mu.id=pa.raas_municipio_id
        where rjca.deleted_at is null ".$this->condition."
        order by municipio, parroquia, comunidad, calle, cedula;");

        return view('exports.listados.jefeCalle', ["data" => $data]);
    }
}
