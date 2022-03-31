<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCensoOrdenOperacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('censo_orden_operaciones', function (Blueprint $table) {
            $table->id();
            $table->string("operacion");
            $table->integer("valor_inicio");
            $table->integer("valor_fin");
            $table->integer("cantidad_bolsas");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('censo_orden_operaciones');
    }
}
