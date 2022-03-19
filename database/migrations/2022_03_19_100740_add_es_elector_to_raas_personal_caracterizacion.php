<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEsElectorToRaasPersonalCaracterizacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raas_personal_caracterizacion', function (Blueprint $table) {
            $table->boolean('es_elector')->default(true)->after('inhabilitado_politico');
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
        });
    }
}
