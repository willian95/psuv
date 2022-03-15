<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnParroquiaIdInRaasCentroVotacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raas_centro_votacion', function (Blueprint $table) {
            $table->renameColumn('parroquia_id', 'raas_parroquia_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('raas_centro_votacion', function (Blueprint $table) {
        });
    }
}
