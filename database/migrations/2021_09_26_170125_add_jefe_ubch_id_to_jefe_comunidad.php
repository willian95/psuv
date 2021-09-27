<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJefeUbchIdToJefeComunidad extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jefe_comunidad', function (Blueprint $table) {
            $table->bigInteger('jefe_ubch_id')->unsigned();
            $table->foreign('jefe_ubch_id')->references('id')->on('jefe_ubch')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jefe_comunidad', function (Blueprint $table) {
            //
        });
    }
}
