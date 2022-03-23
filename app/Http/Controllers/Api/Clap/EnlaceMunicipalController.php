<?php

namespace App\Http\Controllers\Api\Clap;

use App\Http\Controllers\Controller;
use App\Http\Requests\Clap\EnlaceMunicipalStoreRequest;
use App\Models\CensoEnlaceMunicipal;
use App\Traits\ElectorTrait;
use App\Traits\PersonalCaracterizacionTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EnlaceMunicipalController extends Controller
{
    use PersonalCaracterizacionTrait;
    use ElectorTrait;

    public function searchByCedula(Request $request)
    {
        $response = $this->searchPersonalCaracterizacionOrElector($request->cedula, $request->nacionalidad, $request->municipio_id);

        return response()->json($response);
    }

    public function store(EnlaceMunicipalStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $censoEnlaceMunicipal = CensoEnlaceMunicipal::where('raas_municipio_id', $request->selectedMunicipioEnlaceMunicipal)->first();
            if (!is_null($censoEnlaceMunicipal)) {
                return response()->json([
                'success' => false,
                'message' => 'Este municipio ya posee enlace municipal',
            ]);
            }

            $this->storePersonalCaracterizacion($request);
            $personalCaracterizacion = $this->getPersonalCaracterizacion($request->cedula, $request->nacionalidad);
            $this->updatePersonalCaracterizacion($personalCaracterizacion->id, $request);

            $censoEnlaceMunicipal = new CensoEnlaceMunicipal();
            $censoEnlaceMunicipal->raas_personal_caracterizacion_id = $personalCaracterizacion->id;
            $censoEnlaceMunicipal->raas_municipio_id = $request->selectedMunicipioEnlaceMunicipal;
            $censoEnlaceMunicipal->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Enlace municipal creado']);
        } catch (\Exception $e) {
            Log::error($e);

            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Hubo un problema']);
        }
    }

    public function update(EnlaceMunicipalStoreRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $censoEnlaceMunicipal = CensoEnlaceMunicipal::where('id', $id)->first();

            if (is_null($censoEnlaceMunicipal)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Enlace municipal no existe',
                ]);
            }

            $this->storePersonalCaracterizacion($request);
            $personalCaracterizacion = $this->getPersonalCaracterizacion($request->cedula, $request->nacionalidad);
            $this->updatePersonalCaracterizacion($personalCaracterizacion->id, $request);

            $censoEnlaceMunicipal->raas_personal_caracterizacion_id = $personalCaracterizacion->id;
            $censoEnlaceMunicipal->update();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Enlace municipal actualizado']);
        } catch (\Exception $e) {
            Log::error($e);

            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Hubo un problema']);
        }
    }

    public function fetch(Request $request)
    {
        $query = CensoEnlaceMunicipal::with('personalCaracterizacions', 'municipio');

        if (isset($request->selectedMunicipioEnlaceMunicipal)) {
            $query->where('raas_municipio_id', $request->selectedMunicipioEnlaceMunicipal);
        }

        if (isset($request->searchCedula)) {
            $query->whereHas('personalCaracterizacions', function ($query) use ($request) {
                $query->where('cedula', 'like', '%'.$request->searchCedula.'%');
            });
        }

        $censoEnlaceMunicipal = $query->paginate(20);

        return response()->json($censoEnlaceMunicipal);
    }

    public function delete($id)
    {
        CensoEnlaceMunicipal::where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => 'Enlace municipal eliminado']);
    }

    public function fetchAll()
    {
        $enlaces = CensoEnlaceMunicipal::with('municipio')->get();

        return response()->json($enlaces);
    }
}
