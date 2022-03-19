<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeRaasCentroVotacionIdToNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raas_personal_caracterizacion', function (Blueprint $table) {
            $table->bigInteger('raas_estado_id')->nullable()->change();
            $table->bigInteger('raas_municipio_id')->nullable()->change();
            $table->bigInteger('raas_parroquia_id')->nullable()->change();
            $table->bigInteger('raas_centro_votacion_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nullable', function (Blueprint $table) {
        });
    }
}
