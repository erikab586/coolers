<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::table('tarimas', function (Blueprint $table) {
            $table->enum('ubicacion', ['tarima', 'preenfriado', 'conservacion', 'embarque'])
                ->default('tarima')
                ->after('estatus');
        });
    }

    public function down(): void
    {
        Schema::table('tarimas', function (Blueprint $table) {
            $table->dropColumn('ubicacion');
        });
    }
};
