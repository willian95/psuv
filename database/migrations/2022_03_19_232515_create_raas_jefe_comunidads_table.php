<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRaasJefeComunidadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raas_jefe_comunidad', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('raas_personal_caracterizacion_id');
            $table->unsignedBigInteger('censo_jefe_clap_id');

            $table->foreign('raas_personal_caracterizacion_id')->references('id')->on('raas_personal_caracterizacion');
            $table->foreign('censo_jefe_clap_id')->references('id')->on('censo_jefe_clap');

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
        Schema::dropIfExists('raas_jefe_comunidads');
    }
}
