<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Parroquia;
use App\Models\Municipio;
use App\Models\CentroVotacion;

class CentroVotacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $centrosVotacion = [
            ["parroquia" => Parroquia::where("nombre", "Carirubana")->where("municipio_id", Municipio::where("nombre", "Carirubana")->first()->id)->first()->id, "centro_votacion" => "Centro 1"],
            ["parroquia" => Parroquia::where("municipio_id",  Municipio::where("nombre", "Carirubana")->first()->id)->where("nombre", "Santa Ana")->first()->id, "centro_votacion" => "Centro 2"],
            ["parroquia" => Parroquia::where("municipio_id", Municipio::where("nombre", "Carirubana")->first()->id)->where("nombre", "Norte")->first()->id, "centro_votacion" => "Centro 3"],
            ["parroquia" => Parroquia::where("municipio_id",  Municipio::where("nombre", "Carirubana")->first()->id)->where("nombre", "Punta Cardón")->first()->id, "centro_votacion" => "Centro 4"],

            ["parroquia" => Parroquia::where("municipio_id", Municipio::where("nombre", "Los Taques")->first()->id)->where("nombre", "Judibana")->first()->id, "centro_votacion" => "Centro 5"],
            ["parroquia" => Parroquia::where("municipio_id", Municipio::where("nombre", "Los Taques")->first()->id)->where("nombre", "Los Taques")->first()->id, "centro_votacion" => "Centro 6"],

            ["parroquia" => Parroquia::where("municipio_id", Municipio::where("nombre", "Falcón")->first()->id)->where("nombre", "Pueblo Nuevo")->first()->id, "centro_votacion" => "Centro 7"],
            ["parroquia" => Parroquia::where("municipio_id", Municipio::where("nombre", "Falcón")->first()->id)->where("nombre", "Adícora")->first()->id, "centro_votacion" => "Centro 8"],
            ["parroquia" => Parroquia::where("municipio_id", Municipio::where("nombre", "Falcón")->first()->id)->where("nombre", "Baraived")->first()->id, "centro_votacion" => "Centro 9"],
            ["parroquia" => Parroquia::where("municipio_id", Municipio::where("nombre", "Falcón")->first()->id)->where("nombre", "Buena Vista")->first()->id, "centro_votacion" => "Centro 10"],
            ["parroquia" => Parroquia::where("municipio_id", Municipio::where("nombre", "Falcón")->first()->id)->where("nombre", "Jadacaquiva")->first()->id, "centro_votacion" => "Centro 11"],
            ["parroquia" => Parroquia::where("municipio_id", Municipio::where("nombre", "Falcón")->first()->id)->where("nombre", "Moruy")->first()->id, "centro_votacion" => "Centro 12"],
            ["parroquia" => Parroquia::where("municipio_id", Municipio::where("nombre", "Falcón")->first()->id)->where("nombre", "Adaure")->first()->id, "centro_votacion" => "Centro 13"],
            ["parroquia" => Parroquia::where("municipio_id", Municipio::where("nombre", "Falcón")->first()->id)->where("nombre", "El Hato")->first()->id, "centro_votacion" => "Centro 14"],
            ["parroquia" => Parroquia::where("municipio_id", Municipio::where("nombre", "Falcón")->first()->id)->where("nombre", "El Vínculo")->first()->id, "centro_votacion" => "Centro 15"],

            ["parroquia" => Parroquia::where("municipio_id", Municipio::where("nombre", "Miranda")->first()->id)->where("nombre", "Guzmán Guillermo")->first()->id, "centro_votacion" => "Centro 16"],
            ["parroquia" => Parroquia::where("municipio_id", Municipio::where("nombre", "Miranda")->first()->id)->where("nombre", "Mitare")->first()->id, "centro_votacion" => "Centro 17"],
            ["parroquia" => Parroquia::where("municipio_id", Municipio::where("nombre", "Miranda")->first()->id)->where("nombre", "Río Seco")->first()->id, "centro_votacion" => "Centro 18"],
            ["parroquia" => Parroquia::where("municipio_id", Municipio::where("nombre", "Miranda")->first()->id)->where("nombre", "Sabaneta")->first()->id, "centro_votacion" => "Centro 19"],
            ["parroquia" => Parroquia::where("municipio_id", Municipio::where("nombre", "Miranda")->first()->id)->where("nombre", "San Antonio")->first()->id, "centro_votacion" => "Centro 20"],
            ["parroquia" => Parroquia::where("municipio_id", Municipio::where("nombre", "Miranda")->first()->id)->where("nombre", "San Gabriel")->first()->id, "centro_votacion" => "Centro 21"],
            ["parroquia" => Parroquia::where("municipio_id", Municipio::where("nombre", "Miranda")->first()->id)->where("nombre", "Santa Ana")->first()->id, "centro_votacion" => "Centro 22"]
        ];

        foreach($centrosVotacion as $centro){

            $this->store($centro);

        }

    }

    function store($centro){

        if(CentroVotacion::where("parroquia_id", $centro["parroquia"])->where("nombre", $centro["centro_votacion"])->count() == 0){

            $centroModel = new CentroVotacion;
            $centroModel->parroquia_id = $centro["parroquia"];
            $centroModel->nombre = $centro["centro_votacion"];
            $centroModel->codigo = uniqid();
            $centroModel->direccion = "Alguna dirección";
            $centroModel->save();

        }

    }

}
