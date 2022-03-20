<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRaasJefeCallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raas_jefe_calle', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('raas_personal_caracterizacion_id');
            $table->foreign('raas_personal_caracterizacion_id')->references('id')->on('raas_personal_caracterizacion');
            $table->unsignedBigInteger('raas_calle_id');
            $table->foreign('raas_calle_id')->references('id')->on('raas_calle');
            $table->unsignedBigInteger('raas_jefe_comunidad_id');
            $table->foreign('raas_jefe_comunidad_id')->references('id')->on('raas_jefe_comunidad');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('raas_jefe_calles');
    }
}
