<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMovilizacionIdInRaasPersonalCaracterizacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raas_personal_caracterizacion', function (Blueprint $table) {
            $table->renameColumn('movilizacion_id', 'elecciones_movilizacion_id');
            $table->renameColumn('partido_politico_id', 'elecciones_partido_politico_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('raas_personal_caracterizacion', function (Blueprint $table) {
        });
    }
}
