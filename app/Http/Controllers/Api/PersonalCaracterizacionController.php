<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Models\PersonalCaracterizacion as Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PersonalCaracterizacionExport;
class PersonalCaracterizacionController extends Controller
{
   public function index( Request $request)
    {
        try {
            $estado_id = $request->input('estado_id');
            $municipio_id = $request->input('municipio_id');
            $parroquia_id = $request->input('parroquia_id');
            $centro_votacion_id = $request->input('centro_votacion_id');
            //Init query
            $query=Model::query();
            //Filters
            if ($estado_id) {
                $query->where('estado_id', $estado_id);
            }
            if ($municipio_id) {
                $query->where('municipio_id', $municipio_id);
            }
            if ($parroquia_id) {
                $query->where('parroquia_id', $parroquia_id);
            }
            if ($centro_votacion_id) {
                $query->where('centro_votacion_id', $centro_votacion_id);
            }
            $this->addFilters($request, $query);

            $response = $this->getSuccessResponse(
               $query,
                'Listado de personas caracterizadas',
                $request->input('page')
            );
        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Error al listar los registros');
        }
        return $this->response($response, $code ?? 200);
    }//index()

    public function exportToExcel( Request $request)
    {
        try {
            $now=\Carbon\Carbon::now()->format('d-m-Y H:i:s');
            return Excel::download(new PersonalCaracterizacionExport($request),'Reporte RAAS '.$now.".xlsx");
        } catch (\Exception $e) {
            abort(404);
            // $code = $this->getCleanCode($e);
            // $response= $this->getErrorResponse($e, 'Error al listar los registros');
        }
    }//index()

}
