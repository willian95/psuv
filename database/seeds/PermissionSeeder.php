<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission as Model;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          //Roles
          $rolesArray=[
            ['name' => 'gestion usuarios'],
            ['name' => 'gestion roles'],
            ['name' => 'gestion comunidades'],
            ['name' => 'gestion calles'],
            ['name' => 'raas ubch'],
            ['name' => 'raas jefe comunidad'],
            ['name' => 'raas jefe calle'],
            ['name' => 'nucleos familiares'],
            ['name' => 'reporte estructura raas'],
            ['name' => 'reporte movilizacion electores'],
            ['name' => 'reporte carga'],
            ['name' => 'reporte listado jefes'],
            ['name' => 'votaciones cuadernillo'],
            ['name' => 'instituciones'],
            ['name' => 'instituciones listado'],
            ['name' => 'movimientos sociales'],
            ['name' => 'rep listado electores'],
            ['name' => 'metas ubch '],
            ['name' => 'sala tecnica'],
        ];
        foreach ($rolesArray as $role) {
            Model::firstOrCreate(
                [
                    'name' => $role['name']
                ],[
                    'guard_name'=>"api"
                ]
            );
        }
        //rolesArray
    }
}
