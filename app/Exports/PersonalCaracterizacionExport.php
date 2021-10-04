<?php

namespace App\Exports;

use App\Models\PersonalCaracterizacion as Model;
use Maatwebsite\Excel\Concerns\FromCollection;

class PersonalCaracterizacionExport implements FromCollection
{
    public $request;
    public function __construct($request)
    {
        $this->request=$request;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $request=$this->request;
        $estado_id = $request->input('estado_id');
        $municipio_id = $request->input('municipio_id');
        $parroquia_id = $request->input('parroquia_id');
        $centro_votacion_id = $request->input('centro_votacion_id');
        //Init query
        $query=Model::query();
        //Filters
        if ($estado_id) {
            $query->where('estado_id', $estado_id);
        }
        if ($municipio_id) {
            $query->where('municipio_id', $municipio_id);
        }
        if ($parroquia_id) {
            $query->where('parroquia_id', $parroquia_id);
        }
        if ($centro_votacion_id) {
            $query->where('centro_votacion_id', $centro_votacion_id);
        }

        $query->orderBy('cedula',"ASC");

        return $query->get();
    }
}
