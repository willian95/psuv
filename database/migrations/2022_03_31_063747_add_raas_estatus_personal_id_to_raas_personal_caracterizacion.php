<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRaasEstatusPersonalIdToRaasPersonalCaracterizacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raas_personal_caracterizacion', function (Blueprint $table) {
            $table->unsignedBigInteger("raas_estatus_personal_id")->nullable();
            $table->foreign("raas_estatus_personal_id")->references("id")->on("raas_estatus_personal");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('raas_personal_caracterizacion', function (Blueprint $table) {
            //
        });
    }
}
