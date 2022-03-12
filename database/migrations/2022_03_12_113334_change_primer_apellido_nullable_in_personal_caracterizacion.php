<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePrimerApellidoNullableInPersonalCaracterizacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personal_caracterizacion', function (Blueprint $table) {
            $table->string('primer_apellido')->nullable()->change();
            $table->string('segundo_apellido')->nullable()->change();
            $table->string('segundo_nombre')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personal_caracterizacion', function (Blueprint $table) {
        });
    }
}
