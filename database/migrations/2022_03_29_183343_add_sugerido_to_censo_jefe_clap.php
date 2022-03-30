<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSugeridoToCensoJefeClap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('censo_jefe_clap', function (Blueprint $table) {
            $table->integer("sugerido")->nullable();
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
            //
        });
    }
}
