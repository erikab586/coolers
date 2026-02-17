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
        Schema::create('detalle_preenfriado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idpreenfrio')->constrained('preenfriado')->onDelete('restrict');
            $table->foreignId('iddetalle')->constrained('detalle_recepcion')->onDelete('restrict');
            $table->time('hora_entrada')->nullable();
            $table->decimal('temperatura_entrada', 5, 2)->nullable();
            $table->time('hora_salida')->nullable();
            $table->decimal('temperatura_salida', 5, 2)->nullable();
            $table->integer('tiempototal')->nullable()->comment('Tiempo total en minutos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_preenfriado');
    }
};
