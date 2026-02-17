<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tarimas', function (Blueprint $table) {
            $table->enum('estatuseliminar', ['activo', 'inactivo'])
                  ->default('activo')
                  ->after('ubicacion'); // lo colocamos después de 'ubicacion'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tarimas', function (Blueprint $table) {
            $table->dropColumn('estatuseliminar');
        });
    }
};
