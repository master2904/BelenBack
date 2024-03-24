<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistorialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historials', function (Blueprint $table) {
            $table->id()->comment('Identificador');
            $table->unsignedBigInteger('venta_id')->comment('Clave foranea con la tabla venta');
            $table->foreign('venta_id')->references('id')->on('ventas');
            $table->unsignedBigInteger('producto_id')->comment('Clave foranea con la tabla producto');
            $table->foreign('producto_id')->references('id')->on('productos');
            $table->integer('cantidad')->comment('cantidad vendida');
            $table->float('precio')->comment('precio vendido');
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
        Schema::dropIfExists('historials');
    }
}
