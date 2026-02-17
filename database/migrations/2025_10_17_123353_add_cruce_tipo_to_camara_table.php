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
        // Modificar el enum para incluir 'CRUCE DE ANDÉN'
        DB::statement("ALTER TABLE camara MODIFY COLUMN tipo ENUM('PRE ENFRIADO', 'CONSERVACIÓN', 'CRUCE DE ANDÉN') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir al enum original
        DB::statement("ALTER TABLE camara MODIFY COLUMN tipo ENUM('PRE ENFRIADO', 'CONSERVACIÓN') NOT NULL");
    }
};
