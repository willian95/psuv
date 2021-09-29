<?php 
namespace App\Traits;

use App\Models\PersonalCaracterizacion;
use App\Models\Elector;
use Auth;

trait ElectorTrait
{

    function searchPersonalCaracterizacionByCedula($cedula){

        $elector = PersonalCaracterizacion::where("cedula", $cedula)->first();
        return $elector;

    }

    function searchElectorByCedula($cedula){

        $elector = Elector::where("cedula", $cedula)->first();
        return $elector;

    }

}