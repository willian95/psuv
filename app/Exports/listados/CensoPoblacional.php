<?php

namespace App\Exports\listados;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CensoPoblacional implements FromView
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

        $data = DB::select("select 'FALCÓN' estado, mu.nombre municipio, pa.nombre parroquia, clap.nombre clap, co.nombre comunidad, raas_calle.nombre calle,
        cviv.codigo num_casa, tipo_vivienda,
        (select cedula from public_original.raas_personal_caracterizacion where id=rjf.raas_personal_caracterizacion_id) cedula_jefe_familia,
        (select nombre_apellido from public_original.raas_personal_caracterizacion where id=rjf.raas_personal_caracterizacion_id) jefe_familia,
        (select telefono_principal from public_original.raas_personal_caracterizacion where id=rjf.raas_personal_caracterizacion_id) telefono_jefe_familia,
        pc.nombre_apellido, pc.cedula, pc.telefono_principal, pc.fecha_nacimiento, pc.sexo
        from public_original.raas_municipio mu
        left join public_original.raas_parroquia pa on pa.raas_municipio_id=mu.id
        left join public_original.raas_comunidad co on co.raas_parroquia_id=pa.id
        left join public_original.censo_clap clap on clap.id=co.censo_clap_id
        left join public_original.raas_calle on raas_calle.raas_comunidad_id=co.id
        left join public_original.censo_vivienda cviv on cviv.raas_calle_id=raas_calle.id
        left join public_original.raas_jefe_familia rjf on rjf.id=cviv.raas_jefe_familia_id
        left join public_original.raas_personal_caracterizacion pc on pc.raas_jefe_familia_id=cviv.raas_jefe_familia_id
        where mu.raas_estado_id=9 ".$this->condition."
        and clap.deleted_at is null and cviv.deleted_at is null and rjf.deleted_at is null
        order by mu.nombre, pa.nombre, clap.nombre, co.nombre, raas_calle.nombre, cviv.codigo;");

        return view('exports.listados.censoPoblacional', ["data" => $data]);
    }
}
