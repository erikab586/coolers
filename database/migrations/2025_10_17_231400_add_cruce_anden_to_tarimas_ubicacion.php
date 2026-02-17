<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddCruceAndenToTarimasUbicacion extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Agregar 'cruce_anden' al enum de ubicacion en la tabla tarimas
        DB::statement("ALTER TABLE tarimas MODIFY ubicacion ENUM('tarima', 'preenfriado', 'conservacion', 'cruce_anden', 'embarque') DEFAULT 'tarima'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir al enum anterior sin 'cruce_anden'
        DB::statement("ALTER TABLE tarimas MODIFY ubicacion ENUM('tarima', 'preenfriado', 'conservacion', 'embarque') DEFAULT 'tarima'");
    }
}
