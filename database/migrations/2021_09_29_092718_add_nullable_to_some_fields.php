<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNullableToSomeFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('elector', function (Blueprint $table) {
            $table->string("segundo_apellido",25)->nullable()->change();
            $table->string("segundo_nombre",25)->nullable()->change();
        });
        Schema::table('personal_caracterizacion', function (Blueprint $table) {
            $table->string("segundo_apellido",25)->nullable()->change();
            $table->string("segundo_nombre",25)->nullable()->change();
        });
        Schema::table('centro_votacion', function (Blueprint $table) {
            $table->longText("direccion")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
