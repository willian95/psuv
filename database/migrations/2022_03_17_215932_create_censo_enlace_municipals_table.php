<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCensoEnlaceMunicipalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('censo_enlace_municipal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('raas_personal_caracterizacion_id');
            $table->unsignedBigInteger('raas_municipio_id');

            $table->foreign('raas_personal_caracterizacion_id')->references('id')->on('raas_personal_caracterizacion');
            $table->foreign('raas_municipio_id')->references('id')->on('raas_municipio');

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
        Schema::dropIfExists('censo_enlace_municipals');
    }
}
