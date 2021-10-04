<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMunicipioIdToMetasUbches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('metas_ubches', function (Blueprint $table) {
            $table->bigInteger('municipio_id')->unsigned()->nullable();
            $table->foreign('municipio_id')->references('id')->on('municipio')->onDelete('cascade');
            $table->bigInteger('parroquia_id')->unsigned()->nullable();
            $table->foreign('parroquia_id')->references('id')->on('parroquia')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('metas_ubches', function (Blueprint $table) {
            //
        });
    }
}
