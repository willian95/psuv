<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCentroVotacionInRaasJefeUbch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raas_jefe_ubch', function (Blueprint $table) {
            $table->renameColumn('centro_votacion_id', 'raas_centro_votacion_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('raas_jefe_ubch', function (Blueprint $table) {
        });
    }
}
