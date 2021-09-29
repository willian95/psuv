<?php

namespace App\Http\Controllers\api\RAAS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JefeUbch;
use App\Models\JefeCalle;
use App\Models\JefeComunidad;
use App\Models\PersonalCaracterizacion;
use App\Http\Requests\RAAS\JefeComunidad\JefeComunidadStoreRequest;
use App\Http\Requests\RAAS\JefeComunidad\JefeComunidadUpdateRequest;
//use App\Http\Requests\RAAS\UBCH\UBCHCedulaSearchRequest;
use App\Traits\PersonalCaracterizacionTrait;
use App\Traits\ElectorTrait;

class JefeComunidadController extends Controller
{
    use PersonalCaracterizacionTrait;
    use ElectorTrait;

    public function searchByCedulaField( Request $request)
    {
        try {
            $cedula = $request->input('cedula');
            //Init query
            $query=JefeComunidad::query();
            //includes
            $query->with('personalCaracterizacion');
            //Filters
            if ($cedula) {
                $query->whereHas('personalCaracterizacion', function($q) use($cedula){
                    $q->where('cedula', $cedula);
                });
            }
            $entity=$query->first();
            if (!$entity) {
                throw new \Exception('Jefe de Comunidad no encontrado', 404);
            }
            $response = $this->getSuccessResponse($entity);
        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Error al Listar los registros');
        }
        return $this->response($response, $code ?? 200);
    }//index()

    function searchByCedula(Request $request){

        if($this->verificarDuplicidadCedula($request->cedula) > 0){
            return response()->json(["success" => false, "msg" => "Esta cédula ya pertenece a un Jefe de Comunidad"]);
        }

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

    function store(JefeComunidadStoreRequest $request){

        try{

            if($this->verificarDuplicidadCedula($request->cedula) > 0){
                return response()->json(["success" => false, "msg" => "Esta cédula ya pertenece a un Jefe de Comunidad"]);
            }

            if($this->verificarCedulaJefeUBCH($request->cedulaJefe) == 0){
                return response()->json(["success" => false, "msg" => "Esta cédula no le pertenece a un jefe de UBCH"]);
            }

            $personalCaracterizacion = PersonalCaracterizacion::where("cedula", $request->cedula)->first();
            
            if($personalCaracterizacion == null){
                $personalCaracterizacion = $this->storePersonalCaracterizacion($request);
            }
        
            $jefeComunidad = new JefeComunidad;
            $jefeComunidad->personal_caracterizacion_id = $personalCaracterizacion->id;
            $jefeComunidad->comunidad_id = $request->comunidad;

            $cedula = $request->cedulaJefe;
            $jefeComunidad->jefe_ubch_id = JefeUbch::whereHas('personalCaracterizacion', function($q) use($cedula){
                $q->where('cedula', $cedula);
            })->first()->id;
            $jefeComunidad->save();

            $this->updatePersonalCaracterizacion($jefeComunidad->personal_caracterizacion_id, $request);

            return response()->json(["success" => true, "msg" => "Jefe de Comunidad creado"]);
        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Ha ocurrido un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }
        

    }

    function verificarDuplicidadCedula($cedula){

        return JefeComunidad::whereHas('personalCaracterizacion', function($q) use($cedula){
            $q->where('cedula', $cedula);
        })->count();

    }  

    function verificarCedulaJefeUBCH($cedula){

        return JefeUbch::whereHas('personalCaracterizacion', function($q) use($cedula){
            $q->where('cedula', $cedula);
        })->count();

    }   

    function jefeUbchByCedula(UBCHCedulaSearchRequest $request){
        $cedula = $request->cedula;
        $jefeUbch = JefeUbch::whereHas('personalCaracterizacion', function($q) use($cedula){
            $q->where('cedula', $cedula);
        })->with("personalCaracterizacion")->first();

        if($jefeUbch){
            return response()->json($jefeUbch);
        }else{

            return response()->json(["success" => false, "msg" => "Jefe de UBCH no encontrado"]);

        }

        

    } 


    function update(JefeComunidadUpdateRequest $request){

        try{

            $jefeComunidad = JefeComunidad::find($request->id);
       
            $personalCaracterizacion = $this->updatePersonalCaracterizacion($jefeComunidad->personal_caracterizacion_id, $request);

            return response()->json(["success" => true, "msg" => "Jefe de Comunidad actualizado"]);

        }
        catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Ha ocurrido un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }
        

    }

    function suspend(Request $request){

        try{

            $jefeComunidadCount = JefeCalle::where("jefe_comunidad_id", $request->id)->count();
            
            if($jefeComunidadCount > 0){
                return response()->json(["success" => false, "msg" => "No se pudo eliminar el jefe de Comunidad ya que tiene jefes de calle asociados"]);
            }

            $jefeComunidad = JefeComunidad::find($request->id);
            $jefeComunidad->delete();

            return response()->json(["success" => true, "msg" => "Jefe de Comunidad eliminado"]);

        }
        catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Ha ocurrido un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }
        

    }

    function fetch(Request $request){

        $jefeComunidad = JefeComunidad::with("personalCaracterizacion", "personalCaracterizacion.municipio", "personalCaracterizacion.parroquia", "personalCaracterizacion.centroVotacion", "personalCaracterizacion.partidoPolitico", "personalCaracterizacion.movilizacion", "comunidad", "jefeUbch", "jefeUbch.personalCaracterizacion")->orderBy("id", "desc")->paginate(15);
        
        return response()->json($jefeComunidad);

    }
}
