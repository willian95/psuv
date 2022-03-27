<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Clap\ClapRequest;
use App\Models\CensoClap;
use App\Models\CensoClap as Model;
use App\Models\CensoJefeClap;
use App\Models\Comunidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClapController extends Controller
{
    public function index(Request $request)
    {
        try {
            $search = $request->input('search');
            $comunidad_id = $request->input('comunidad_id');
            $municipio_id = $request->input('municipio_id');
            $page = $request->input('page');
            $includes = $request->input('includes') ? $request->input('includes') : [];
            //Init query
            $query = Model::query();
            //Includes
            $query->with($includes);
            //Filters
            if ($search) {
                $searchUpper = strtoupper($search);
                $query->where('nombre', 'LIKE', "%{$searchUpper}%");
            }
            if ($municipio_id) {
                $query->whereHas('comunidades', function ($query) use ($municipio_id) {
                    $query->whereHas('parroquia', function ($query) use ($municipio_id) {
                        $query->where('municipio_id', $municipio_id);
                    });
                });
            }
            $query->orderBy('created_at', 'DESC');
            if ($page) {
                $query = $query->paginate(15);
            } else {
                $query = $query->get();
            }

            return response()->json($query, 200);
            $response = $this->getSuccessResponse(
               $query,
                'Listado de calles',
                $request->input('page')
            );
        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            $response = $this->getErrorResponse($e, 'Error al Listar los registros');
        }

        return $this->response($response, $code ?? 200);
    }

    //index()

    public function show($id)
    {
        try {
            $entity = Model::where('id', $id)->first();
            if (!$entity) {
                throw new \Exception('Clap no encontrado', 404);
            }
            $response = $this->getSuccessResponse($entity);
        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            $response = $this->getErrorResponse($e, 'Error al consultar la información');
        }//catch

        return $this->response($response, $code ?? 200);
    }

    //show()

    public function store(ClapRequest $request)
    {
        try {
            DB::beginTransaction();
            //Get data
            $data = $request->all();

            $censo = new CensoClap();
            $censo->nombre = strtoupper($request->nombre);
            $censo->save();

            foreach ($request->comunidades as $comunidad) {
                $comunidadModel = Comunidad::find($comunidad);
                if ($comunidadModel->censo_clap_id == null) {
                    $comunidadModel->censo_clap_id = $censo->id;
                    $comunidadModel->update();
                }
            }

            if (Comunidad::where('censo_clap_id', $censo->id)->count() == 0) {
                $censo->delete();
                throw new \Exception('Este CLAP posee comunidades duplicadas', 301);
            }

            DB::commit();
            $response = $this->getSuccessResponse($censo, 'Registro exitoso');
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $response = $this->getErrorResponse($e, 'Registro no exitoso');
        }//catch

        return $this->response($response, $code ?? 200);
    }

    public function update($id, Request $request)
    {
        try {
            DB::beginTransaction();
            //Get data
            $data = $request->all();
            //Find entity
            $entity = Model::find($id);
            if (!$entity) {
                throw new \Exception('Calp no encontrado', 404);
            }
            //Update data

            $entity->nombre = strtoupper($request->nombre);
            $entity->update();

            DB::commit();
            $response = $this->getSuccessResponse($entity, 'Actualización exitosa');
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $response = $this->getErrorResponse($e, 'La actualización de datos ha fallado');
        }//catch

        return $this->response($response, $code ?? 200);
    }

    //update()

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $entity = Model::find($id);
            $this->validModel($entity, 'Clap no encontrado');
            $jefeCalle = CensoJefeClap::where('censo_clap_id', $id)->first();
            if ($jefeCalle) {
                throw new \Exception('Este Clap no puede ser eliminado, ya que uno o mas jefes de calles están asociados a ella.', 404);
            }

            Comunidad::where('censo_clap_id', $id)->update(['censo_clap_id' => null]);

            $entity->delete();
            DB::commit();
            $response = $this->getSuccessResponse('', 'Eliminación exitosa');
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $msg = 'Ha ocurrido un error al intentar borrar la calle';
            $response = $this->getErrorResponse($e, $msg);
        }//catch

        return $this->response($response, $code ?? 200);
    }

    //destroy()
}
