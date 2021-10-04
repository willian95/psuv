<?php 
namespace App\Traits;

use App\Models\PersonalCaracterizacion;
use App\Models\Elector;
use Auth;

trait ElectorTrait
{

    function searchPersonalCaracterizacionOrElector($cedula, $municipio_id){

        if($municipio_id == null){

            $elector = $this->searchPersonalCaracterizacionByCedula($cedula);
            if($elector){
                return ["success" => true, "elector" => $elector];
            }

            $elector = $this->searchElectorByCedula($cedula);
            if($elector){
                return ["success" => true, "elector" => $elector];
            }

            return ["success" => false, "msg" => "Elector no encontrado"];

        }else{

            $elector = $this->searchPersonalCaracterizacionByCedula($cedula);
            if($elector){
                
                if($elector->municipio_id != $municipio_id){
                    return ["success" => false, "msg" => "Éste Elector no pertenece a este municipio"];
                }
        
                return ["success" => true, "elector" => $elector];
            }

            $elector = $this->searchElectorByCedula($cedula);
    
            if($elector){

                if($elector->municipio_id != $municipio_id){
                    return ["success" => false, "msg" => "Éste Elector no pertenece a este municipio"];
                }
             
                return ["success" => true, "elector" => $elector];
            }

            return ["success" => false, "msg" => "Elector no encontrado"];

        }

    }

    function searchPersonalCaracterizacionByCedula($cedula){

        $elector = PersonalCaracterizacion::where("cedula", $cedula)->first();
        return $elector;

    }

    function searchElectorByCedula($cedula){

        $elector = Elector::where("cedula", $cedula)->first();
        return $elector;

    }

}