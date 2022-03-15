<?php

namespace App\Traits;

use App\Models\CentroVotacion;
use App\Models\Elector;
use App\Models\Estado;
use App\Models\Municipio;
use App\Models\Parroquia;
use App\Models\PersonalCaracterizacion;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait ElectorTrait
{
    public function searchPersonalCaracterizacionOrElector($cedula, $nacionalidad = 'V', $municipio_id)
    {
        if ($municipio_id == null) {
            $elector = $this->searchPersonalCaracterizacionByCedula($cedula, $nacionalidad);
            if ($elector) {
                return ['success' => true, 'elector' => $elector];
            }

            $elector = $this->searchElectorByCedula($cedula, $nacionalidad);
            if ($elector) {
                return ['success' => true, 'elector' => $elector];
            }

            $elector = $this->searchInCNE($cedula, $nacionalidad);
            if ($elector) {
                return ['success' => true, 'elector' => $elector];
            }

            return ['success' => false, 'msg' => 'Elector no encontrado'];

        //comentario: Posbilemente el 'else' no se vaya a utilizar
        } else {
            $elector = $this->searchPersonalCaracterizacionByCedula($cedula, $nacionalidad);
            if ($elector) {
                if ($elector->municipio_id != $municipio_id) {
                    return ['success' => false, 'msg' => 'Éste Elector no pertenece a este municipio'];
                }

                return ['success' => true, 'elector' => $elector];
            }

            $elector = $this->searchElectorByCedula($cedula, $nacionalidad);

            if ($elector) {
                if ($elector->municipio_id != $municipio_id) {
                    return ['success' => false, 'msg' => 'Éste Elector no pertenece a este municipio'];
                }

                return ['success' => true, 'elector' => $elector];
            }

            if ($elector) {
                return ['success' => true, 'elector' => $elector];
            }

            return ['success' => false, 'msg' => 'Elector no encontrado'];
        }
    }

    public function searchPersonalCaracterizacionByCedula($cedula, $nacionalidad = 'V')
    {
        $elector = PersonalCaracterizacion::where('cedula', $cedula)->where('nacionalidad', $nacionalidad)->first();

        return $elector;
    }

    public function searchElectorByCedula($cedula, $nacionalidad = 'V')
    {
        $elector = Elector::where('cedula', $cedula)->where('nacionalidad', $nacionalidad)->first();

        return $elector;
    }

    public function verifyOrCreateEstado($nombre)
    {
        $estado = Estado::where('nombre', $nombre)->first();
        if ($estado) {
            return $estado;
        }

        $estado = new Estado();
        $estado->nombre = $nombre;
        $estado->save();

        return $estado;
    }

    public function verifyOrCreateMunicipio($nombre, $estado_id)
    {
        $municipio = Municipio::where('nombre', $nombre)->first();
        if ($municipio) {
            return $municipio;
        }

        $municipio = new Municipio();
        $municipio->nombre = $nombre;
        $municipio->raas_estado_id = $estado_id;
        $municipio->save();

        return $municipio;
    }

    public function verifyOrCreateParroquia($nombre, $municipio_id)
    {
        $parroquia = Parroquia::where('nombre', $nombre)->first();
        if ($parroquia) {
            return $parroquia;
        }

        $parroquia = new Parroquia();
        $parroquia->nombre = $nombre;
        $parroquia->raas_municipio_id = $municipio_id;
        $parroquia->save();

        return $parroquia;
    }

    public function verifyOrCreateCentro($nombre, $parroquia_id)
    {
        $centro = CentroVotacion::where('nombre', $nombre)->first();
        if ($centro) {
            return $centro;
        }

        $centro = new CentroVotacion();
        $centro->nombre = $nombre;
        $centro->raas_parroquia_id = $parroquia_id;
        $centro->save();

        return $centro;
    }

    public function searchInCNE($cedula, $nacionalidad = 'V')
    {
        try {
            $response = Http::get('http://www.cne.gob.ve/web/registro_electoral/ce.php?nacionalidad='.$nacionalidad.'&cedula='.$cedula);
            $response = $response->body();
            $body = explode('<td', $response);
            $nameSanitize = $body['14'];
            $estadoSanitize = $body['16'];
            $municipioSanitize = $body['18'];
            $parroquiaSanitize = $body['20'];
            $centroSanitize = $body['22'];

            if (strpos($nameSanitize, 'ESTATUS')) {
                return null;
            }

            $name = substr($nameSanitize, 17, strpos($nameSanitize, '</b>') - 17);

            if (strlen($name) > 0) {
                $estado = str_replace('EDO.', '', substr($estadoSanitize, 14, strpos($estadoSanitize, '</td>') - 14));
                $municipio = str_replace('MP.', '', substr($municipioSanitize, 18, strpos($municipioSanitize, '</td>') - 18));
                $parroquia = str_replace('PQ.', '', substr($parroquiaSanitize, 14, strpos($parroquiaSanitize, '</td>') - 14));
                $centro = substr($centroSanitize, 36, strpos($centroSanitize, '</font>') - 36);

                $estado = $this->verifyOrCreateEstado(trim($estado));
                $municipio = $this->verifyOrCreateMunicipio(trim($municipio), $estado->id);
                $parroquia = $this->verifyOrCreateParroquia(trim($parroquia), $municipio->id);
                $centro = $this->verifyOrCreateCentro(trim($centro), $parroquia->id);

                return [
                    'nombre_apellido' => $name,
                    'raas_municipio_id' => $municipio->id,
                    'raas_parroquia_id' => $parroquia->id,
                    'raas_centro_votacion_id' => $centro->id,
                    'raas_estado_id' => $estado->id,
                ];
            }

            return null;
        } catch (\Exception $e) {
            Log::error($e);

            return $e->getMessage();
        }
    }
}
