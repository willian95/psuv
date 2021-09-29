<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Models\Elector;
use App\Models\CodigoCne;

class REPImport implements ToCollection, WithBatchInserts, WithChunkReading
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $index = 0;
        foreach($collection as $row){

            if($index > 0){

                if(Elector::where("cedula", $row[1])->count() == 0){
                
                    $this->storeElector($row);


                }

            }

            $index++;
        }

    }

    function storeElector($row){

        $elector = new Elector;
        $elector->nacionalidad = $row[0];
        $elector->cedula = $row[1];
        $elector->primer_apellido = $row[2];
        $elector->segundo_apellido = $row[3];
        $elector->primer_nombre = $row[4];
        $elector->segundo_nombre = $row[5];
        $elector->sexo = $row[6];
        $elector->fecha_nacimiento = $row[7];
        $elector->estado_id = $row[8];
        $elector->municipio_id = $row[9];
        $elector->parroquia_id = $this->findParroquia($row[9], $row[10]);
        $elector->centro_votacion_id = $this->findCentroVotacion($row[11]);
        $elector->save();

    }

    function findParroquia($municipio, $parroquia){

        $codigoParroquia = CodigoCne::where("cod_mcpo_cne", $municipio)->where("cod_pq_cne", $parroquia)->first();
        return $codigoParroquia->parroquia_id;

    }

    function findCentroVotacion($centroVotacion){

        $codigoParroquia = CodigoCne::where("cod_cv_cne", $centroVotacion)->first();
        return $codigoParroquia->centro_votacion_id;

    }

    public function batchSize(): int
    {
        return 1000;
    }
    
    public function chunkSize(): int
    {
        return 1000;
    }
}
