<?php

namespace App\Exports\listados;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class EstructuraCLAP implements FromView
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

        $data = DB::select("select 'FALCÓN' estado, mu.nombre municipio, pa.nombre parroquia,
        (select cedula from public_original.raas_personal_caracterizacion where id=cem.raas_personal_caracterizacion_id) cedula_enlace_municipal,
        (select nombre_apellido from public_original.raas_personal_caracterizacion where id=cem.raas_personal_caracterizacion_id) enlace_municipal,
        (select telefono_principal from public_original.raas_personal_caracterizacion where id=cem.raas_personal_caracterizacion_id) telefono_enlace_municipal,
        clap.nombre clap,
        (select cedula from public_original.raas_personal_caracterizacion where id=jclap.raas_personal_caracterizacion_id) cedula_jefe_clap,
        (select nombre_apellido from public_original.raas_personal_caracterizacion where id=jclap.raas_personal_caracterizacion_id) jefe_clap,
        (select telefono_principal from public_original.raas_personal_caracterizacion where id=jclap.raas_personal_caracterizacion_id) telefono_jefe_clap,
        co.nombre comunidad, 
        (select cedula from public_original.raas_personal_caracterizacion where id=jco.raas_personal_caracterizacion_id) cedula_jefe_comunidad,
        (select nombre_apellido from public_original.raas_personal_caracterizacion where id=jco.raas_personal_caracterizacion_id) jefe_comunidad,
        (select telefono_principal from public_original.raas_personal_caracterizacion where id=jco.raas_personal_caracterizacion_id) telefono_jefe_comunidad,
        raas_calle.nombre calle,
        (select cedula from public_original.raas_personal_caracterizacion where id=jca.raas_personal_caracterizacion_id) cedula_jefe_calle,
        (select nombre_apellido from public_original.raas_personal_caracterizacion where id=jca.raas_personal_caracterizacion_id) jefe_calle,
        (select telefono_principal from public_original.raas_personal_caracterizacion where id=jca.raas_personal_caracterizacion_id) telefono_jefe_calle
        FROM public_original.raas_municipio mu
        left join public_original.raas_parroquia pa on pa.raas_municipio_id=mu.id
        left join public_original.censo_enlace_municipal cem on cem.raas_municipio_id=mu.id
        left join public_original.raas_comunidad co on co.raas_parroquia_id=pa.id
        left join public_original.censo_clap clap on clap.id=co.censo_clap_id
        left join public_original.censo_jefe_clap jclap on jclap.censo_clap_id=clap.id
        left join public_original.raas_jefe_comunidad jco on jco.raas_comunidad_id=co.id
        left join public_original.raas_calle on raas_calle.raas_comunidad_id=co.id
        left join public_original.raas_jefe_calle jca on jca.raas_calle_id=raas_calle.id
        where mu.raas_estado_id=9 ".$this->condition."
        and cem.deleted_at is null and clap.deleted_at is null and jclap.deleted_at is null 
        and jco.deleted_at is null and jca.deleted_at is null
        order by mu.nombre, pa.nombre, clap.nombre, co.nombre, raas_calle.nombre;");

        return view('exports.listados.estructuraClap', ["data" => $data]);
    }
}
