<?php

namespace App\Http\Controllers\Api\Clap;

use App\Http\Controllers\Controller;
use App\Http\Requests\Clap\JefeCLAPStoreRequest;
use App\Http\Requests\Clap\JefeCLAPUpdateRequest;
use App\Models\CensoJefeClap;
use App\Traits\ElectorTrait;
use App\Traits\PersonalCaracterizacionTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JefeClapController extends Controller
{
    use PersonalCaracterizacionTrait;
    use ElectorTrait;

    public function searchByCedula(Request $request)
    {
        $response = $this->searchPersonalCaracterizacionOrElector($request->cedula, $request->nacionalidad, $request->municipio_id);

        return response()->json($response);
    }

    public function store(JefeCLAPStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $this->storePersonalCaracterizacion($request);
            $personalCaracterizacion = $this->getPersonalCaracterizacion($request->cedula, $request->nacionalidad);

            if (CensoJefeClap::where('raas_personal_caracterizacion_id', $personalCaracterizacion->id)->first()) {
                return response()->json(['success' => false, 'message' => 'Esta persona ya es Jefe de un CLAP']);
            }

            if (CensoJefeClap::where('censo_enlace_municipal_id', $request->selectedMunicipioEnlaceMunicipal)->count() > 0) {
                return response()->json(['success' => false, 'message' => 'Ya existe un jefe para este CLAP']);
            }

            $censoJefeClap = new CensoJefeClap();
            $censoJefeClap->raas_personal_caracterizacion_id = $personalCaracterizacion->id;
            $censoJefeClap->censo_enlace_municipal_id = $request->selectedMunicipioEnlaceMunicipal;
            $censoJefeClap->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Jefe CLAP creado']);
        } catch (\Exception $e) {
            Log::error($e);

            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Hubo un problema']);
        }
    }

    public function update(JefeCLAPUpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $this->storePersonalCaracterizacion($request);
            $personalCaracterizacion = $this->getPersonalCaracterizacion($request->cedula, $request->nacionalidad);

            $censoJefeClap = CensoJefeClap::where('id', $id)->first();

            if (is_null($censoJefeClap)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jefe de CLAP no existe',
                ]);
            }

            if (CensoJefeClap::where('id', '<>', $id)->where('raas_personal_caracterizacion_id', $personalCaracterizacion->id)->first()) {
                return response()->json(['success' => false, 'message' => 'Esta persona ya es Jefe de un CLAP']);
            }

            if (CensoJefeClap::where('id', '<>', $id)->where('censo_enlace_municipal_id', $request->selectedMunicipioEnlaceMunicipal)->count() > 0) {
                return response()->json(['success' => false, 'message' => 'Ya existe un jefe para este CLAP']);
            }

            $censoJefeClap->raas_personal_caracterizacion_id = $personalCaracterizacion->id;
            $censoJefeClap->update();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Jefe CLAP actualizado']);
        } catch (\Exception $e) {
            Log::error($e);

            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Hubo un problema']);
        }
    }

    public function fetch(Request $request)
    {
        $query = CensoJefeClap::with('personalCaracterizacions', 'enlaceMunicipal.municipio');

        if (isset($request->searchCedula)) {
            $query->whereHas('personalCaracterizacions', function ($query) use ($request) {
                $query->where('cedula', 'like', '%'.$request->searchCedula.'%');
            });
        }

        $censoJefeClap = $query->paginate(20);

        return response()->json($censoJefeClap);
    }

    public function delete($id)
    {
        CensoJefeClap::where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => 'Jefe clap eliminado']);
    }

    public function searchJefeClapByCedula(Request $request)
    {
        $jefeClap = CensoJefeClap::whereHas('personalCaracterizacions', function ($query) use ($request) {
            $query->where('cedula', $request->cedula);
            $query->where('nacionalidad', $request->nacionalidad);
        })->with('personalCaracterizacions', 'enlaceMunicipal.municipio')->first();

        if (is_null($jefeClap)) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Jefe clap no encontrado',
                ]
            );
        }

        return response()->json(['success' => true, 'jefe' => $jefeClap]);
    }
}
