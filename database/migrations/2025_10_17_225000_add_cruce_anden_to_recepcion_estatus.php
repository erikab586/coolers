<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddCruceAndenToRecepcionEstatus extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Agregar 'EN CRUCE DE ANDÉN' al enum de estatus
        DB::statement("ALTER TABLE recepcion MODIFY estatus ENUM('CON DETALLE', 'TARIMA', 'EN PREENFRIADO', 'EN CONSERVACIÓN', 'EN CRUCE DE ANDÉN', 'EN EMBARQUE', 'FINALIZADO', 'CANCELADA') DEFAULT 'CON DETALLE'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir al enum anterior sin 'EN CRUCE DE ANDÉN'
        DB::statement("ALTER TABLE recepcion MODIFY estatus ENUM('CON DETALLE', 'TARIMA', 'EN PREENFRIADO', 'EN CONSERVACIÓN', 'EN EMBARQUE', 'FINALIZADO', 'CANCELADA') DEFAULT 'CON DETALLE'");
    }
};
