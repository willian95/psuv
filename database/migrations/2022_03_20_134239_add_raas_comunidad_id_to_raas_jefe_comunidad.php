<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRaasComunidadIdToRaasJefeComunidad extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raas_jefe_comunidad', function (Blueprint $table) {
            $table->unsignedBigInteger('raas_comunidad_id')->nullable();
            $table->foreign('raas_comunidad_id')->references('id')->on('raas_comunidad');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('raas_jefe_comunidad', function (Blueprint $table) {
        });
    }
}
