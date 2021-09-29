<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\CodigoCne;

class CodigoCNEImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $index = 0;
        foreach ($collection as $row) 
        {
            
            if($index > 0){

                if(CodigoCne::where("estado_id", $row[0])->where("cod_edo_cne", $row[1])->where("municipio_id", $row[2])->where("cod_mcpo_cne", $row[3])->where("parroquia_id", $row[4])->where("cod_pq_cne", $row[5])->where("centro_votacion_id", $row[6])->where("cod_cv_cne", $row[7])->count() == 0){
                
                    $codigo = new CodigoCne;
                    $codigo->estado_id = $row[0];
                    $codigo->cod_edo_cne = $row[1];
                    $codigo->municipio_id = $row[2];
                    $codigo->cod_mcpo_cne = $row[3];
                    $codigo->parroquia_id = $row[4];
                    $codigo->cod_pq_cne = $row[5];
                    $codigo->centro_votacion_id = $row[6];
                    $codigo->cod_cv_cne = $row[7];
                    $codigo->save();
          
                }
    

            }
            
            $index++;
        }
    }
}
