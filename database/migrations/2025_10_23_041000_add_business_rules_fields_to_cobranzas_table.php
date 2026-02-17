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
            // Campos para manejo de moneda y tipo de cambio
            $table->decimal('tipo_cambio', 10, 4)->default(1.0000)->after('moneda');
            $table->string('moneda_contrato')->default('MXN')->after('tipo_cambio');
            
            // Subtotales sin IVA
            $table->decimal('subtotal_preenfriado', 10, 2)->default(0)->after('monto_preenfriado');
            $table->decimal('subtotal_conservacion', 10, 2)->default(0)->after('monto_conservacion');
            $table->decimal('subtotal_anden', 10, 2)->default(0)->after('monto_anden');
            
            // IVA y total
            $table->decimal('iva', 10, 2)->default(0)->after('subtotal_anden');
            $table->decimal('total', 10, 2)->default(0)->after('iva');
            
            // Campos de control
            $table->integer('regla_aplicada')->nullable()->after('total');
            $table->boolean('tiene_cruce_anden')->default(false)->after('regla_aplicada');
            
            // Campos adicionales para cálculos
            $table->decimal('monto_conservacion_extra', 10, 2)->default(0)->after('tiene_cruce_anden')
                ->comment('Monto adicional de 0.12 por caja en conservación (más de 48 horas)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cobranzas', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_cambio',
                'moneda_contrato',
                'subtotal_preenfriado',
                'subtotal_conservacion',
                'subtotal_anden',
                'iva',
                'total',
                'regla_aplicada',
                'tiene_cruce_anden',
                'monto_conservacion_extra'
            ]);
        });
    }
};
