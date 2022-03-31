<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnCensoTipoViviendaIdFromCensoVivienda extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('censo_vivienda', function (Blueprint $table) {
            $table->dropForeign("censo_viviendas_censo_tipo_vivienda_id_foreign");
            $table->dropColumn("censo_tipo_vivienda_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('censo_vivienda', function (Blueprint $table) {
            //
        });
    }
}
