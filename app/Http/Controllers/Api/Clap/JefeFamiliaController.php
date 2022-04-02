<?php

namespace App\Http\Controllers\Api\Clap;

use App\Http\Controllers\Controller;
use App\Http\Requests\Clap\JefeFamilia\StoreNucleoFamiliarRequest;
use App\Traits\ElectorTrait;
use App\Traits\PersonalCaracterizacionTrait;
use App\Http\Requests\Clap\JefeFamilia\StoreRequest;
use App\Http\Requests\Clap\JefeFamilia\UpdateRequest;
use Illuminate\Http\Request;
use App\Models\JefeFamilia;
use Illuminate\Support\Facades\DB;
use App\Models\CensoVivienda;
use App\Models\PersonalCaracterizacion;
use App\Models\RaasEstatusPersonal;
use App\Models\RaasJefeCalle;
use Illuminate\Support\Facades\Log;

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
                return response()->json(['success' => false, 'message' => 'Esta persona ya es Jefe de familia']);
            }

            $raasJefeFamilia = new JefeFamilia();
            $raasJefeFamilia->raas_personal_caracterizacion_id = $personalCaracterizacion->id;
            $raasJefeFamilia->raas_jefe_calle_id = $request->jefeCalleId;
            $raasJefeFamilia->save();

            $this->storeVivienda($request, $raasJefeFamilia);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Jefe familia creado']);
        } catch (\Exception $e) {
            Log::error($e);

            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Hubo un problema']);
        }
    }

    private function storeVivienda($request, $raasJefeFamilia){

        $vivienda = new CensoVivienda();
        $vivienda->codigo = $this->codigoVivienda($request);
        $vivienda->cantidad_familias = $request->numeroFamilia;
        $vivienda->raas_calle_id = $this->getJefeCalle($request);
        $vivienda->direccion = $request->direccion;
        $vivienda->tipo_vivienda = $request->tipoCasa;
        $vivienda->raas_jefe_familia_id = $raasJefeFamilia->id;
        if($request->tipoCasa == 'anexo'){
            $vivienda->vivienda_id = $request->selectedCasa;
        }

        $vivienda->save();

    }

    private function codigoVivienda($request){

        $amount = 0;
        if($request->tipoCasa == "anexo"){

            $amount = $this->calculateTipoAnexo($request);
            $codigo = str_pad($amount, 5, "0", STR_PAD_LEFT);
            $casaCodigo = CensoVivienda::where("id", $request->selectedCasa)->first()->codigo;
            $codigo = $casaCodigo."-".$codigo;
        }else{

            $amount = $this->calculateTipoCasa($request);
            $codigo = str_pad($amount, 5, "0", STR_PAD_LEFT);
        }

        return $codigo;

    }

    public function calculateTipoCasa($request){
        $jefeCalle = RaasJefeCalle::where("id", $request->jefeCalleId)->with("calle.comunidad")->first();
        $comunidad = $jefeCalle->calle->comunidad->id;

        $amount = CensoVivienda::whereHas("calle", function($query) use($comunidad){

            $query->where("raas_comunidad_id", $comunidad);

        })->count();
        
        if(CensoVivienda::whereHas("calle", function($query) use($comunidad){

            $query->where("raas_comunidad_id", $comunidad);

        })->where("codigo", str_pad($amount + 1, 5, "0", STR_PAD_LEFT))->first()){

            $amount = $amount + 2;
            return $amount;
        }

        return $amount + 1;
    }

    

    public function calculateTipoAnexo($request){
        
        $amount = 0;
        $amount = CensoVivienda::where("vivienda_id", $request->selectedCasa)->count();

        return $amount + 1;
    }

    public function getJefeCalle($request){
        $jefeCalle = RaasJefeCalle::where("id", $request->jefeCalleId)->first();
        return $jefeCalle->raas_calle_id;
    }

    public function getEstatusPersonal(){
        
        $estatus = RaasEstatusPersonal::get();
        return response()->json($estatus);

    }

    public function fetch(Request $request)
    {
        $query = JefeFamilia::with('personalCaracterizacion', 'vivienda', 'JefeCalle.personalCaracterizacions', 'JefeCalle.calle.comunidad.parroquia.municipio');

        if (isset($request->searchCedula)) {
            $query->whereHas('personalCaracterizacions', function ($query) use ($request) {
                $query->where('cedula', 'like', '%'.$request->searchCedula.'%');
            });
        }

        $censoJefeFamilia = $query->orderBy("id", "desc")->paginate(20);

        return response()->json($censoJefeFamilia);
    }

    public function getCasasByCalle($id){

        $casas = CensoVivienda::where("raas_calle_id", $id)->get();
        return response()->json($casas);
    }

    public function update(UpdateRequest $request, $id)
    {

        try {
            DB::beginTransaction();

            $this->storePersonalCaracterizacion($request);
            $personalCaracterizacion = $this->getPersonalCaracterizacion($request->cedula, $request->nacionalidad);
            $this->updatePersonalCaracterizacion($personalCaracterizacion->id, $request);

            if (JefeFamilia::where('raas_personal_caracterizacion_id', $personalCaracterizacion->id)->where("id", "<>", $id)->first()) {
                return response()->json(['success' => false, 'message' => 'Esta persona ya es Jefe de familia']);
            }

            $raasJefeFamilia = JefeFamilia::find($id);
            $raasJefeFamilia->raas_personal_caracterizacion_id = $personalCaracterizacion->id;
            $raasJefeFamilia->update();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Jefe familia actualizado']);
        } catch (\Exception $e) {
            Log::error($e);

            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Hubo un problema']);
        }
    }

    public function storeNucleoFamiliar(StoreNucleoFamiliarRequest $request){

        try {
            DB::beginTransaction();

            $this->storePersonalCaracterizacion($request);
            $personalCaracterizacion = $this->getPersonalCaracterizacion($request->cedula, $request->nacionalidad);
            $this->updatePersonalCaracterizacion($personalCaracterizacion->id, $request);

            if(JefeFamilia::where("raas_personal_caracterizacion_id", $personalCaracterizacion->id)->first()){
                return response()->json(["success" => false, "message" => "Esta persona es jefe de familia"]);
            }

            if($personalCaracterizacion->raas_jefe_familia_id != null && $personalCaracterizacion->raas_jefe_familia_id != $request->jefeFamiliaId){
                return response()->json(["success" => false, "message" => "Esta persona ya posee jefe de familia"]);
            }

            $personalCaracterizacion = PersonalCaracterizacion::find($personalCaracterizacion->id);
            $personalCaracterizacion->raas_jefe_familia_id = $request->jefeFamiliaId;
            $personalCaracterizacion->update();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Familiar añadido']);
        } catch (\Exception $e) {
            Log::error($e);

            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Hubo un problema']);
        }

    }

    public function getFamiliaresByJefeFamilia($jefe_familia){

        $familiares = PersonalCaracterizacion::where("raas_jefe_familia_id", $jefe_familia)->get();
        return response()->json($familiares);

    }

    public function delete($id){

        if(PersonalCaracterizacion::where("raas_jefe_familia_id", $id)->count() > 0){
            return response()->json(["success" => false, "message" => "No puedes eliminar debido a que posees personas en tu nucleo familiar"]);
        }

        CensoVivienda::where("raas_jefe_familia_id", $id)->delete();
        JefeFamilia::where("id", $id)->delete();

        return response()->json(['success' => true, "message" => "Jefe de familia eliminado"]);
        
    }

}
