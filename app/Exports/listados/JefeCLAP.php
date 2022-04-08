<?php

namespace App\Exports\listados;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class JefeCLAP implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
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

        $data = DB::select("SELECT mu.nombre municipio, pa.nombre parroquia, rc.nombre comunidad, clap.nombre clap, cedula, nombre_apellido, telefono_principal, sugerido
        FROM public_original.censo_clap clap
        left join public_original.censo_jefe_clap cjc on cjc.censo_clap_id=clap.id
        left join public_original.raas_personal_caracterizacion pc on pc.id=cjc.raas_personal_caracterizacion_id
        left join public_original.raas_comunidad rc on rc.censo_clap_id=clap.id
        left join public_original.raas_parroquia pa on pa.id=rc.raas_parroquia_id
        left join public_original.raas_municipio mu on mu.id=pa.raas_municipio_id
        where cjc.deleted_at is null ".$this->condition."
        order by municipio, parroquia, comunidad, clap, cedula;");

        return view('exports.listados.jefeClap', ["data" => $data]);
    }
}
