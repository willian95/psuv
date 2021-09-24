<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCentroVotacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('centro_votacion', function (Blueprint $table) {
            $table->id();
            $table->string("nombre");
            $table->string("codigo");
            $table->longText("direccion");
            $table->bigInteger('parroquia_id')->unsigned();
            $table->foreign('parroquia_id')->references('id')->on('parroquia')->onDelete('cascade');
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
        Schema::dropIfExists('centro_votacion');
    }
}
