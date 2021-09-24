<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJefeComunidadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jefe_comunidad', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('comunidad_id')->unsigned();
            $table->foreign('comunidad_id')->references('id')->on('comunidad')->onDelete('cascade');
            $table->bigInteger('personal_caraterizacion_id')->unsigned();
            $table->foreign('personal_caraterizacion_id')->references('id')->on('personal_caracterizacion')->onDelete('cascade');
            $table->bigInteger('ubch_id')->unsigned();
            $table->foreign('ubch_id')->references('id')->on('ubch')->onDelete('cascade');
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
        Schema::dropIfExists('jefe_comunidad');
    }
}
