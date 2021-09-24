<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalCaracterizacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_caracterizacion', function (Blueprint $table) {
            $table->id();
            $table->string("nacionalidad",10);
            $table->string("cedula",10);
            $table->string("primer_apellido",25);
            $table->string("segundo_apellido",25);
            $table->string("primer_nombre",25);
            $table->string("segundo_nombre",25);
            $table->string("sexo",10);
            $table->string("telefono_principal",11)->nullable();
            $table->string("telefono_secundario",11)->nullable();
            $table->date("fecha_nacimiento")->nullable();
            $table->string("tipo_voto",15);
            $table->boolean("inhabilitado_politicio")->default(false);
            $table->bigInteger('estado_id')->unsigned();
            $table->foreign('estado_id')->references('id')->on('estado')->onDelete('cascade');
            $table->bigInteger('municipio_id')->unsigned();
            $table->foreign('municipio_id')->references('id')->on('municipio')->onDelete('cascade');
            $table->bigInteger('parroquia_id')->unsigned();
            $table->foreign('parroquia_id')->references('id')->on('parroquia')->onDelete('cascade');
            $table->bigInteger('centro_votacion_id')->unsigned();
            $table->foreign('centro_votacion_id')->references('id')->on('centro_votacion')->onDelete('cascade');
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
        Schema::dropIfExists('personal_caracterizacion');
    }
}
