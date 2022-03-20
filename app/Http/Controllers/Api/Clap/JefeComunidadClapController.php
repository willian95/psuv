<?php

namespace App\Http\Controllers\Api\Clap;

use App\Http\Controllers\Controller;
use App\Http\Requests\Clap\JefeComunidadCLAPStoreRequest;
use App\Http\Requests\Clap\JefeComunidadCLAPUpdateRequest;
use App\Models\RaasJefeComunidad;
use App\Traits\ElectorTrait;
use App\Traits\PersonalCaracterizacionTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JefeComunidadClapController extends Controller
{
    use PersonalCaracterizacionTrait;
    use ElectorTrait;

    public function searchByCedula(Request $request)
    {
        $response = $this->searchPersonalCaracterizacionOrElector($request->cedula, $request->nacionalidad, $request->municipio_id);

        return response()->json($response);
    }

    public function store(JefeComunidadCLAPStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $this->storePersonalCaracterizacion($request);
            $personalCaracterizacion = $this->getPersonalCaracterizacion($request->cedula, $request->nacionalidad);

            if (RaasJefeComunidad::where('raas_personal_caracterizacion_id', $personalCaracterizacion->id)->first()) {
                return response()->json(['success' => false, 'message' => 'Esta persona ya es Jefe comunidad CLAP']);
            }

            $censoJefeClap = new RaasJefeComunidad();
            $censoJefeClap->raas_personal_caracterizacion_id = $personalCaracterizacion->id;
            $censoJefeClap->censo_jefe_clap_id = $request->jefeClapId;
            $censoJefeClap->raas_comunidad_id = $request->comunidadId;
            $censoJefeClap->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Jefe comunidad CLAP creado']);
        } catch (\Exception $e) {
            Log::error($e);

            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Hubo un problema']);
        }
    }

    public function update(JefeComunidadCLAPUpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $this->storePersonalCaracterizacion($request);
            $personalCaracterizacion = $this->getPersonalCaracterizacion($request->cedula, $request->nacionalidad);

            if (RaasJefeComunidad::where('raas_personal_caracterizacion_id', $personalCaracterizacion->id)->where('id', '<>', $request->id)->first()) {
                return response()->json(['success' => false, 'message' => 'Esta persona ya es Jefe comunidad CLAP']);
            }

            $censoJefeClap = RaasJefeComunidad::find($id);

            if (is_null($censoJefeClap)) {
                return response()->json(['success' => false, 'message' => 'Jefe de comunidad no existe']);
            }

            $censoJefeClap->raas_personal_caracterizacion_id = $personalCaracterizacion->id;
            $censoJefeClap->censo_jefe_clap_id = $request->jefeClapId;
            $censoJefeClap->raas_comunidad_id = $request->comunidadId;
            $censoJefeClap->update();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Jefe comunidad CLAP actualizado']);
        } catch (\Exception $e) {
            Log::error($e);

            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Hubo un problema']);
        }
    }

    public function fetch(Request $request)
    {
        $query = RaasJefeComunidad::with('personalCaracterizacions', 'jefeClap.personalCaracterizacions', 'jefeClap.enlaceMunicipal.municipio', 'comunidad');

        if (isset($request->searchCedula)) {
            $query->whereHas('personalCaracterizacions', function ($query) use ($request) {
                $query->where('cedula', 'like', '%'.$request->searchCedula.'%');
            });
        }

        $jefeComunidad = $query->paginate(20);

        return response()->json($jefeComunidad);
    }

    public function delete($id)
    {
        RaasJefeComunidad::where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => 'Jefe comunidad eliminado']);
    }
}
