<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('variedad', function (Blueprint $table) {
            $table->enum('estatus', ['activo', 'inactivo'])
                ->default('activo')
                ->after('tipofruta'); // se coloca después de tipofruta
        });
    }

    public function down(): void
    {
        Schema::table('variedad', function (Blueprint $table) {
            $table->dropColumn('estatus');
        });
    }

};
