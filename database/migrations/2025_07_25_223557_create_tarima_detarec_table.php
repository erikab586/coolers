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
        Schema::create('tarima_detarec', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('iddetalle')->nullable();
            $table->unsignedBigInteger('idtarima')->nullable();
            $table->unsignedBigInteger('idtipopallet')->nullable();

            $table->string('codigo')->nullable(); // Formato: TAR-00{idtarima}{iddetalle}
            $table->integer('cantidad')->nullable();
            $table->integer('cantidad_usada')->nullable();

            $table->enum('estatus', ['vacio', 'disponible', 'completo'])->nullable();

            $table->timestamps();

            // Relaciones (opcional si usarás integridad referencial)
            $table->foreign('iddetalle')->references('id')->on('detalle_recepcion')->onDelete('set null');
            $table->foreign('idtarima')->references('id')->on('tarimas')->onDelete('set null');
            $table->foreign('idtipopallet')->references('id')->on('tipopallet')->onDelete('set null');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarima_detarec');
    }
};
