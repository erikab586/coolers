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
        // Migrar datos de tipomoneda del contrato a moneda en detalle_contrato
        DB::statement("
            UPDATE detalle_contrato dc
            INNER JOIN contrato c ON dc.idcontrato = c.id
            SET dc.moneda = c.tipomoneda
            WHERE dc.moneda IS NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No es necesario revertir, ya que los datos originales se mantienen en contrato
    }
};
