<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Models\Comunidad as Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ComunidadesController extends Controller
{
   public function index( Request $request)
    {
        try {
            $parroquia_id = $request->input('parroquia_id');
            $auth = $request->input('auth');
            //Init query
            $query=Model::query();
            //Filters
            if ($parroquia_id) {
                $query->where('parroquia_id', $parroquia_id);
            }
            if($auth){
                $user=\Auth::user();
                if($user->municipio_id){
                    $query->whereHas('parroquia',function($query) use($user){
                        $query->where("municipio_id",$user->municipio_id);
                    });
                }
            }
            $this->addFilters($request, $query);

            $response = $this->getSuccessResponse(
               $query,
                'Listado de comunidades',
                $request->input('page')
            );
        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Error al Listar los registros');
        }
        return $this->response($response, $code ?? 200);
    }//index()

}
