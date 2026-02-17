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
        Schema::table('detalle_preenfriado', function (Blueprint $table) {
            // Cambiar hora_entrada de TIME a DATETIME
            $table->dateTime('hora_entrada')->nullable()->change();
            // Cambiar hora_salida de TIME a DATETIME
            $table->dateTime('hora_salida')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_preenfriado', function (Blueprint $table) {
            // Revertir a TIME
            $table->time('hora_entrada')->nullable()->change();
            $table->time('hora_salida')->nullable()->change();
        });
    }
};