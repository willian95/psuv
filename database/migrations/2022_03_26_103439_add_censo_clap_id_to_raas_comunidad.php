<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCensoClapIdToRaasComunidad extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raas_comunidad', function (Blueprint $table) {
            $table->unsignedBigInteger('censo_clap_id')->nullable();
            $table->foreign('censo_clap_id')->references('id')->on('censo_clap');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('raas_comunidad', function (Blueprint $table) {
        });
    }
}
