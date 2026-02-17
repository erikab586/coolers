<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Actualizar los valores existentes al nuevo formato
        DB::table('recepcion')
            ->where('estatus', 'con detalle')
            ->update(['estatus' => 'CON DETALLE']);
            
        DB::table('recepcion')
            ->where('estatus', 'pendiente')
            ->update(['estatus' => 'TARIMA']);
            
        DB::table('recepcion')
            ->where('estatus', 'completado')
            ->update(['estatus' => 'FINALIZADO']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir al formato anterior
        DB::table('recepcion')
            ->where('estatus', 'CON DETALLE')
            ->update(['estatus' => 'con detalle']);
            
        DB::table('recepcion')
            ->where('estatus', 'TARIMA')
            ->update(['estatus' => 'pendiente']);
            
        DB::table('recepcion')
            ->where('estatus', 'FINALIZADO')
            ->update(['estatus' => 'completado']);
    }
};
