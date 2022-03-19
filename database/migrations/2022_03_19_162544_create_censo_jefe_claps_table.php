<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCensoJefeClapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('censo_jefe_clap', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('raas_personal_caracterizacion_id');
            $table->foreign('raas_personal_caracterizacion_id')->references('id')->on('raas_personal_caracterizacion');
            $table->unsignedBigInteger('censo_enlace_municipal_id');
            $table->foreign('censo_enlace_municipal_id')->references('id')->on('censo_enlace_municipal');
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
        Schema::dropIfExists('censo_jefe_claps');
    }
}
