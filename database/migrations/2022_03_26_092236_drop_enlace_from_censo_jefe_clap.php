<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropEnlaceFromCensoJefeClap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('censo_jefe_clap', function (Blueprint $table) {
            $table->dropForeign(['censo_enlace_municipal_id']);
            $table->dropColumn('censo_enlace_municipal_id');
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
