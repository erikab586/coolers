<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recepcion', function (Blueprint $table) {
            $table->enum('estatuseliminar', ['activo', 'inactivo'])
                  ->default('activo')
                  ->after('folio'); // lo agrega después de la columna folio
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recepcion', function (Blueprint $table) {
            $table->dropColumn('estatuseliminar');
        });
    }
};
