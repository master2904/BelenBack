<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            if(!Schema::hasColumn('categorias','codigo'))
                $table->string('codigo')->nullable();
        });
    }
    public function down(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            if(Schema::hasColumn('categorias','codigo'))
                $table->dropColumn('codigo');
        });
    }
};
