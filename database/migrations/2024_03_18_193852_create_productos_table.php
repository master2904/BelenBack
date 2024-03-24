<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id()->comment('Identificador');
            $table->integer('codigo')->comment('Codigo asignado');
            $table->string('descripcion')->comment('Descripcion');
            $table->string('imagen')->comment('Imgagen descriptiva');
            $table->integer('stock')->comment('Cantidad disponible');
            $table->integer('cantidad_minima')->comment('Cantidad minima que debe existir en almacen');
            $table->float('precio_compra')->comment('Precio a vender');
            $table->float('precio_venta')->comment('Precio adquirido');
            $table->unsignedBigInteger('categoria_id')->comment('Clave foranea con la tabla categoria');
            $table->foreign('categoria_id')->references('id')->on('categorias');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('productos');
    }
}
