<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Estado;
use App\Models\Parroquia;
use App\Models\Municipio;
use App\Models\CentroVotacion;
use App\Models\Elector;

class ElectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $electores = [
            [
                "nacionalidad" => "venezolano",
                "cedula" => "24595726",
                "primer_apellido" => "Rodríguez",
                "segundo_apellido" => "Ramírez",
                "primer_nombre" => "Willian",
                "segundo_nombre" => "Rodríguez",
                "sexo" => "masculino",
                "fecha_nacimiento" => "1995-11-09",
                "estado_id" => Estado::first()->id,
                "municipio_id" => Municipio::first()->id,
                "parroquia_id" => Parroquia::first()->id,
                "centro_votacion_id" => CentroVotacion::first()->id
            ],
            [
                "nacionalidad" => "venezolano",
                "cedula" => "23543223",
                "primer_apellido" => "Perez",
                "segundo_apellido" => "Gonzalez",
                "primer_nombre" => "María",
                "segundo_nombre" => "Josefina",
                "sexo" => "femenino",
                "fecha_nacimiento" => "1998-05-01",
                "estado_id" => Estado::first()->id,
                "municipio_id" => Municipio::first()->id,
                "parroquia_id" => Parroquia::first()->id,
                "centro_votacion_id" => CentroVotacion::first()->id
            ],
            [
                "nacionalidad" => "venezolano",
                "cedula" => "23586157",
                "primer_apellido" => "vega",
                "segundo_apellido" => "Gonzalez",
                "primer_nombre" => "sabas",
                "segundo_nombre" => "carlos e",
                "sexo" => "masculino",
                "fecha_nacimiento" => "1998-05-01",
                "estado_id" => Estado::first()->id,
                "municipio_id" => Municipio::first()->id,
                "parroquia_id" => Parroquia::first()->id,
                "centro_votacion_id" => CentroVotacion::first()->id
            ]
        ];

        foreach($electores as $elector){

            $this->store($elector);

        }

    }

    function store($elector){

        if(Elector::where("cedula", $elector["cedula"])->count() == 0){

            Elector::create($elector);

        }

    }
}
