<?php

namespace App\Http\Controllers\api\RAAS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JefeFamilia as Model;
use App\Http\Requests\RAAS\JefeFamilia\StoreRequest as StoreRequest;
use App\Http\Requests\RAAS\JefeFamilia\UpdateRequest as UpdateRequest;
use App\Traits\PersonalCaracterizacionTrait;
use App\Models\PersonalCaracterizacion;
use App\Models\Elector;
use DB;
class JefeFamiliaController extends Controller
{
    use PersonalCaracterizacionTrait;

    public function index( Request $request)
    {
        try {
            $personal_caracterizacion_id = $request->input('personal_caracterizacion_id');
            $jefe_calle_id = $request->input('jefe_calle_id');
            $includes= $request->input('includes') ? $request->input('includes') : [
                "jefeCalle.personalCaracterizacion",
                "personalCaracterizacion.movilizacion",
                "personalCaracterizacion.partidoPolitico"
            ];
            //Init query
            $query=Model::query();
            //Includes
            $query->with($includes);
            //Filters
            if ($personal_caracterizacion_id) {
                $query->where('personal_caracterizacion_id', $personal_caracterizacion_id);
            }
            if ($jefe_calle_id) {
                $query->where('jefe_calle_id', $jefe_calle_id);
            }
            // $this->addFilters($request, $query);
            
            $query->orderBy("created_at","DESC");
            $query=$query->paginate(15);
            return response()->json($query);

            // $response = $this->getSuccessResponse(
            //    $query,
            //     'Listado de calles',
            //     $request->input('page')
            // );
        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Error al Listar los registros');
        }
        return $this->response($response, $code ?? 200);
    }//index()

    function store(StoreRequest $request){

        try {
            DB::beginTransaction();
            //Get data
            $data=$request->all();
            //Operations
            $elector=PersonalCaracterizacion::whereCedula($data['personal_caracterizacion']['cedula'])->first();
            if(!$elector){
                $elector=Elector::whereCedula($data['personal_caracterizacion']['cedula'])->first();
                if(!$elector){
                    throw new \Exception('Elector jefe de familia no encontrado.', 404);
                }else{
                    //Create
                    $elector=PersonalCaracterizacion::create([
                        "cedula"=>$elector->cedula,
                        "nacionalidad"=>$elector->nacionalidad,
                        "primer_apellido"=>$elector->primer_apellido,
                        "segundo_apellido"=>$elector->segundo_apellido,
                        "primer_nombre"=>$elector->primer_nombre,
                        "segundo_nombre"=>$elector->segundo_nombre,
                        "sexo"=>$elector->sexo,
                        "fecha_nacimiento"=>$elector->fecha_nacimiento,
                        "estado_id"=>$elector->estado_id,
                        "municipio_id"=>$elector->municipio_id,
                        "parroquia_id"=>$elector->parroquia_id,
                        "centro_votacion_id"=>$elector->centro_votacion_id,
                        "telefono_principal"=>$request->telefono_principal,
                        "telefono_secundario"=>$request->telefono_secundario,
                        "tipo_voto"=>$request->tipo_voto,
                        "partido_politico_id"=>$request->partido_politico_id,
                        "movilizacion_id"=>$request->movilizacion_id,
                    ]);
                    $data['personal_caraterizacion_id']=$elector->id;
                }   
            }else{
                $data['personal_caraterizacion_id']=$elector->id;
            }
            //validate exist
            $exist=Model::where('personal_caraterizacion_id',$elector->id)->first();
            if($exist){
                throw new \Exception('Este jefe de familia ya fue registrado en otra ocasión.', 400);
            }
            //Create entity
            $entity=Model::create($data);
            DB::commit();
            $response = $this->getSuccessResponse($entity,"Registro exitoso");
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Registro no exitoso');

        }//catch
        return $this->response($response, $code ?? 200);        

    }

