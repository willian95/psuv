<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePersonalCaracterizacionInRaasJefeUbch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raas_jefe_ubch', function (Blueprint $table) {
            $table->renameColumn('personal_caracterizacion_id', 'raas_personal_caracterizacion_id');
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
