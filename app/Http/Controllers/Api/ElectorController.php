<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Elector;
use App\Models\PersonalCaracterizacion;

class ElectorController extends Controller
{
    
    function searchByCedula(Request $request){

        $elector = $this->searchPersonalCaracterizacionByCedula($request->cedula);
        
        if($elector){
            return response()->json(["success" => true, "elector" => $elector]);
        }

        $elector = $this->searchElectorByCedula($request->cedula);
        if($elector){
            return response()->json(["success" => true, "elector" => $elector]);
        }

        return response()->json(["success" => false, "msg" => "Elector no encontrado"]);
        
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
