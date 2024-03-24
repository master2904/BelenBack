<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id()->comment('Identificador');
            $table->unsignedBigInteger('proveedor_id')->comment('clave foranea con la tabla proveedor');
            $table->foreign('proveedor_id')->references('id')->on('proveedors');
            $table->unsignedBigInteger('producto_id')->nullable()->comment('clave foranea con la tabla producto');
            $table->foreign('producto_id')->references('id')->on('productos');
            $table->string('nuevo')->comment('Producto propenso a una adquisicion');
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
        Schema::dropIfExists('items');
    }
}
