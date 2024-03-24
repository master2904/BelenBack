<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id()->comment('Identificador');
            $table->string('nit')->nullable(true)->comment('Nit o CI del cliente');
            $table->string('nombre')->comment('nombre del cliente');
            $table->string('celular')->nullable()->comment('celular cliente');
            $table->string('direccion')->nullable()->comment('direccion cliente');
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
        Schema::dropIfExists('clientes');
    }
}
