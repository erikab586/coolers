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
        // Actualizar cobranzas existentes con valores por defecto
        DB::table('cobranzas')->update([
            'tipo_cambio' => 20.00,
            'moneda_contrato' => 'MXN',
            'subtotal_preenfriado' => DB::raw('total_preenfriado'),
            'subtotal_conservacion' => DB::raw('total_conservacion'),
            'subtotal_anden' => 0,
            'iva' => DB::raw('(total_preenfriado + total_conservacion) * 0.16'),
            'total' => DB::raw('(total_preenfriado + total_conservacion) * 1.16'),
            'regla_aplicada' => 1,
            'tiene_cruce_anden' => false,
            'monto_conservacion_extra' => 0,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No es necesario revertir
    }
};
