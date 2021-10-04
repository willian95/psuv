<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Elector;
use App\Models\PersonalCaracterizacion;

class ElectorController extends Controller
{
    
    function searchByCedula(Request $request){

        if(\Auth::user()->municipio_id == null){
            $elector = $this->searchPersonalCaracterizacionByCedula($request->cedula);
            if($elector){
                return response()->json(["success" => true, "elector" => $elector]);
            }

            $elector = $this->searchElectorByCedula($request->cedula);
            if($elector){
                return response()->json(["success" => true, "elector" => $elector]);
            }

            return response()->json(["success" => false, "msg" => "Elector no encontrado"]);
        
        }else{
            
            $elector = $this->searchPersonalCaracterizacionByCedula($request->cedula, \Auth::user()->municipio_id);
            if($elector){
                
                if($elector->municipio_id != \Auth::user()->municipio_id){
                    return response()->json(["success" => false, "msg" => "Éste elector no pertence a tu municipio"]);
                }

                return response()->json(["success" => true, "elector" => $elector]);
            }

            $elector = $this->searchElectorByCedula($request->cedula, \Auth::user()->municipio_id);
            if($elector){

                if($elector->municipio_id != \Auth::user()->municipio_id){
                    return response()->json(["success" => false, "msg" => "Éste elector no pertence a tu municipio"]);
                }
             
                return response()->json(["success" => true, "elector" => $elector]);
            }

            return response()->json(["success" => false, "msg" => "Elector no encontrado"]);

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
