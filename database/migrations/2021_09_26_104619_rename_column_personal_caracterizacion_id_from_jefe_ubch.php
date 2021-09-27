<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnPersonalCaracterizacionIdFromJefeUbch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jefe_ubch', function (Blueprint $table) {
            $table->renameColumn('personal_caraterizacion_id', 'personal_caracterizacion_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jefe_ubch', function (Blueprint $table) {
            //
        });
    }
}
