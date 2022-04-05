<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    
    function generate(Request $request){

        ini_set('max_execution_time', 300);

        $condition = "";
        if($request->has("municipio")){

            $condition .= " AND raas_municipio.id = ".$request->municipio;

        }

        if($request->has("parroquia")){

            $condition .= " AND raas_parroquia.id = ".$request->parroquia;

        }

        if($request->has("comunidad")){

            $condition .= " AND raas_comunidad.id = ".$request->comunidad;

        }

        if($request->has("calle")){

            $condition .= " AND raas_calle.id = ".$request->calle;

        }

        $data = DB::select(DB::raw("Select sum(cantidad_familias) familias, (select count(*)from public_original.censo_vivienda where tipo_vivienda='casa' and deleted_at is null) casas,
        (select count(*)from public_original.censo_vivienda where tipo_vivienda='anexo' and deleted_at is null) anexos, count(*) jefe_familia,
        (select count(*) from public_original.raas_personal_caracterizacion where raas_jefe_familia_id is not null) cantidad_habitantes,
        (select count(date_part('year',age(fecha_nacimiento))) from public_original.raas_personal_caracterizacion where fecha_nacimiento is not null and date_part('year',age(fecha_nacimiento))>=18 and sexo='femenino') mujeres,
        (select count(date_part('year',age(fecha_nacimiento))) from public_original.raas_personal_caracterizacion where fecha_nacimiento is not null and date_part('year',age(fecha_nacimiento))>=18 and sexo='masculino') hombres,
        (select count(date_part('year',age(fecha_nacimiento))) from public_original.raas_personal_caracterizacion where fecha_nacimiento is not null and date_part('year',age(fecha_nacimiento))<18 and sexo='femenino') menor_edad_femenino,
        (select count(date_part('year',age(fecha_nacimiento))) from public_original.raas_personal_caracterizacion where fecha_nacimiento is not null and date_part('year',age(fecha_nacimiento))<18 and sexo='masculino') menor_edad_masculino
        from public_original.censo_vivienda
        join public_original.raas_jefe_familia jf on jf.id=censo_vivienda.raas_jefe_familia_id
        join public_original.raas_jefe_calle rjca on rjca.id=jf.raas_jefe_calle_id
        join public_original.raas_calle on raas_calle.id=rjca.raas_calle_id
        join public_original.raas_comunidad on raas_comunidad.id=raas_calle.raas_comunidad_id
        join public_original.raas_parroquia on raas_parroquia.id=raas_comunidad.raas_parroquia_id
        join public_original.raas_municipio on raas_municipio.id=raas_parroquia.raas_municipio_id
        where jf.deleted_at is null and rjca.deleted_at is null ".$condition.";"));

        return response()->json($data);

    }

}
