<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCensoClapToCensoJefeClap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('censo_jefe_clap', function (Blueprint $table) {
            $table->unsignedBigInteger('censo_clap_id')->unsigned()->nullable()->after('id');
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
        Schema::table('censo_jefe_clap', function (Blueprint $table) {
        });
    }
}
