<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddCruceAndenToDetalleRecepcionEstatus extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Agregar 'cruce_anden' al enum de estatus en la tabla detalle_recepcion
        DB::statement("ALTER TABLE detalle_recepcion MODIFY estatus ENUM('recepcion', 'tarima', 'preenfriado', 'conservacion', 'cruce_anden', 'embarcacion', 'salida') DEFAULT 'recepcion'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir al enum anterior sin 'cruce_anden'
        DB::statement("ALTER TABLE detalle_recepcion MODIFY estatus ENUM('recepcion', 'tarima', 'preenfriado', 'conservacion', 'embarcacion', 'salida') DEFAULT 'recepcion'");
    }
}
