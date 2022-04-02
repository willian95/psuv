<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddViviendaId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('censo_vivienda', function (Blueprint $table) {
            $table->unsignedBigInteger("vivienda_id")->nullable();
            $table->foreign("vivienda_id")->references("id")->on("censo_vivienda");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
