<?php

namespace App\Traits;

use App\Models\Elector;
use App\Models\PersonalCaracterizacion;

trait PersonalCaracterizacionTrait
{
    public function storePersonalCaracterizacion($data, $cne = false)
    {
        if (!is_array($data)) {
            $data = $data->toArray();
        }

        $personal = new PersonalCaracterizacion();
        $personal->cedula = $data['cedula'];
        $personal->nombre_apellido = $data['nombre_apellido'];
        $personal->sexo = $data['sexo'];
        $personal->telefono_principal = $data['telefono_principal'];
        $personal->telefono_secundario = $data['telefono_secundario'];
        $personal->nacionalidad = $data['nacionalidad'];
        $personal->tipo_voto = $data['tipo_voto'];
        $personal->raas_estado_id = $data['raas_estado_id'];
        $personal->raas_municipio_id = $data['raas_municipio_id'];
        $personal->raas_parroquia_id = $data['raas_parroquia_id'];
        $personal->raas_centro_votacion_id = $data['raas_centro_votacion_id'];
        $personal->partido_politico_id = $data['partido_politico_id'];
        $personal->movilizacion_id = $data['movilizacion_id'];
        $personal->sexo = $data['sexo'];
        $personal->save();

        $elector = new Elector();
        $elector->cedula = $data['cedula'];
        $elector->nombre_apellido = $data['nombre_apellido'];
        $elector->sexo = $data['sexo'];
        $elector->nacionalidad = $data['nacionalidad'];
        $elector->raas_estado_id = $data['raas_estado_id'];
        $elector->raas_municipio_id = $data['raas_municipio_id'];
        $elector->raas_parroquia_id = $data['raas_parroquia_id'];
        $elector->raas_centro_votacion_id = $data['raas_centro_votacion_id'];
        $elector->sexo = $data['sexo'];
        $elector->save();

        return $personal;
    }

    public function updatePersonalCaracterizacion($id, $data)
    {
        $personal = PersonalCaracterizacion::find($id);
        $personal->nombre_apellido = $data->nombre_apellido;
        $personal->cedula = $data->cedula ? $data->cedula : $personal->cedula;
        $personal->nombre_apellido = $data->nombre_apellido;
        $personal->sexo = $data->sexo;
        $personal->telefono_principal = $data->telefono_principal ? $data->telefono_principal : $personal->telefono_principal;
        $personal->telefono_secundario = $data->telefono_secundario ? $data->telefono_secundario : $personal->telefono_secundario;
        $personal->fecha_nacimiento = $data->fecha_nacimiento ? $data->fecha_nacimiento : $personal->fecha_nacimiento;
        $personal->tipo_voto = $data->tipo_voto ? $data->tipo_voto : $personal->tipo_voto;
        $personal->inhabilitado_politicio = $data->inhabilitado_politicio ? $data->inhabilitado_politicio : 0;
        $personal->partido_politico_id = $data->partido_politico_id ? $data->partido_politico_id : $personal->partido_politico_id;
        $personal->movilizacion_id = $data->movilizacion_id ? $data->movilizacion_id : $personal->movilizacion_id;
        $personal->update();
    }
}
