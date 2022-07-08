<?php

namespace App\Http\Controllers\api\RAAS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JefeCalle as Model;
use App\Http\Requests\RAAS\JefeCalle\StoreRequest as StoreRequest;
use App\Http\Requests\RAAS\JefeCalle\UpdateRequest as UpdateRequest;
use App\Traits\PersonalCaracterizacionTrait;
use App\Models\PersonalCaracterizacion;
use App\Models\Elector;
use DB;
class JefeCalleController extends Controller
{
    use PersonalCaracterizacionTrait;

    public function index( Request $request)
    {
        try {
            $search = $request->input('search');
            $calle_id = $request->input('calle_id');
            $personal_caracterizacion_id = $request->input('personal_caracterizacion_id');
            $jefe_comunidad_id = $request->input('jefe_comunidad_id');
            $includes= $request->input('includes') ? $request->input('includes') : [
                "personalCaracterizacion.movilizacion",
                "personalCaracterizacion.partidoPolitico",
                "jefeComunidad.personalCaracterizacion",
                "jefeComunidad.comunidad",
                "jefeComunidad.comunidades",
                "calle"
            ];
            //Init query
            $query=Model::query();
            //Includes
            $query->with($includes);
            //Filters
            if ($calle_id) {
                $query->where('calle_id', $calle_id);
            }
            if ($personal_caracterizacion_id) {
                $query->where('personal_caracterizacion_id', $personal_caracterizacion_id);
            }
            if ($jefe_comunidad_id) {
                $query->where('jefe_comunidad_id', $jefe_comunidad_id);
            }
            if ($search) {
                $query->whereHas('personalCaracterizacion',function($query) use($search){
                    $query->where("cedula","LIKE","%{$search}%")
                    ->orWhere("primer_nombre","LIKE","%{$search}%")
                    ->orWhere("primer_apellido","LIKE","%{$search}%")
                    ->orWhere("segundo_nombre","LIKE","%{$search}%")
                    ->orWhere("segundo_apellido","LIKE","%{$search}%");
                });
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
            $elector=PersonalCaracterizacion::whereCedula($data['personal_caraterizacion']['cedula'])->first();
            if(!$elector){
                $elector=Elector::whereCedula($data['personal_caraterizacion']['cedula'])->first();
                if(!$elector){
                    throw new \Exception('Elector jefe de calle no encontrado.', 404);
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
            //
            $exist=Model::where('personal_caraterizacion_id',$elector->id)
            ->where("calle_id",$data['calle_id'])->first();
            if($exist){
                throw new \Exception('Este elector ya ha sido registrado como jefe de esta calle.', 404);
            }
            //Cada calle solo puede tener un jefe de calle
            $exist=Model::where("calle_id",$data['calle_id'])->first();
            if($exist){
                throw new \Exception('Esta calle, ya posee un jefe de calle: '.$exist->personalCaracterizacion->full_name, 404);
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
                throw new \Exception('Jefe de calle no encontrado', 404);
            }
            //Preguntar validación: Si ya existe el mismo jefe de calle para esta calle.
            //Get data
            $data=$request->all();
            //Operations
            $data['personal_caraterizacion']=json_decode($data['personal_caraterizacion']);
            $elector=PersonalCaracterizacion::whereCedula($data['personal_caraterizacion']->cedula)->first();
            if(!$elector){
                $elector=Elector::whereCedula($data['personal_caraterizacion']->cedula)->first();
                if(!$elector){
                    throw new \Exception('Elector jefe de calle no encontrado.', 404);
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
                PersonalCaracterizacion::whereCedula($data['personal_caraterizacion']->cedula)->update([
                    "telefono_principal"=>$request->telefono_principal,
                    "telefono_secundario"=>$request->telefono_secundario,
                    "tipo_voto"=>$request->tipo_voto,
                    "partido_politico_id"=>$request->partido_politico_id,
                    "movilizacion_id"=>$request->movilizacion_id,
                ]);
            }//exist & update
            $exist=Model::where('personal_caraterizacion_id',$elector->id)
            ->where("calle_id",$data['calle_id'])->where("id","!=",$id)->first();
            if($exist){
                throw new \Exception('Este elector ya ha sido registrado como jefe de esta calle.', 404);
            }
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

    
    function delete($id,Request $request){

        try {
            DB::beginTransaction();
            //Find entity
            $entity=Model::find($id);
            if (!$entity) {
                throw new \Exception('Jefe de calle no encontrado', 404);
            }
            //Preguntar validación: Si ya existe el mismo jefe de calle para esta calle.
            if(count($entity->jefeFamilias)>0){
                throw new \Exception('Este jefe de calle posee 1 o más jefes de familia asignados, por favor reasignar los jefes de familia a otro jefe de calle, para proceder a eliminar este', 404);
            }
            //Create entity
            $entity->delete();
            DB::commit();
            $response = $this->getSuccessResponse($entity,"Eliminación exitosa");
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Eliminación no exitosa');

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
            $query->with('personalCaracterizacion',"calle","calles.calle");
            if ($cedula) {
                $query->whereHas('personalCaracterizacion', function($q) use($cedula){
                    $q->where('cedula', $cedula);
                });
            }//cedula
            $entity=$query->first();
            if (!$entity) {
                throw new \Exception('Jefe de Calle no encontrado', 404);
            }

            if(\Auth::user()->municipio_id != null){

                if($entity->personalCaracterizacion->municipio_id != \Auth::user()->municipio_id){
                    return response()->json(["success" => false, "msg" => "Éste jefe de comunidad no pertenece a tu municipio"]);
                }

            }

            $response = $this->getSuccessResponse($entity);
        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Error al Listar los registros');
        }
        return $this->response($response, $code ?? 200);
    }//searchByCedulaField()

    
    public function importacionJefeCalle(Request $request){
        $jefeCalles = \Maatwebsite\Excel\Facades\Excel::toArray(new \App\Imports\BaseImport, $request->file('file'));
        $data['datos'] = $jefeCalles[0];
        $validation = \Validator::make($data, [
            "datos.*.nombre_comunidad" => 'required|string',
            "datos.*.comunidad_parroquia_id" => 'required|string',
            "datos.*.nombre_calle" => "required|string",
            "datos.*.cedula_jefe_comunidad" => "required|numeric",
            "datos.*.cedula_persona" => 'required|numeric',
       ]);

       if ($validation->fails()) {
            return response()->json([
                "message"=>'Algunos datos en el excel no son validos',
                "errors"=>$validation->errors()
            ],400);
       }
       $response=[
           "importados"=>0,
           "errores"=>[]
       ];
       $data['datos']=json_decode(json_encode($data['datos']));
       foreach($data["datos"] as $jefe){
            $comunidad=\App\Models\Comunidad::where("nombre",$jefe->nombre_comunidad)
            ->where("raas_parroquia_id",$jefe->comunidad_parroquia_id)
            ->first();
            if(!$comunidad){
                $response["errores"][]=[
                    "datos"=>$jefe,
                    "motivo"=>"La comunidad: ".$jefe->nombre_comunidad." no existe"
                ];
                break;
            }
            $calle=\App\Models\Calle::where("nombre",$jefe->nombre_calle)
            ->where("raas_comunidad_id",$comunidad->id)
            ->first();
            if(!$calle){
                $calle=\App\Models\Calle::create([
                    "nombre"=>$jefe->nombre_calle,
                    "raas_comunidad_id"=>$comunidad->id
                ]);
                // $response["errores"][]=[
                //     "datos"=>$jefe,
                //     "motivo"=>"La calle: ".$jefe->nombre_calle." no existe"
                // ];
                // break;
            }
            $jefeComunidad=\App\Models\JefeComunidad::whereHas("personalCaracterizacion",function($query)use($jefe,$comunidad){
                $query->where("cedula",$jefe->cedula_jefe_comunidad)
                ->where("raas_comunidad_id",$comunidad->id)
                ;
            })
            ->first();
            if(!$jefeComunidad){
                $response["errores"][]=[
                    "datos"=>$jefe,
                    "motivo"=>"El jefe de comunidad con la cédula: ".$jefe->cedula_jefe_comunidad." no existe"
                ];
                break;
            }
            $personalCaracterizacion=\App\Models\PersonalCaracterizacion::where("cedula",$jefe->cedula_persona)
            ->first();
            if(!$personalCaracterizacion){
                $elector=\App\Models\Elector::where("cedula",$jefe->cedula_persona)->first();
                if($elector){
                    $personalCaracterizacion=\App\Models\PersonalCaracterizacion::create([
                        "cedula"=>$jefe->cedula_persona,
                        "nombre_apellido"=>$elector->nombre_apellido,
                        "sexo"=>$elector->sexo,
                        "nacionalidad"=>$elector->nacionalidad,
                        "raas_estado_id"=>$elector->raas_estado_id,
                        "raas_municipio_id"=>$elector->raas_municipio_id,
                        "raas_parroquia_id"=>$elector->raas_parroquia_id,
                        "raas_centro_votacion_id"=>$elector->raas_centro_votacion_id,
                        "telefono_principal"=>null,
                        "telefono_secundario"=>null,
                        "tipo_voto"=>null,
                        "fecha_nacimiento"=>null,
                        "raas_estatus_personal_id"=>null,
                        "elecciones_partido_politico_id"=>null,
                        "elecciones_movilizacion_id"=>null,
                    ]);
                }else{
                    $response["errores"][]=[
                        "datos"=>$jefe,
                        "motivo"=>"No existe personal registrado con esta cédula: ".$jefe->cedula_persona
                    ];
                    break;
                }
            }
            $dataJefe=\App\Models\JefeCalle::where("raas_calle_id",$calle->id)
            ->where("raas_personal_caraterizacion_id",$personalCaracterizacion->id)
            ->where("raas_jefe_comunidad_id",$jefeComunidad->id)
            ->first();
            if($dataJefe){
                $response["errores"][]=[
                    "datos"=>$jefe,
                    "motivo"=>"Ya este jefe de calle se encuentra registrado para esta calle: ".$calle->nombre
                ];
                break;
            }
            $dataJefe=\App\Models\JefeCalle::create([
                "raas_personal_caraterizacion_id"=>$personalCaracterizacion->id,
                "raas_calle_id"=>$calle->id,
                "raas_jefe_comunidad_id"=>$jefeComunidad->id,
            ]);
            $response["importados"]=$response["importados"]+1;
        }//jefes foreach
        return response()->json([
            "data"=>$response,
            "message"=>"Jefes de calle importados exitosamente: ".$response["importados"]
        ],200);
    }

}
