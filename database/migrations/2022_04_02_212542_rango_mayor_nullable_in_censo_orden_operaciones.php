<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RangoMayorNullableInCensoOrdenOperaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('censo_orden_operaciones', function (Blueprint $table) {
            $table->integer("valor_fin")->nullable()->change();
            $table->integer("valor_inicio")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('censo_orden_operaciones', function (Blueprint $table) {
            //
        });
    }
}
