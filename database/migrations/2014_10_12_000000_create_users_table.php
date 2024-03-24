<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id()->comment('Identificador');
            $table->string('nombre')->comment('Nombre del usuario');
            $table->string('apellido')->comment('Apelldido del usuario');
            $table->string('username')->comment('Cuenta para ingreso al sistema');
            $table->integer('rol')->comment('rol asignado(Administrador, Sucursal,Vendedor)');
            $table->string('password')->comment('clave');
            $table->unsignedBigInteger('sucursal_id')->nullable()->comment('Clave foranea con la tabla sucursal');
            $table->foreign('sucursal_id')->references('id')->on('sucursals');
            $table->string('imagen')->comment('Imagen representativa');
            $table->rememberToken()->commnet('token');
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
        Schema::dropIfExists('users');
    }
}
