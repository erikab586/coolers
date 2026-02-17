<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tarimas', function (Blueprint $table) {
            // Eliminar la columna cantidad_usada
            if (Schema::hasColumn('tarimas', 'cantidad_usada')) {
                $table->dropColumn('cantidad_usada');
            }

            // Cambiar enum: primero se elimina y luego se vuelve a crear (no se puede cambiar enum directamente)
            $table->dropColumn('estatus');
            $table->enum('estatus', ['disponible', 'completo'])->default('disponible');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tarimas', function (Blueprint $table) {
            $table->integer('cantidad_usada')->nullable();

            $table->dropColumn('estatus');
            $table->enum('estatus', ['vacio', 'disponible', 'completo'])->default('vacio');
        });
    }
};
