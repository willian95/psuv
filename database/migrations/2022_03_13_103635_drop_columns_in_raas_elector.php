<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnsInRaasElector extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raas_elector', function (Blueprint $table) {
            $table->dropColumn(['primer_nombre', 'primer_apellido', 'segundo_nombre', 'segundo_apellido']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('raas_elector', function (Blueprint $table) {
        });
    }
}
