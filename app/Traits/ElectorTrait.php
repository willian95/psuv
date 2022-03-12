<?php

namespace App\Traits;

use App\Models\Elector;
use App\Models\PersonalCaracterizacion;
use Illuminate\Support\Facades\Http;

trait ElectorTrait
{
    public function searchPersonalCaracterizacionOrElector($cedula, $municipio_id)
    {
        if ($municipio_id == null) {
            $elector = $this->searchPersonalCaracterizacionByCedula($cedula);
            if ($elector) {
                return ['success' => true, 'elector' => $elector];
            }

            $elector = $this->searchElectorByCedula($cedula);
            if ($elector) {
                return ['success' => true, 'elector' => $elector];
            }

            $elector = $this->searchInCNE($cedula);
            if ($elector) {
                return ['success' => true, 'elector' => $elector];
            }

            return ['success' => false, 'msg' => 'Elector no encontrado'];
        } else {
            $elector = $this->searchPersonalCaracterizacionByCedula($cedula);
            if ($elector) {
                if ($elector->municipio_id != $municipio_id) {
                    return ['success' => false, 'msg' => 'Éste Elector no pertenece a este municipio'];
                }

                return ['success' => true, 'elector' => $elector];
            }

            $elector = $this->searchElectorByCedula($cedula);

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

    public function searchPersonalCaracterizacionByCedula($cedula)
    {
        $elector = PersonalCaracterizacion::where('cedula', $cedula)->first();

        return $elector;
    }

    public function searchElectorByCedula($cedula)
    {
        $elector = Elector::where('cedula', $cedula)->first();

        return $elector;
    }

    public function searchInCNE($cedula)
    {
        $response = Http::get('http://www.cne.gob.ve/web/registro_electoral/ce.php?nacionalidad=V&cedula='.$cedula);
        $response = $response->body();
        $body = explode('<td', $response);
        $nameSanitize = $body['14'];
        $name = substr($nameSanitize, 17, strpos($nameSanitize, '</b>') - 17);
        if (strlen($name) > 0) {
            return ['full_name' => $name];
        }

        return null;
    }
}
