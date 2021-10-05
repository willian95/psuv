<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Models\Calle as Model;
use Illuminate\Http\Request;
use App\Http\Requests\Api\Users\CalleRequest as EntityRequest;
use App\Http\Controllers\Controller;

class CallesController extends Controller
{
   public function index( Request $request)
    {
        try {
            $comunidad_id = $request->input('comunidad_id');
            $auth = $request->input('auth');
            //Init query
            $query=Model::query();
            //Filters
            if ($comunidad_id) {
                $query->where('comunidad_id', $comunidad_id);
            }
            if($auth){
                $user=\Auth::user();
                if($user->municipio_id){
                    $query->whereHas('comunidad',function($query) use($user){
                        $query->whereHas('parroquia',function($query) use($user){
                            $query->where("municipio_id",$user->municipio_id);
                        });
                    });
                }
            }
            $this->addFilters($request, $query);

            $response = $this->getSuccessResponse(
               $query,
                'Listado de calles',
                $request->input('page')
            );
        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Error al Listar los registros');
        }
        return $this->response($response, $code ?? 200);
    }//index()
     
    public function show($id)
    {
        try {
            $entity=Model::where('id' , $id)->first();
            if (!$entity) {
                throw new \Exception('Calle no encontrada', 404);
            }
            $response = $this->getSuccessResponse($entity);
        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Error al consultar la información');
        }//catch
        return $this->response($response, $code ?? 200);
    }//show()

    public function store(EntityRequest $request)
    {
        try {
            DB::beginTransaction();
            //Get data
            $data=$request->all();
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
    }//

    public function update($id, EntityRequest $request)
    {
        try {
            DB::beginTransaction();
            //Get data
            $data=$request->all();
            //Find entity
            $entity=Model::find($id);
            if (!$entity) {
                throw new \Exception('Calle no encontrada', 404);
            }
            //Update data
            $entity->update($data);
            DB::commit();
            $response = $this->getSuccessResponse($entity , 'Actualización exitosa' );
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, "La actualización de datos ha fallado");
        }//catch
        return $this->response($response, $code ?? 200);
    }//update()

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $entity=Model::find($id);
            $this->validModel($entity, 'Calle no encontrada');
            $entity->delete();
            DB::commit();
            $response = $this->getSuccessResponse('', "Eliminación exitosa" );
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $msg = "Ha ocurrido un error al intentar borrar la calle";
            $response= $this->getErrorResponse($e, $msg);
        }//catch
        return $this->response($response, $code ?? 200);
    }//destroy()

}
