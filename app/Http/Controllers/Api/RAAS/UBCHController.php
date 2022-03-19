<?php

namespace App\Http\Controllers\Api\RAAS;

use App\Http\Controllers\Controller;
use App\Http\Requests\RAAS\UBCH\UBCHCedulaSearchRequest;
use App\Http\Requests\RAAS\UBCH\UBCHStoreRequest;
use App\Http\Requests\RAAS\UBCH\UBCHUpdateRequest;
use App\Models\JefeComunidad;
use App\Models\JefeUbch;
use App\Traits\ElectorTrait;
use App\Traits\PersonalCaracterizacionTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UBCHController extends Controller
{
    use PersonalCaracterizacionTrait;
    use ElectorTrait;

    public function searchByCedula(Request $request)
    {
        if ($this->verificarDuplicidadCedula($request->cedula) > 0) {
            return response()->json(['success' => false, 'msg' => 'Esta cédula ya pertenece a un Jefe de UBCH']);
        }

        $response = $this->searchPersonalCaracterizacionOrElector($request->cedula, $request->nacionalidad, $request->municipio_id);

        return response()->json($response);
    }

    public function store(UBCHStoreRequest $request)
    {
        try {
            if ($this->verificarDuplicidadCedula($request->cedula) > 0) {
                return response()->json(['success' => false, 'msg' => 'Esta cédula ya pertenece a un Jefe de UBCH']);
            }

            if ($this->verificarUnSoloCentroVotacion($request->centro_votacion_id) > 0) {
                return response()->json(['success' => false, 'msg' => 'Ya existe otro jefe para ésta UBCH']);
            }

            $personalCaracterizacion = $this->getPersonalCaracterizacion($request->cedula, $request->nacionalidad);

            if ($personalCaracterizacion == null) {
                $personalCaracterizacion = $this->storePersonalCaracterizacion($request);
            }

            $jefeUbch = new JefeUbch();
            $jefeUbch->raas_personal_caracterizacion_id = $personalCaracterizacion->id;
            $jefeUbch->raas_centro_votacion_id = $request->raas_centro_votacion_id;
            $jefeUbch->save();

            return response()->json(['success' => true, 'msg' => 'Jefe de UBCH creado']);
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json(['success' => false, 'msg' => 'Ha ocurrido un problema', 'err' => $e->getMessage()]);
        }
    }

    public function verificarDuplicidadCedula($cedula)
    {
        return JefeUbch::whereHas('personalCaracterizacion', function ($q) use ($cedula) {
            $q->where('cedula', $cedula);
        })->count();
    }

    public function verificarUnSoloCentroVotacion($centro_votacion)
    {
        return  JefeUbch::where('raas_centro_votacion_id', $centro_votacion)->count();
    }

    public function jefeUbchByCedula(UBCHCedulaSearchRequest $request)
    {
        $cedula = $request->cedulaJefe;
        $jefeUbch = JefeUbch::whereHas('personalCaracterizacion', function ($q) use ($cedula) {
            $q->where('cedula', $cedula);
        })->with('personalCaracterizacion', 'centroVotacion')->first();

        if ($jefeUbch == null) {
            return response()->json(['success' => false, 'msg' => 'Jefe de UBCH no encontrado']);
        }

        if ($request->municipio_id != null) {
            if ($jefeUbch->personalCaracterizacion->municipio_id != $request->municipio_id) {
                return response()->json(['success' => false, 'msg' => 'Este Jefe de UBCH no pertenece a tu municipio ']);
            }
        }

        return response()->json($jefeUbch);
    }

    public function update(UBCHUpdateRequest $request)
    {
        try {
            /*if($this->verificarUnSoloCentroVotacionUpdate($request->centro_votacion_id, $request->id) > 0){
                return response()->json(["success" => false, "msg" => "Ya existe otro jefe para ésta UBCH"]);
            }*/

            $jefeUbch = JefeUbch::find($request->id);

            $personalCaracterizacion = $this->getPersonalCaracterizacion($request->cedula, $request->nacionalidad);

            if ($personalCaracterizacion == null) {
                $personalCaracterizacion = $this->storePersonalCaracterizacion($request);
            }

            $jefeUbch->raas_personal_caracterizacion_id = $personalCaracterizacion->id;
            $personalCaracterizacion = $this->updatePersonalCaracterizacion($jefeUbch->raas_personal_caracterizacion_id, $request);
            $jefeUbch->update();

            return response()->json(['success' => true, 'msg' => 'Jefe de UBCH actualizado']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => 'Ha ocurrido un problema', 'err' => $e->getMessage(), 'ln' => $e->getLine()]);
        }
    }

    public function verificarUnSoloCentroVotacionUpdate($centro_votacion, $jefeUbchId)
    {
        return  JefeUbch::where('centro_votacion_id', $centro_votacion)->where('id', '<>', $jefeUbchId)->count();
    }

    public function suspend(Request $request)
    {
        try {
            $jefeComunidadCount = JefeComunidad::where('jefe_ubch_id', $request->id)->count();

            if ($jefeComunidadCount > 0) {
                return response()->json(['success' => false, 'msg' => 'No se pudo eliminar el jefe de UBCH ya que tiene jefes de comunidad asociados']);
            }

            $jefeUbch = JefeUbch::find($request->id);
            $jefeUbch->delete();

            return response()->json(['success' => true, 'msg' => 'Jefe de UBCH eliminado']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => 'Ha ocurrido un problema', 'err' => $e->getMessage(), 'ln' => $e->getLine()]);
        }
    }

    public function fetch(Request $request)
    {
        $query = JefeUbch::with('centroVotacion', 'centroVotacion.parroquia', 'personalCaracterizacion', 'personalCaracterizacion.municipio', 'personalCaracterizacion.parroquia', 'personalCaracterizacion.centroVotacion', 'personalCaracterizacion.partidoPolitico', 'personalCaracterizacion.movilizacion');

        if (\Auth::user()->municipio_id != null) {
            $municipio_id = \Auth::user()->municipio_id;
            $query->whereHas('personalCaracterizacion', function ($q) use ($municipio_id) {
                $q->where('municipio_id', $municipio_id);
            });
        }

        $jefeUbch = $query->orderBy('id', 'desc')->paginate(20);

        return response()->json($jefeUbch);
    }

    public function search(Request $request)
    {
        $cedula = $request->search;
        $query = JefeUbch::with('centroVotacion', 'centroVotacion.parroquia', 'personalCaracterizacion', 'personalCaracterizacion.municipio', 'personalCaracterizacion.parroquia', 'personalCaracterizacion.centroVotacion', 'personalCaracterizacion.partidoPolitico', 'personalCaracterizacion.movilizacion');

        if ($cedula == '') {
            $jefeUbch = $query->orderBy('id', 'desc')->paginate(15);

            return response()->json($jefeUbch);
        }

        if (\Auth::user()->municipio_id != null) {
            $municipio_id = \Auth::user()->municipio_id;
            $query->whereHas('personalCaracterizacion', function ($q) use ($municipio_id) {
                $q->where('municipio_id', $municipio_id);
            });
        }

        $jefeUbch = $query->whereHas('personalCaracterizacion', function ($q) use ($cedula) {
            $q->where('cedula', $cedula);
        })->orderBy('id', 'desc')->paginate(15);

        return response()->json($jefeUbch);
    }
}
