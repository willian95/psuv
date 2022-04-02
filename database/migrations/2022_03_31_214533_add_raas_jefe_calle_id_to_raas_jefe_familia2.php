<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRaasJefeCalleIdToRaasJefeFamilia2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raas_jefe_familia', function (Blueprint $table) {
            $table->unsignedBigInteger("raas_jefe_calle_id");
            $table->foreign("raas_jefe_calle_id")->references("id")->on("raas_jefe_calle");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('raas_jefe_familia2', function (Blueprint $table) {
            //
        });
    }
}
