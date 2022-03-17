<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMovilizacionToEleccionesMovilizacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movilizacion', function (Blueprint $table) {
            Schema::rename('movilizacion', 'elecciones_movilizacion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('elecciones_movilizacion', function (Blueprint $table) {
        });
    }
}
