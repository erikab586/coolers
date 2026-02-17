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
        Schema::create('preenfriado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idcamara')->constrained('camara')->onDelete('restrict');
            $table->foreignId('idtarima')->constrained('tarimas')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preenfriado');
    }
};
