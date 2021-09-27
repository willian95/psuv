<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteCentroVotacionIndexFromUbch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ubch', function (Blueprint $table) {
            $table->dropForeign('ubch_centro_votacion_id_foreign');
            $table->dropIndex('ubch_centro_votacion_id_foreign');
            $table->dropColumn('centro_votacion_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ubch', function (Blueprint $table) {
            //
        });
    }
}
