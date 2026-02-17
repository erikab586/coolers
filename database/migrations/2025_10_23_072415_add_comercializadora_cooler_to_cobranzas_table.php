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
            // Agregar campos para identificar comercializadora y cooler directamente
            $table->unsignedBigInteger('idcomercializadora')->nullable()->after('iddetallerecepcion');
            $table->unsignedBigInteger('idcooler')->nullable()->after('idcomercializadora');
            
            // Foreign keys
            $table->foreign('idcomercializadora')->references('id')->on('comercializadora')->onDelete('set null');
            $table->foreign('idcooler')->references('id')->on('cooler')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cobranzas', function (Blueprint $table) {
            $table->dropForeign(['idcomercializadora']);
            $table->dropForeign(['idcooler']);
            $table->dropColumn(['idcomercializadora', 'idcooler']);
        });
    }
};
