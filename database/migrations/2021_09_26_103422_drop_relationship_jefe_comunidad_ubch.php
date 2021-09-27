<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropRelationshipJefeComunidadUbch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jefe_comunidad', function (Blueprint $table) {
            $table->dropForeign('jefe_comunidad_ubch_id_foreign');
            // $table->dropIndex('jefe_comunidad_ubch_id_foreign');
            $table->dropColumn('ubch_id');
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
