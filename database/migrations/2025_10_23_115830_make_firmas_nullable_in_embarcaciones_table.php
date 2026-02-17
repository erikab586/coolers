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
        Schema::table('embarcaciones', function (Blueprint $table) {
            // Hacer nullable los campos de firmas que anteriormente no lo eran
            $table->string('firma_usuario')->nullable()->change();
            $table->string('firma_cliente')->nullable()->change();
            $table->string('firma_chofer')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('embarcaciones', function (Blueprint $table) {
            // Revertir los cambios (hacer NOT NULL nuevamente)
            $table->string('firma_usuario')->nullable(false)->change();
            $table->string('firma_cliente')->nullable(false)->change();
            $table->string('firma_chofer')->nullable(false)->change();
        });
    }
};
