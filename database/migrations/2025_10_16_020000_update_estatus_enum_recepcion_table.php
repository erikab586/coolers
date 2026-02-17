<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Actualizar el enum de estatus con los valores correctos
        DB::statement("ALTER TABLE recepcion MODIFY estatus ENUM('CON DETALLE', 'TARIMA', 'EN PREENFRIADO', 'EN CONSERVACIÓN', 'EN EMBARQUE', 'FINALIZADO') DEFAULT 'CON DETALLE'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE recepcion MODIFY estatus ENUM('con detalle', 'pendiente', 'completado') DEFAULT 'con detalle'");
    }
};
