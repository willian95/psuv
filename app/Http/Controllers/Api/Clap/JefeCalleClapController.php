<?php

namespace App\Http\Controllers\Api\Clap;

use App\Http\Controllers\Controller;
use App\Http\Requests\Clap\JefeCalleClapStoreRequest;
use App\Http\Requests\Clap\JefeCalleClapUpdateRequest;
use App\Models\RaasJefeCalle;
use App\Traits\ElectorTrait;
use App\Traits\PersonalCaracterizacionTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JefeCalleClapController extends Controller
{
    use PersonalCaracterizacionTrait;
    use ElectorTrait;

    public function searchByCedula(Request $request)
    {
        $response = $this->searchPersonalCaracterizacionOrElector($request->cedula, $request->nacionalidad, $request->municipio_id);

        return response()->json($response);
    }

    public function store(JefeCalleClapStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $this->storePersonalCaracterizacion($request);
            $personalCaracterizacion = $this->getPersonalCaracterizacion($request->cedula, $request->nacionalidad);
            $this->updatePersonalCaracterizacion($personalCaracterizacion->id, $request);

            if (RaasJefeCalle::where('raas_personal_caracterizacion_id', $personalCaracterizacion->id)->first()) {
                return response()->json(['success' => false, 'message' => 'Esta persona ya es Jefe calle']);
            }

            if (RaasJefeCalle::where('raas_calle_id', $request->calleId)->first()) {
                return response()->json(['success' => false, 'message' => 'Esta calle ya posee Jefe calle']);
            }

            $raasJefeCalle = new RaasJefeCalle();
            $raasJefeCalle->raas_personal_caracterizacion_id = $personalCaracterizacion->id;
            $raasJefeCalle->raas_jefe_comunidad_id = $request->jefeComunidadId;
            $raasJefeCalle->raas_calle_id = $request->calleId;
            $raasJefeCalle->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Jefe calle creado']);
        } catch (\Exception $e) {
            Log::error($e);

            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Hubo un problema']);
        }
    }

    public function update(JefeCalleClapUpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $this->storePersonalCaracterizacion($request);
            $personalCaracterizacion = $this->getPersonalCaracterizacion($request->cedula, $request->nacionalidad);
            $this->updatePersonalCaracterizacion($personalCaracterizacion->id, $request);

            if (RaasJefeCalle::where('raas_personal_caracterizacion_id', $personalCaracterizacion->id)->where('id', '<>', $request->id)->first()) {
                return response()->json(['success' => false, 'message' => 'Esta persona ya es Jefe calle']);
            }

            if (RaasJefeCalle::where('raas_calle_id', $request->calleId)->where('id', '<>', $request->id)->first()) {
                return response()->json(['success' => false, 'message' => 'Esta calle ya posee Jefe calle']);
            }

            $raasJefeCalle = RaasJefeCalle::find($id);

            if (is_null($raasJefeCalle)) {
                return response()->json(['success' => false, 'message' => 'Jefe de calle no existe']);
            }

            $raasJefeCalle->raas_personal_caracterizacion_id = $personalCaracterizacion->id;
            $raasJefeCalle->raas_jefe_comunidad_id = $request->jefeComunidadId;
            $raasJefeCalle->raas_calle_id = $request->calleId;
            $raasJefeCalle->update();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Jefe calle actualizado']);
        } catch (\Exception $e) {
            Log::error($e);

            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Hubo un problema']);
        }
    }

    public function fetch(Request $request)
    {
        $query = RaasJefeCalle::with('personalCaracterizacions', 'jefeComunidad.personalCaracterizacions', 'jefeComunidad.comunidad.parroquia.municipio', 'calle.comunidad.parroquia.municipio');

        if (isset($request->searchCedula)) {
            $query->whereHas('personalCaracterizacions', function ($query) use ($request) {
                $query->where('cedula', 'like', '%'.$request->searchCedula.'%');
            });
        }

        $jefeCalle = $query->paginate(20);

        return response()->json($jefeCalle);
    }

    public function delete($id)
    {
        RaasJefeCalle::where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => 'Jefe calle eliminado']);
    }

    public function searchJefeCalleClapByCedula(Request $request)
    {
        $jefeComunidadClap = RaasJefeCalle::whereHas('personalCaracterizacions', function ($query) use ($request) {
            $query->where('cedula', $request->cedula);
            $query->where('nacionalidad', $request->nacionalidad);
        })->with('personalCaracterizacions', 'calle')->first();

        if (is_null($jefeComunidadClap)) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Jefe calle no encontrado',
                ]
            );
        }

        return response()->json(['success' => true, 'jefe' => $jefeComunidadClap]);
    }

    function jefeCalleByCalle($calle_id){

        $jefeCalle = RaasJefeCalle::where("raas_calle_id", $calle_id)->first();
        if($jefeCalle){
            return response()->json(["success" => true, "jefe" => $jefeCalle]);
        }else{
            return response()->json(["success" => false]);
        }
    }

}
