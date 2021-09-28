<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Models\Calle as Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CallesController extends Controller
{
   public function index( Request $request)
    {
        try {
            $comunidad_id = $request->input('comunidad_id');
            //Init query
            $query=Model::query();
            //Filters
            if ($comunidad_id) {
                $query->where('comunidad_id', $comunidad_id);
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

}
