<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Comunidad\ComunidadStoreRequest;
use App\Http\Requests\Comunidad\ComunidadUpdateRequest;
use App\Models\Calle;
use App\Models\Comunidad;

class ComunidadController extends Controller
{
    
    function comunidadesByParroquia($parroquia){

        return response()->json(Comunidad::where("parroquia_id", $parroquia)->get());

    }

    function store(ComunidadStoreRequest $request){

        try{    

            $comunidad = new Comunidad;
            $comunidad->parroquia_id = $request->parroquia_id;
            $comunidad->nombre = strtoupper($request->nombre);
            $comunidad->save();

            return response()->json(["success" => true, "msg" => "Comunidad creada"]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Ha ocurrido un problema", "err" => $e->getMessage()]);

        }

    }

    function update(ComunidadUpdateRequest $request){

        try{    

            $comunidad = Comunidad::find($request->id);
            $comunidad->parroquia_id = $request->parroquia_id;
            $comunidad->nombre = strtoupper($request->nombre);
            $comunidad->update();

            return response()->json(["success" => true, "msg" => "Comunidad actualizada"]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Ha ocurrido un problema", "err" => $e->getMessage()]);

        }

    }

    function delete(Request $request){

        try{    

            if(Calle::where("comunidad_id", $request->id)->count() > 0){
                return response()->json(["success" => false, "msg" => "No es posible eliminar debido a que hay calles asociadas"]);
            }

            $comunidad = Comunidad::find($request->id);
            $comunidad->delete();

            return response()->json(["success" => true, "msg" => "Comunidad eliminada"]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Ha ocurrido un problema", "err" => $e->getMessage()]);

        }

    }

    function fetch(Request $request){

        $comunidades = Comunidad::with("parroquia", "parroquia.municipio")->orderBy("id", "desc")->paginate(15);
        
        return response()->json($comunidades);

    }

    function search(Request $request){

        $comunidades = Comunidad::where('nombre', 'like', '%' . strtoupper($request->search) . '%')->with("parroquia", "parroquia.municipio")->orderBy("id", "desc")->paginate(15);
        
        return response()->json($comunidades);

    }

}
