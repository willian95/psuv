<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnsInRaasJefeFamilia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raas_jefe_familia', function (Blueprint $table) {
            $table->renameColumn('personal_caraterizacion_id', 'raas_personal_caracterizacion_id');
            $table->renameColumn('jefe_calle_id', 'raas_jefe_calle_id');
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
        });
    }
}
