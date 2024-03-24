<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProveedorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedors', function (Blueprint $table) {
            $table->id()->comment('Identificador');
            $table->string('nombre')->comment('Nombre del proveedor');
            $table->string('apellido')->comment('Apellido del proveedor');
            $table->string('empresa')->comment('Empresa');
            $table->string('celular')->comment('Celular');
            $table->string('observacion')->comment('onservacion requerida');
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
        Schema::dropIfExists('proveedors');
    }
}
