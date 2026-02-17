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
        Schema::create('conservacion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idcamara'); // Relación con tabla camaras
            $table->unsignedBigInteger('idtarima'); // Relación con tabla tarimas
            $table->timestamps();

            // Claves foráneas
            $table->foreign('idcamara')->references('id')->on('camara')->onDelete('restrict');
            $table->foreign('idtarima')->references('id')->on('tarimas')->onDelete('restrict');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conservacion');
    }
};
