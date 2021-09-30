<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Models\Elector;
use App\Models\CodigoCne;

class REPImport implements ToCollection, WithLimit, ShouldQueue, WithChunkReading
{
    /**
    * @param Collection $collection
    */


    public function collection(Collection $collection)
    {
        dd($collection);
        foreach($collection as $row){
            
            if($row[0] != "nacionalidad"){
                if(Elector::where("cedula", $row[1])->count() == 0){
    
                    return new Elector([
                        "nacionalidad" => $row[0],
                        "cedula" => $row[1],
                        "primer_apellido" => $row[2],
                        "segundo_apellido" => $row[3],
                        "primer_nombre" => $row[4],
                        "segundo_nombre" => $row[5],
                        "sexo" => $row[6],
                        "fecha_nacimiento" => $row[7],
                        "estado_id" => $row[8],
                        "municipio_id" => $row[9],
                        "parroquia_id" => $this->findParroquia($row[9], $row[10]),
                        "centro_votacion_id" => $this->findCentroVotacion($row[11])
                    ]);
        
                }
            }

        }
        
    }

    function findParroquia($municipio, $parroquia){

        $codigoParroquia = CodigoCne::where("cod_mcpo_cne", $municipio)->where("cod_pq_cne", $parroquia)->first();
        return $codigoParroquia->parroquia_id;

    }

    function findCentroVotacion($centroVotacion){

        $codigoParroquia = CodigoCne::where("cod_cv_cne", $centroVotacion)->first();
        return $codigoParroquia->centro_votacion_id;

    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function limit(): int 
    {
         return 100;
    }

}
