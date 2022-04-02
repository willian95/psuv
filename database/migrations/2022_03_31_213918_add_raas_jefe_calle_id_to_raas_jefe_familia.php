<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRaasJefeCalleIdToRaasJefeFamilia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raas_jefe_familia', function (Blueprint $table) {
            $table->dropColumn("raas_jefe_calle_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('raas_jefe_familia', function (Blueprint $table) {
            //
        });
    }
}
