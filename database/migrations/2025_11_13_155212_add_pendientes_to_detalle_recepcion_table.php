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
        Schema::table('detalle_recepcion', function (Blueprint $table) {
            // Campo para controlar cuántas cajas quedan disponibles para asignar
            $table->integer('pendientes')->default(0)->after('cantidad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_recepcion', function (Blueprint $table) {
            $table->dropColumn('pendientes');
        });
    }
};
