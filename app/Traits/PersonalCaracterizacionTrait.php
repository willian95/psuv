<?php

namespace App\Traits;

use App\Models\Elector;
use App\Models\PersonalCaracterizacion;

trait PersonalCaracterizacionTrait
{
    public function storePersonalCaracterizacion($data, $cne = false)
    {
        if ($cne == false) {
            $personalCaracterizacion = PersonalCaracterizacion::create($data->toArray());
            $personalCaracterizacion->municipio_id = Elector::where('cedula', $personalCaracterizacion->cedula)->first()->municipio_id;
            $personalCaracterizacion->parroquia_id = Elector::where('cedula', $personalCaracterizacion->cedula)->first()->parroquia_id;
            $personalCaracterizacion->centro_votacion_id = Elector::where('cedula', $personalCaracterizacion->cedula)->first()->centro_votacion_id;
            $personalCaracterizacion->update();
        } else {
            $personalCaracterizacion = PersonalCaracterizacion::create($data);
        }

        return $personalCaracterizacion;
    }

    public function updatePersonalCaracterizacion($id, $data)
    {
        $personal = PersonalCaracterizacion::find($id);
        $personal->cedula = $data->cedula ? $data->cedula : $personal->cedula;
        $personal->nacionalidad = $data->nacionalidad ? $data->nacionalidad : $personal->nacionalidad;
        $personal->primer_apellido = $data->primer_apellido ? $data->primer_apellido : $personal->primer_apellido;
        $personal->segundo_apellido = $data->segundo_apellido ? $data->segundo_apellido : $personal->segundo_apellido;
        $personal->primer_nombre = $data->primer_nombre ? $data->primer_nombre : $personal->primer_nombre;
        $personal->segundo_nombre = $data->segundo_nombre ? $data->segundo_nombre : $personal->segundo_nombre;
        $personal->sexo = $data->sexo ? $data->sexo : $personal->sexo;
        $personal->telefono_principal = $data->telefono_principal ? $data->telefono_principal : $personal->telefono_principal;
        $personal->telefono_secundario = $data->telefono_secundario ? $data->telefono_secundario : $personal->telefono_secundario;
        $personal->fecha_nacimiento = $data->fecha_nacimiento ? $data->fecha_nacimiento : $personal->fecha_nacimiento;
        $personal->tipo_voto = $data->tipo_voto ? $data->tipo_voto : $personal->tipo_voto;
        $personal->inhabilitado_politicio = $data->inhabilitado_politicio ? $data->inhabilitado_politicio : 0;
        $personal->estado_id = $data->estado_id ? $data->estado_id : $personal->estado_id;
        //$personal->municipio_id = $data->municipio_id;
        //$personal->parroquia_id = $data->parroquia_id;
        //$personal->centro_votacion_id = $data->centro_votacion_id;
        $personal->partido_politico_id = $data->partido_politico_id ? $data->partido_politico_id : $personal->partido_politico_id;
        $personal->movilizacion_id = $data->movilizacion_id ? $data->movilizacion_id : $personal->movilizacion_id;
        $personal->update();
    }
}
