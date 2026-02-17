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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Ej: 'crear_usuarios', 'editar_coolers'
            $table->string('display_name')->nullable(); // Nombre legible
            $table->string('description')->nullable(); // Descripción del permiso
            $table->string('module')->nullable(); // Módulo al que pertenece (usuarios, coolers, etc)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
