<?php

namespace App\Http\Controllers\Api\CierreMesa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mesa;
use Carbon\Carbon;
use App\Models\Eleccion;
use App\Models\CierreCandidatoVotacion;
use DB;

class CandidatoController extends Controller
{
    
    function getMesasCerradas(Request $request){

        $municipio = $request->municipio_id;
        if($municipio == 0){
            $mesas = Mesa::where("transmision", true)->with("centroVotacion", "centroVotacion.parroquia", "centroVotacion.parroquia.municipio")->paginate(20);
        }else{
            $mesas = Mesa::where("transmision", true)->whereHas("centroVotacion.parroquia", function($q) use($municipio){

                $q->where("municipio_id", $municipio);

            })->with("centroVotacion", "centroVotacion.parroquia", "centroVotacion.parroquia.municipio")->paginate(20);
        }
        
        return response()->json($mesas);

    }

    function searchCentroVotacion(Request $request){

        $municipio = $request->municipio_id;
        $search = $request->search;
        
        if($municipio == 0){

            if($search == ""){
                $mesas = Mesa::where("transmision", true)->with("centroVotacion", "centroVotacion.parroquia", "centroVotacion.parroquia.municipio")->paginate(20);
            }else{
                $mesas = Mesa::where("transmision", true)->whereHas("centroVotacion", function($q) use($search){
                    $q->where("nombre", $search);
                })->with("centroVotacion", "centroVotacion.parroquia", "centroVotacion.parroquia.municipio")->paginate(20);
            }

            

        }else{

            if($search == ""){

                $mesas = Mesa::where("transmision", true)->whereHas("centroVotacion.parroquia", function($q) use($municipio){

                    $q->where("municipio_id", $municipio);
    
                })->with("centroVotacion", "centroVotacion.parroquia", "centroVotacion.parroquia.municipio")->paginate(20);

            }else{

                $mesas = Mesa::where("transmision", true)
                ->whereHas("centroVotacion", function($q) use($search){
                    $q->where("nombre", $search);
                })
                ->whereHas("centroVotacion.parroquia", function($q) use($municipio){

                    $q->where("municipio_id", $municipio);

                })->with("centroVotacion", "centroVotacion.parroquia", "centroVotacion.parroquia.municipio")->paginate(20);

            }

            
        }
        
        return response()->json($mesas);

    }

    function getMesasByCentroVotacion($centro_votacion_id){

        $mesas = Mesa::where("centro_votacion_id", $centro_votacion_id)->where("transmision", false)->get();
        return response()->json($mesas);

    }

    function updateCierreMesa(Request $request){

        $mesa = Mesa::find($request->mesaId);
        $mesa->transmision = true;
        $mesa->hora_transmision = Carbon::createFromFormat('H:iA',  $request->hora.":".$request->minuto.$request->meridiano);
        $mesa->update();

        return response()->json(["success" => true, "msg" => "Cierre de mesa realizado"]);

    }

    function getCandidatos(Request $request){

        $query = "SELECT candidatos.id, foto, (candidatos.nombre||' '||candidatos.apellido) candidato, cargo_eleccion FROM public.candidatos left join municipio on municipio.id=municipio_id where cargo_eleccion='GOBERNADOR' or municipio.id='".$request->municipio_id."' order by candidato";
        
        $candidatos = DB::select(DB::raw($query));

        return response()->json($candidatos);

    }

    function storeResults(Request $request){

        foreach($request->results as $result){

            $cierreCandidatoVotacion = new CierreCandidatoVotacion;
            $cierreCandidatoVotacion->mesa_id = $request->mesaId;
            $cierreCandidatoVotacion->candidatos_id = $result["id"];
            $cierreCandidatoVotacion->eleccion_id = Eleccion::orderBy("id", "desc")->first()->id;
            $cierreCandidatoVotacion->cantidad_voto = $result["votos"];
            $cierreCandidatoVotacion->save();

        }

        return response()->json(["success" => true, "msg" => "Resultados agregados exitosamente"]);

    }

    function getResultados($mesa_id){

        $resultados = CierreCandidatoVotacion::where("mesa_id", $mesa_id)->with("candidato")->get();

        return response()->json($resultados);

    }
    

}
