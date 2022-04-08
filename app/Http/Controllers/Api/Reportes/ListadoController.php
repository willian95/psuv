<?php

namespace App\Http\Controllers\Api\Reportes;

use App\Exports\listados\EstructuraCLAP;
use App\Exports\listados\JefeCalle;
use App\Exports\listados\JefeCLAP;
use App\Exports\listados\JefeComunidad;
use App\Exports\listados\JefeEnlaceMunicipal;
use App\Exports\listados\JefeUBCH as ListadosJefeUBCH;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JefeUbch;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;

class ListadoController extends Controller
{
    
    function download(Request $request){

        $data = null;
        $name = "";

        $condition = "";
            
        if($request->municipio != "0"){

            $condition .= ' AND mu.id='.$request->municipio;

        }
        
        if($request->parroquia != "0"){

            $condition .= ' AND pa.id='.$request->parroquia;

        }

        if($request->comunidad != "0"){

            $condition .= ' AND rc.id='.$request->comunidad;

        }

        if($request->type == "1"){
        
            return Excel::download((new ListadosJefeUBCH)->forCondition($condition), 'ListadoJefeUBCH'.uniqid().'.xlsx');
            
        }

        else if($request->type == "2"){
            
            return Excel::download((new JefeEnlaceMunicipal)->forCondition($condition), 'ListadoEnlaceMunicipal'.uniqid().'.xlsx');
        }

        else if($request->type == "3"){
        
            return Excel::download((new JefeCLAP)->forCondition($condition), 'ListadoJefeClap'.uniqid().'.xlsx');
        }

        else if($request->type == "4"){
        
            return Excel::download((new JefeCalle)->forCondition($condition), 'ListadoJefeCalle'.uniqid().'.xlsx');
        }

        else if($request->type == "5"){
        
            return Excel::download((new JefeComunidad)->forCondition($condition), 'ListadoJefeComunidad'.uniqid().'.xlsx');
        }

    }


    function downloadEstructuraClap(Request $request){

        ini_set('max_execution_time', '0'); 

        $condition = "";
            
        if($request->municipio != "0"){

            $condition .= ' AND mu.id='.$request->municipio;

        }
        
        if($request->parroquia != "0"){

            $condition .= ' AND pa.id='.$request->parroquia;

        }

        return Excel::download((new EstructuraCLAP)->forCondition($condition), 'EstructuraCLAP'.uniqid().'.xlsx');

    }


}
