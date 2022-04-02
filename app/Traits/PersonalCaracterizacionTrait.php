<?php

namespace App\Traits;

use App\Models\Elector;
use App\Models\PersonalCaracterizacion;
use Illuminate\Support\Facades\Log;

trait PersonalCaracterizacionTrait
{
    public function storePersonalCaracterizacion($data)
    {
        $personal = null;
        if (!is_array($data)) {
            $data = $data->toArray();
        }

        if (!PersonalCaracterizacion::where('cedula', $data['cedula'])->where('nacionalidad', $data['nacionalidad'])->first()) {
            $personal = new PersonalCaracterizacion();
            $personal->cedula = $data['cedula'];
            $personal->nombre_apellido = strtoupper($data['nombre_apellido']);
            $personal->sexo = $data['sexo'];
            $personal->telefono_principal = $data['telefono_principal'] ?? null;
            $personal->telefono_secundario = $data['telefono_secundario'] ?? null;
            $personal->nacionalidad = $data['nacionalidad'];
            $personal->tipo_voto = $data['tipo_voto'] ?? null;
            $personal->fecha_nacimiento = $data['fecha_nacimiento'] ?? null;
            $personal->raas_estado_id = $data['raas_estado_id'] ?? null;
            $personal->raas_municipio_id = $data['raas_municipio_id'] ?? null;
            $personal->raas_parroquia_id = $data['raas_parroquia_id'] ?? null;
            $personal->raas_estatus_personal_id = $data['raas_estatus_personal_id'] ?? null;
            $personal->raas_centro_votacion_id = $data['raas_centro_votacion_id'] ?? null;
            $personal->elecciones_partido_politico_id = $data['partido_politico_id'] ?? null;
            $personal->elecciones_movilizacion_id = $data['movilizacion_id'] ?? null;

            if (!isset($data['raas_centro_votacion_id'])) {
                $personal->es_elector = false;
            }

            $personal->save();
        }

        if (!Elector::where('cedula', $data['cedula'])->where('nacionalidad', $data['nacionalidad'])->first() && $data['raas_estado_id']) {
            $elector = new Elector();
            $elector->cedula = $data['cedula'];
            $elector->nombre_apellido = strtoupper($data['nombre_apellido']);
            $elector->sexo = $data['sexo'];
            $elector->nacionalidad = $data['nacionalidad'];
            $elector->raas_estado_id = $data['raas_estado_id'];
            $elector->raas_municipio_id = $data['raas_municipio_id'];
            $elector->raas_parroquia_id = $data['raas_parroquia_id'];
            $elector->raas_centro_votacion_id = $data['raas_centro_votacion_id'];
            $elector->sexo = $data['sexo'];
            $elector->save();
        }

        return $personal;
    }

    public function updatePersonalCaracterizacion($id, $data)
    {

        $personal = PersonalCaracterizacion::find($id);
        $personal->nombre_apellido = $data->nombre_apellido;
        $personal->cedula = $data->cedula ? $data->cedula : $personal->cedula;
        $personal->nombre_apellido = $data->nombre_apellido ? $data->nombre_apellido : $personal->nombre_apellido;
        $personal->sexo = $data->sexo ? $data->sexo : $personal->sexo;
        $personal->telefono_principal = $data->telefono_principal ? $data->telefono_principal : $personal->telefono_principal;
        $personal->telefono_secundario = $data->telefono_secundario ? $data->telefono_secundario : $personal->telefono_secundario;
        $personal->fecha_nacimiento = $data->fecha_nacimiento ? $data->fecha_nacimiento : $personal->fecha_nacimiento;
        $personal->tipo_voto = $data->tipo_voto ? $data->tipo_voto : $personal->tipo_voto;
        $personal->inhabilitado_politicio = 'no';
        $personal->raas_estatus_personal_id = $data->raas_estatus_personal_id ? $data->raas_estatus_personal_id : $personal->raas_estatus_personal_id;
        $personal->elecciones_partido_politico_id = $data->partido_politico_id ? $data->elecciones_partido_politico_id : $personal->elecciones_partido_politico_id;
        $personal->elecciones_movilizacion_id = $data->movilizacion_id ? $data->elecciones_movilizacion_id : $personal->elecciones_movilizacion_id;
        $personal->update();
    }

    public function getPersonalCaracterizacion($cedula, $nacionalidad)
    {
        $personalCaracterizacion = PersonalCaracterizacion::where('cedula', $cedula)->where('nacionalidad', $nacionalidad)->first();

        return $personalCaracterizacion;
    }

    public function getElector($cedula, $nacionalidad)
    {
        $elector = Elector::where('cedula', $cedula)->where('nacionalidad', $nacionalidad)->first();

        return $elector;
    }
}
