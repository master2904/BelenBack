<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if(!Schema::hasColumn('items','precio'))
                $table->float('precio')->default(0);
        });
    }
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if(Schema::hasColumn('items','precio'))
                $table->dropColumn('precio');
        });
    }
};
