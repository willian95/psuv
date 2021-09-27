<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUbchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ubch', function (Blueprint $table) {
            $table->id();
            $table->string("nombre",140);
            $table->bigInteger('centro_votacion_id')->unsigned();
            $table->foreign('centro_votacion_id')->references('id')->on('centro_votacion')->onDelete('cascade');
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
        Schema::dropIfExists('ubch');
    }
}