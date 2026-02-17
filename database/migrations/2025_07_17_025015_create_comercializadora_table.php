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
        Schema::create('comercializadora', function (Blueprint $table) {
            $table->id();
            $table->string('rfc')->unique();
            $table->string('nombrerepresentante');
            $table->string('numtelefono');
            $table->string('correo');
            $table->string('banco');
            $table->string('clave');
            $table->string('abreviatura');
            $table->string('imgcomercializadora')->nullable();
            $table->string('nombrecomercializadora');
            $table->string('estatus')->default('activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comercializadora');
    }
};
