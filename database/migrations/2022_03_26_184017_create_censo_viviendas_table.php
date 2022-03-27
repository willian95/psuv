<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCensoViviendasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('censo_viviendas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->unsignedBigInteger('censo_tipo_vivienda_id');
            $table->foreign('censo_tipo_vivienda_id')->references('id')->on('censo_tipo_vivienda');
            $table->integer('cantidad_familias');
            $table->unsignedBigInteger('raas_calle_id');
            $table->foreign('raas_calle_id')->references('id')->on('raas_calle');
            $table->integer('cantidad_bolsas');
            $table->text('direccion');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('censo_viviendas');
    }
}
