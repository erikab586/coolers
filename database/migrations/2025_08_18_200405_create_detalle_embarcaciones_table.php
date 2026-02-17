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
        Schema::create('detalle_embarcaciones', function (Blueprint $table) {
            $table->id(); // clave primaria incremental

            // Relación con embarcaciones
            $table->foreignId('idembarcacion')
                  ->constrained('embarcaciones')
                  ->onDelete('restrict');

            // Relación con conservaciones
            $table->foreignId('idconservacion')
                  ->constrained('conservacion')
                  ->onDelete('restrict');

            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_embarcaciones');
    }
};
