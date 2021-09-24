<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJefeCallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jefe_calle', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('calle_id')->unsigned();
            $table->foreign('calle_id')->references('id')->on('calle')->onDelete('cascade');
            $table->bigInteger('personal_caraterizacion_id')->unsigned();
            $table->foreign('personal_caraterizacion_id')->references('id')->on('personal_caracterizacion')->onDelete('cascade');
            $table->bigInteger('jefe_comunidad_id')->unsigned();
            $table->foreign('jefe_comunidad_id')->references('id')->on('jefe_comunidad')->onDelete('cascade');
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
        Schema::dropIfExists('jefe_calle');
    }
}
