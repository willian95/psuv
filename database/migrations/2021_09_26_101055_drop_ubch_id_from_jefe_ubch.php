<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUbchIdFromJefeUbch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jefe_ubch', function (Blueprint $table) {
    
            //$table->dropForeign('jefe_ubch_ubch_id_foreign');
            //$table->dropIndex('jefe_ubch_ubch_id_foreign');
            //$table->dropColumn('ubch_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jefe_ubch', function (Blueprint $table) {
            
        });
    }
}
