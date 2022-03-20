<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comunidad as Model;
use Illuminate\Http\Request;

class ComunidadesController extends Controller
{
    public function index(Request $request)
    {
        try {
            $parroquia_id = $request->input('parroquia_id');
            $municipio_id = $request->input('municipio_id');
            $auth = $request->input('auth');
            //Init query
            $query = Model::query();
            //Filters
            if ($parroquia_id) {
                $query->where('parroquia_id', $parroquia_id);
            }
            if ($municipio_id) {
                $query->whereHas('parroquia', function ($query) use ($municipio_id) {
                    $query->where('municipio_id', $municipio_id);
                });
            }
            $this->addFilters($request, $query);

            $response = $this->getSuccessResponse(
               $query,
                'Listado de comunidades',
                $request->input('page')
            );
        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            $response = $this->getErrorResponse($e, 'Error al Listar los registros');
        }

        return $this->response($response, $code ?? 200);
    }

    //index()
}
