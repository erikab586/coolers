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
        Schema::create('detalle_conservacion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idconservacion');
            $table->unsignedBigInteger('iddetalle');
            $table->time('hora_entrada');
            $table->decimal('temperatura_entrada', 5, 2);
            $table->time('hora_salida')->nullable();
            $table->decimal('temperatura_salida', 5, 2)->nullable();
            $table->integer('tiempototal')->nullable(); // minutos totales
            $table->timestamps();

            // Claves foráneas
            $table->foreign('idconservacion')->references('id')->on('conservacion')->onDelete('restrict');
            $table->foreign('iddetalle')->references('id')->on('detalle_recepcion')->onDelete('restrict');
       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_conservacion');
    }
};
