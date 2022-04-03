<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderOperacionesRequest;
use App\Models\CensoOrdenOperaciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderOperacionesController extends Controller
{
    
    public function store(OrderOperacionesRequest $request){

        try{

            DB::beginTransaction();

            $operaciones = new CensoOrdenOperaciones;
            $operaciones->operacion = $request->selectedOperacion;
            $operaciones->valor_inicio =$request->rangoMenor;
            $operaciones->valor_fin =$request->rangoMayor;
            $operaciones->cantidad_bolsas = $request->cantidadBolsas;
            $operaciones->save();

            DB::commit();

            return response()->json(["success" =>true, "message" => "Orden de operación creada"]);

        }catch(\Exception $e){

            Log::error($e);

            DB::rollBack();
            return response()->json(["success" => false, "message" => "Hubo un problema"]);
        }

    }

    public function fetch(){

        $operaciones = CensoOrdenOperaciones::all();
        return response()->json($operaciones);
    }

    public function delete($id){

        CensoOrdenOperaciones::where("id", $id)->delete();
        return response()->json(["success" => true, "message" => "Orden eliminado"]);
    }

}