    function update($id,UpdateRequest $request){

        try {
            DB::beginTransaction();
            //Find entity
            $entity=Model::find($id);
            if (!$entity) {
                throw new \Exception('Jefe de familia no encontrado', 404);
            }
            //Get data
            $data=$request->all();
            //Operations
            $data['personal_caracterizacion']=json_decode($data['personal_caracterizacion']);
            $elector=PersonalCaracterizacion::whereCedula($data['personal_caracterizacion']->cedula)->first();
            if(!$elector){
                $elector=Elector::whereCedula($data['personal_caracterizacion']->cedula)->first();
                if(!$elector){
                    throw new \Exception('Elector jefe de familia no encontrado.', 404);
                }else{
                    //Create
                    $elector=PersonalCaracterizacion::create([
                        "cedula"=>$elector->cedula,
                        "nacionalidad"=>$elector->nacionalidad,
                        "primer_apellido"=>$elector->primer_apellido,
                        "segundo_apellido"=>$elector->segundo_apellido,
                        "primer_nombre"=>$elector->primer_nombre,
                        "segundo_nombre"=>$elector->segundo_nombre,
                        "sexo"=>$elector->sexo,
                        "fecha_nacimiento"=>$elector->fecha_nacimiento,
                        "estado_id"=>$elector->estado_id,
                        "municipio_id"=>$elector->municipio_id,
                        "parroquia_id"=>$elector->parroquia_id,
                        "centro_votacion_id"=>$elector->centro_votacion_id,
                        "telefono_principal"=>$request->telefono_principal,
                        "telefono_secundario"=>$request->telefono_secundario,
                        "tipo_voto"=>$request->tipo_voto,
                        "partido_politico_id"=>$request->partido_politico_id,
                        "movilizacion_id"=>$request->movilizacion_id,
                    ]);
                    $data['personal_caraterizacion_id']=$elector->id;
                }   
            }else{
                $data['personal_caraterizacion_id']=$elector->id;
                PersonalCaracterizacion::whereCedula($data['personal_caracterizacion']->cedula)->update([
                    "telefono_principal"=>$request->telefono_principal,
                    "telefono_secundario"=>$request->telefono_secundario,
                    "tipo_voto"=>$request->tipo_voto,
                    "partido_politico_id"=>$request->partido_politico_id,
                    "movilizacion_id"=>$request->movilizacion_id,
                ]);
            }//exist & update
            //Create entity
            $entity->update($data);
            DB::commit();
            $response = $this->getSuccessResponse($entity,"Actualización exitosa");
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Actualización no exitosa');

        }//catch
        return $this->response($response, $code ?? 200);        

    }

    // function suspend(JefeComunidadUpdateRequest $request){

    //     try{

    //         if($this->verificarDuplicidadCedula($request->cedula) > 0){
    //             return response()->json(["success" => false, "msg" => "Esta cédula ya pertenece a un Jefe de Comunidad"]);
    //         }

    //         $jefeComunidad = JefeComunidad::find($request->id);
    //         $jefeUbchId = $jefeComunidad->jefe_ubch_id;
    //         $comunidadId = $jefeComunidad->comunidad_id;
    //         $jefeComunidad->delete();

    //         $personalCaracterizacion = PersonalCaracterizacion::where("cedula", $request->cedula)->first();
            
    //         if($personalCaracterizacion == null){
    //             $personalCaracterizacion = $this->storePersonalCaracterizacion($request);
    //         }

    //         $jefeComunidad = new JefeComunidad;
    //         $jefeComunidad->personal_caracterizacion_id = $personalCaracterizacion->id;
    //         $jefeComunidad->comunidad_id = $comunidadId;
    //         $jefeComunidad->jefe_ubch_id = $jefeUbchId;
    //         $jefeComunidad->save();

    //         $personalCaracterizacion = $this->updatePersonalCaracterizacion($jefeComunidad->personal_caracterizacion_id, $request);

    //         return response()->json(["success" => true, "msg" => "Jefe de Comunidad suspendido y sustituido"]);

    //     }
    //     catch(\Exception $e){

    //         return response()->json(["success" => false, "msg" => "Ha ocurrido un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

    //     }
        

    // }//suspend

    public function searchByCedulaField($cedula){
        try {
            //Init query
            $query=Model::query();
            //includes
            $query->with('personalCaracterizacion');
            if ($cedula) {
                $query->whereHas('personalCaracterizacion', function($q) use($cedula){
                    $q->where('cedula', $cedula);
                });
            }//cedula
            $entity=$query->first();
            if (!$entity) {
                throw new \Exception('Jefe de Calle no encontrado', 404);
            }
            $response = $this->getSuccessResponse($entity);
        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Error al Listar los registros');
        }
        return $this->response($response, $code ?? 200);
    }//searchByCedulaField()

}
