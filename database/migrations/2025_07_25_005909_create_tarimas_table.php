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
        Schema::create('tarimas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique(); // Ej: TAR-0001
            $table->integer('cantidad')->default(0);
            $table->integer('capacidad')->default(732);
            $table->integer('cantidad_usada')->nullable(); // Puede comenzar null si aún no se usa
            $table->enum('estatus', ['vacio', 'disponible', 'completo'])->default('vacio');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarimas');
    }
};
