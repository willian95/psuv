<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCodigoCnesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('codigo_cnes', function (Blueprint $table) {
            $table->id();
            $table->integer("estado_id");
            $table->integer("cod_edo_cne");
            $table->integer("municipio_id");
            $table->integer("cod_mcpo_cne");
            $table->integer("parroquia_id");
            $table->integer("cod_pq_cne");
            $table->integer("centro_votacion_id");
            $table->integer("cod_cv_cne");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('codigo_cnes');
    }
}
