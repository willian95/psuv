<?php

namespace App\Exports\listados;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class JefeComunidad implements FromView
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

        $data = DB::select("SELECT mu.nombre municipio, pa.nombre parroquia, rc.nombre comunidad, cedula, nombre_apellido, telefono_principal
        FROM public_original.raas_jefe_comunidad rjc 
        left join public_original.raas_personal_caracterizacion pc on pc.id=rjc.raas_personal_caracterizacion_id
        left join public_original.raas_comunidad rc on rc.id=rjc.raas_comunidad_id
        left join public_original.raas_parroquia pa on pa.id=rc.raas_parroquia_id
        left join public_original.raas_municipio mu on mu.id=pa.raas_municipio_id
        where rjc.deleted_at is null ".$this->condition."
        order by municipio, parroquia, comunidad, cedula;");

        

        return view('exports.listados.jefeComunidad', ["data" => $data]);
    }
}
