<?php

namespace App\Http\Controllers\Api\Clap;

use App\Http\Controllers\Controller;
use App\Traits\ElectorTrait;
use App\Traits\PersonalCaracterizacionTrait;
use App\Http\Requests\Clap\JefeFamilia\StoreRequest;
use Illuminate\Http\Request;
use App\Models\JefeFamilia;
use Illuminate\Support\Facades\DB;

class JefeFamiliaController extends Controller
{

    use PersonalCaracterizacionTrait;
    use ElectorTrait;

    public function searchByCedula(Request $request)
    {
        $response = $this->searchPersonalCaracterizacionOrElector($request->cedula, $request->nacionalidad, $request->municipio_id);

        return response()->json($response);
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $this->storePersonalCaracterizacion($request);
            $personalCaracterizacion = $this->getPersonalCaracterizacion($request->cedula, $request->nacionalidad);
            $this->updatePersonalCaracterizacion($personalCaracterizacion->id, $request);

            if (JefeFamilia::where('raas_personal_caracterizacion_id', $personalCaracterizacion->id)->first()) {
                return response()->json(['success' => false, 'message' => 'Esta persona ya es Jefe calle']);
            }


            $raasJefeCalle = new JefeFamilia();
            $raasJefeCalle->raas_personal_caracterizacion_id = $personalCaracterizacion->id;
            $raasJefeCalle->raas_jefe_calle_id = $request->calleId;
            $raasJefeCalle->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Jefe familia creado']);
        } catch (\Exception $e) {
            Log::error($e);

            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Hubo un problema']);
        }
    }
}
