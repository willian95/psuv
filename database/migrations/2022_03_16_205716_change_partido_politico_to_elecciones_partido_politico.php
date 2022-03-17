<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePartidoPoliticoToEleccionesPartidoPolitico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partido_politico', function (Blueprint $table) {
            Schema::rename('partido_politico', 'elecciones_partido_politico');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('elecciones_partido_politico', function (Blueprint $table) {
        });
    }
}
