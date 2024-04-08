<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {

            $table->id()->comment('Identificador');
            $table->unsignedBigInteger('user_id')->comment('Clave foranea con la tabla user');
            $table->unsignedBigInteger('cliente_id')->comment('Clave foranea con la tabla cliente');
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('user_id')->references('id')->on('users');
            $table->dateTime('fecha')->comment('fecha en la cual se realizo la venta');
            $table->float('total')->comment('total de la venta realizada');
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
        Schema::dropIfExists('ventas');
    }
}
