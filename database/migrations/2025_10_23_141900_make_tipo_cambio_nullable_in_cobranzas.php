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
        Schema::table('cobranzas', function (Blueprint $table) {
            // Modificar tipo_cambio para que sea nullable
            $table->decimal('tipo_cambio', 10, 4)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cobranzas', function (Blueprint $table) {
            // Revertir a no nullable con valor por defecto
            $table->decimal('tipo_cambio', 10, 4)->default(1.0000)->change();
        });
    }
};
