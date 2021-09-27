<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPartidoPoliticoIdToPersonalCaracterizacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personal_caracterizacion', function (Blueprint $table) {
            $table->bigInteger('partido_politico_id')->unsigned();
            $table->foreign('partido_politico_id')->references('id')->on('partido_politico')->onDelete('cascade');

            $table->bigInteger('movilizacion_id')->unsigned();
            $table->foreign('movilizacion_id')->references('id')->on('movilizacion')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personal_caracterizacion', function (Blueprint $table) {
            //
        });
    }
}
