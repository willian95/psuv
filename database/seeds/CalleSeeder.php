<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Calle;
use App\Models\Comunidad;

class CalleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $c=Comunidad::first();
        if($c){
            $calle=Calle::firstOrCreate([
                "nombre"=>"Federación"
            ],[
                "tipo"=>"ccccc",
                "sector"=>"nuevo pueblo",
                "comunidad_id"=>$c->id
            ]);
        }
    }
}
