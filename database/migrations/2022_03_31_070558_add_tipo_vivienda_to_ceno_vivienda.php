<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoViviendaToCenoVivienda extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('censo_vivienda', function (Blueprint $table) {
            $table->string("tipo_vivienda");
            $table->removeColumn("cantidad_bolsas");
            $table->unsignedBigInteger("raas_jefe_familia_id");
            $table->foreign("raas_jefe_familia_id")->references("id")->on("raas_jefe_familia");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ceno_vivienda', function (Blueprint $table) {
            //
        });
    }
}
