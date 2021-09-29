<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IncrementedLengthFieldsInSomeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('elector', function (Blueprint $table) {
            $table->string("segundo_apellido",60)->nullable()->change();
            $table->string("segundo_nombre",60)->nullable()->change();
        });
        Schema::table('personal_caracterizacion', function (Blueprint $table) {
            $table->string("segundo_apellido",60)->nullable()->change();
            $table->string("segundo_nombre",60)->nullable()->change();
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
