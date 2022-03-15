<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnMuncipioIdFromRaasElector extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raas_elector', function (Blueprint $table) {
            $table->renameColumn('municipio_id', 'raas_municipio_id');
            $table->renameColumn('parroquia_id', 'raas_parroquia_id');
            $table->renameColumn('centro_votacion_id', 'raas_centro_votacion_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('raas_elector', function (Blueprint $table) {
        });
    }
}
