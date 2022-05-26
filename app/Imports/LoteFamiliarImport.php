<?php

namespace App\Imports;

use App\Models\CensoVivienda;
use App\Models\Elector;
use App\Models\JefeFamilia;
use App\Models\PersonalCaracterizacion;
use App\Models\RaasJefeCalle;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Traits\ElectorTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LoteFamiliarImport implements ToCollection
{
    use ElectorTrait;

    public $tempRows = [];

    public function forCalleId($calleId)
    {
        $this->calleId = $calleId;
        
        return $this;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        try{

            
            $index = 0;
            $jefeCalle = $this->getJefeCalle($this->calleId);
            foreach ($collection as $row) 
            {
             
               
                /*if($index > 0){

                    if($row[6] != null){

                        $nacionalidad = explode("-", $row[6])[0];
                        $cedula = explode("-", $row[6])[1];

                        $personalCaracterizacion = PersonalCaracterizacion::where("nacionalidad", strtoupper($nacionalidad))->where("cedula", $cedula)->first();

                        if($personalCaracterizacion){

                            if($personalCaracterizacion->raas_jefe_familia == null){

                                $jefeCalle = $this->getJefeCalle($this->calleId);
                                
                                if(strtoupper($row[10]) == "SI"){

                                   
                                    $jefeFamilia = $this->storeJefeFamilia($personalCaracterizacion, $jefeCalle);
                                    if($jefeFamilia){
                                        $this->updatePersonalCaracterizacionJefeFamilia($personalCaracterizacion, $jefeFamilia);
                                        $this->storeCensoVivienda($row, $jefeFamilia->id);
                                    }else{
                                        $row[11] = "Jefe de familia no registrado";
                                        $this->tempRows[] = $row;
                                    }
                                    
                                    
                                }else{

                                    $jefeFamilia = $this->getJefeFamiliaByCedula($row);
                                    if($jefeFamilia){
                                        $this->updatePersonalCaracterizacionJefeFamilia($personalCaracterizacion, $jefeFamilia);
                                    }else{
                                        $row[11] = "Jefe de familia no registrado";
                                        $tempRows[] = $row;
                                    }

                                    
                                    

                                }


                            }else{
            
                                $this->tempRows[] = $row;
                            }

                        }else{

                            $elector = Elector::where("nacionalidad", $nacionalidad)->where("cedula", $cedula)->first();

                            if($elector){
                                if(!PersonalCaracterizacion::where("nacionalidad", $nacionalidad)->where("cedula", $cedula)->first()){
                                    $personalCaracterizacion = $this->personalCaracterizacionStore($nacionalidad, $cedula, $elector, $row);
                                }
                                
                                $jefeCalle = $this->getJefeCalle($this->calleId);

                                if(strtoupper($row[10]) == "SI"){

                                   
                                    $jefeFamilia = $this->storeJefeFamilia($personalCaracterizacion, $jefeCalle);
                                    if($jefeFamilia){
                                        $this->updatePersonalCaracterizacionJefeFamilia($personalCaracterizacion, $jefeFamilia);
                                        $this->storeCensoVivienda($row, $jefeFamilia->id);
                                    }
                                    

                                }else{

                                    $jefeFamilia = $this->getJefeFamiliaByCedula($row);
                                    if($jefeFamilia){
                                        $this->updatePersonalCaracterizacionJefeFamilia($personalCaracterizacion, $jefeFamilia);
                                    }
                                    

                                }

                            }else{

                                $response = $this->searchInCNE($cedula, $nacionalidad);
                               
                                if($response){
                                    
                                    $elector = new Elector();
                                    $elector->nacionalidad = strtoupper($nacionalidad);
                                    $elector->cedula = $cedula;
                                    $elector->nombre_apellido = $response["nombre_apellido"];
                                    $elector->sexo = strtolower($row[9]);
                                    $elector->raas_estado_id = $response["raas_estado_id"];
                                    $elector->raas_municipio_id = $response["raas_municipio_id"];
                                    $elector->raas_parroquia_id = $response["raas_parroquia_id"];
                                    $elector->raas_centro_votacion_id = $response["raas_centro_votacion_id"];
                                    $elector->save();
                                    if(!PersonalCaracterizacion::where("nacionalidad", $nacionalidad)->where("cedula", $cedula)->first()){
                                        $personalCaracterizacion = $this->personalCaracterizacionStore($nacionalidad, $cedula, $elector, $row);
                                    }

                                    $jefeCalle = $this->getJefeCalle($this->calleId);

                                    if(strtoupper($row[10]) == "SI"){
                                    
                                        $jefeFamilia = $this->storeJefeFamilia($personalCaracterizacion, $jefeCalle);
                                        if($jefeFamilia){
                                            $this->updatePersonalCaracterizacionJefeFamilia($personalCaracterizacion, $jefeFamilia);
                                            $this->storeCensoVivienda($row, $jefeFamilia->id);
                                        }
                                        

                                    }else{

                                        $jefeFamilia = $this->getJefeFamiliaByCedula($row);
                                        if($jefeFamilia){
                                            $this->updatePersonalCaracterizacionJefeFamilia($personalCaracterizacion, $jefeFamilia);
                                        }
                                        

                                    }

                                }else{

                                    if(intval($cedula) >= 30000000){

                                        if(!PersonalCaracterizacion::where("nacionalidad", strtoupper($nacionalidad))->where("cedula", $cedula)->first()){

                                            $personalCaracterizacion = $this->personalCaracterizacionStore($nacionalidad, $cedula, $elector, $row);
                                            $jefeCalle = $this->getJefeCalle($this->calleId);
        
                                            if(strtoupper($row[10]) == "SI"){
                                            
                                                $jefeFamilia = $this->storeJefeFamilia($personalCaracterizacion, $jefeCalle);
                                                if($jefeFamilia){
                                                    $this->updatePersonalCaracterizacionJefeFamilia($personalCaracterizacion, $jefeFamilia);
                                                    $this->storeCensoVivienda($row, $jefeFamilia->id);
                                                }
                                                
        
                                            }else{
        
                                                $jefeFamilia = $this->getJefeFamiliaByCedula($row);
                                                if($jefeFamilia){
                                                    $this->updatePersonalCaracterizacionJefeFamilia($personalCaracterizacion, $jefeFamilia);
                                                }
                                                
        
                                            }

                                        }

                                        

                                    }else{
                                        $row[11] = "No encontrado en CNE";
                                        $this->tempRows[] = $row;
                                    }

                                    
                                }

                            
                            }

                        }

                    }else{

                        if($row[5] != null && $row[8] != null){
                            $personalCaracterizacion = PersonalCaracterizacion::where("nombre_apellido", $row[5])->where("fecha_nacimiento", $row[8])->first();
                           
                            if(!$personalCaracterizacion){
                                $jefeCalle = $this->getJefeCalle($this->calleId);

                                $personalCaracterizacion = new PersonalCaracterizacion();
                                $personalCaracterizacion->nacionalidad = "V";
                                $personalCaracterizacion->nombre_apellido = $row[5];
                                $personalCaracterizacion->sexo = strtolower($row[9]);
                                $personalCaracterizacion->fecha_nacimiento = $row[8];
                                $personalCaracterizacion->save();

                                if(strtoupper($row[10]) == "SI"){

                                    
                                    $jefeFamilia = $this->storeJefeFamilia($personalCaracterizacion, $jefeCalle);
                                    $this->updatePersonalCaracterizacionJefeFamilia($personalCaracterizacion, $jefeFamilia);
                                    $this->storeCensoVivienda($row, $jefeFamilia->id);

                                }else{

                                    $jefeFamilia = $this->getJefeFamiliaByCedula($row);
                                    if($jefeFamilia){
                                        $this->updatePersonalCaracterizacionJefeFamilia($personalCaracterizacion, $jefeFamilia);
                                    }else{
                                        
                                        $row[11] = "Jefe de familia no registrado";
                                        $this->tempRows[] = $row;
                                    }
                                    

                                }
                            }else{
                                $row[11] = "Persona no cedulada duplicada";
                                $this->tempRows[] = $row;

                            }
                        }

                        

                    }
        

                }*/

                if($index > 0){
                    Log::info($row[6]);
                    if($row[6] != null){
                        
                        $nacionalidad = explode("-", $row[6])[0];
                        $cedula = explode("-", $row[6])[1];

                        if(strtoupper($row[10]) == "SI"){

                            
                            $personalCaracterizacion = PersonalCaracterizacion::where("nacionalidad", strtoupper($nacionalidad))->where("cedula", $cedula)->first();
                            if(!$personalCaracterizacion){

                                $elector = Elector::where("nacionalidad", $nacionalidad)->where("cedula", $cedula)->first();
                                if(!$elector){

                                    $response = $this->searchInCNE($cedula, $nacionalidad);
                                
                                    if($response){
                                        $elector = $this->storeElector($nacionalidad, $cedula, $row, $response);
                                        $personalCaracterizacion = $this->personalCaracterizacionStore($nacionalidad, $cedula, $elector, $row);
                                    }else{
                                        $personalCaracterizacion = $this->personalCaracterizacionStoreNoElector($row, $nacionalidad, $cedula);
                                    }

                                }else{
                                    $row[11] = "Persona duplicada Jefe elector id = ".$elector->id;
                                    Log::info("Elector jefe");
                                    Log::info($row);
                                }
                                $personalCaracterizacion = $this->personalCaracterizacionStore($nacionalidad, $cedula, $elector, $row);

                                $jefeFamilia = $this->storeJefeFamilia($personalCaracterizacion, $jefeCalle);
                                if($jefeFamilia){
                                    $this->updatePersonalCaracterizacionJefeFamilia($personalCaracterizacion, $jefeFamilia);
                                    $this->storeCensoVivienda($row, $jefeFamilia->id);
                                }
                            

                            }else{
                                $row[11] = "Persona duplicada en personal caracterizacion Jefe id = ". $personalCaracterizacion->id;
                                $this->tempRows[] = $row;
                            }

                        }else{

                            
                                $personalCaracterizacion = PersonalCaracterizacion::where("nacionalidad", strtoupper($nacionalidad))->where("cedula", $cedula)->first();
                                if(!$personalCaracterizacion){

                                    $elector = Elector::where("nacionalidad", $nacionalidad)->where("cedula", $cedula)->first();
                                    if(!$elector){
                                        
                                        $elector = null;
                                        $response = $this->searchInCNE($cedula, $nacionalidad);
                                        
                                        if($response){
                                            $elector = $this->storeElector($nacionalidad, $cedula, $row, $response);
                                            $personalCaracterizacion = $this->personalCaracterizacionStore($nacionalidad, $cedula, $elector, $row);
                                        }else{
                                            $personalCaracterizacion = $this->personalCaracterizacionStoreNoElector($row, $nacionalidad, $cedula);
                                        }

                                    }else{
                                        $row[11] = "Persona duplicada no Jefe elector id = ".$elector->id;
                                        Log::info("Elector no jefe");
                                        Log::info($row);
                                    }

                                    $jefeFamilia = $this->getJefeFamiliaByCedula($row);
                                    if($jefeFamilia){
                                        $this->updatePersonalCaracterizacionJefeFamilia($personalCaracterizacion, $jefeFamilia);
                                    }else{
                                        $row[11] = "Persona no posee jefe";
                                        $this->tempRows[] = $row;
                                    }
                                

                                }else{
                                    $row[11] = "Persona duplicada en personal caracterizacion no Jefe id = ". $personalCaracterizacion->id;
                                    $this->tempRows[] = $row;
                                }
                            

                        }


                    }

                }
                
                $index++;
                
            }

            

            return $this->tempRows;

        }catch(\Exception $e){

            dd($e);

        }
    }


    private function getJefeCalle($calle_id){

        $jefeCalle = RaasJefeCalle::where("raas_calle_id", $calle_id)->first();
        return $jefeCalle;

    }

    private function storeJefeFamilia($personalCaracterizacion, $jefeCalle){

        if(JefeFamilia::where("raas_personal_caracterizacion_id", $personalCaracterizacion->id)->first()){

            return JefeFamilia::where("raas_personal_caracterizacion_id", $personalCaracterizacion->id)->first();

        }

        $jefeFamilia = new JefeFamilia();
        $jefeFamilia->raas_personal_caracterizacion_id = $personalCaracterizacion->id;
        $jefeFamilia->raas_jefe_calle_id = $jefeCalle->id;
        $jefeFamilia->save();

        return $jefeFamilia;
    }

    private function updatePersonalCaracterizacionJefeFamilia($personalCaracterizacion, $jefeFamilia){
        $personalCaracterizacion->raas_jefe_familia_id = $jefeFamilia->id;
        $personalCaracterizacion->update();
    }

    private function storeCensoVivienda($row, $raas_jefe_familia_id){

        $censoVivienda = new CensoVivienda();
        $censoVivienda->codigo = $row[0];
        $censoVivienda->cantidad_familias = $row[3];
        $censoVivienda->raas_calle_id = $this->calleId;
        $censoVivienda->tipo_vivienda = $row[1];
        $censoVivienda->cantidad_habitantes = $row[2] ? $row[2] : 0;
        $censoVivienda->raas_jefe_familia_id = $raas_jefe_familia_id;
        $censoVivienda->save();

    }

    private function getJefeFamiliaByCedula($row){
        if($row[4]){

            $nacionalidad = explode("-", $row[4])[0];
            $cedula = explode("-", $row[4])[1];
            
            $personalCaracterizacion = PersonalCaracterizacion::where("nacionalidad", strtoupper($nacionalidad))->where("cedula", $cedula)->first();
            if($personalCaracterizacion){
                $jefeFamilia = JefeFamilia::where("raas_personal_caracterizacion_id", $personalCaracterizacion->id)->first();
                return $jefeFamilia;
            }

        }
        
        
        return null;

    }

    private function personalCaracterizacionStore($nacionalidad, $cedula, $elector, $row){

        $personalCaracterizacion = new PersonalCaracterizacion();
        $personalCaracterizacion->nacionalidad = strtoupper($nacionalidad);
        $personalCaracterizacion->cedula = $cedula;
        $personalCaracterizacion->nombre_apellido = $elector ? $elector->nombre_apellido : $row[5];
        $personalCaracterizacion->sexo = strtolower($row[9]);
        $personalCaracterizacion->fecha_nacimiento = $row[8];
        $personalCaracterizacion->raas_estado_id = $elector ? $elector->raas_estado_id : null;
        $personalCaracterizacion->raas_municipio_id = $elector ? $elector->raas_municipio_id : null;
        $personalCaracterizacion->raas_parroquia_id = $elector ? $elector->raas_parroquia_id : null;
        $personalCaracterizacion->raas_centro_votacion_id = $elector ? $elector->raas_centro_votacion_id : null;
        $personalCaracterizacion->es_elector =  $elector ? $elector->raas_centro_votacion_id ? true : false : false;
        $personalCaracterizacion->save();

        return $personalCaracterizacion;

    }

    private function personalCaracterizacionStoreNoElector($row, $nacionalidad,$cedula){
        $personalCaracterizacion = new PersonalCaracterizacion();
        $personalCaracterizacion->nacionalidad = strtoupper($nacionalidad);
        $personalCaracterizacion->cedula = $cedula;
        $personalCaracterizacion->nombre_apellido = $row[5];
        $personalCaracterizacion->sexo = strtolower($row[9]);
        $personalCaracterizacion->fecha_nacimiento = $row[8];
        $personalCaracterizacion->save();

        return $personalCaracterizacion;
    }

    private function storeElector($nacionalidad, $cedula, $row, $response){

        $elector = new Elector();
        $elector->nacionalidad = strtoupper($nacionalidad);
        $elector->cedula = $cedula;
        $elector->nombre_apellido = $response["nombre_apellido"];
        $elector->sexo = strtolower($row[9]);
        $elector->raas_estado_id = $response["raas_estado_id"];
        $elector->raas_municipio_id = $response["raas_municipio_id"];
        $elector->raas_parroquia_id = $response["raas_parroquia_id"];
        $elector->raas_centro_votacion_id = $response["raas_centro_votacion_id"];
        $elector->save();

    }

}