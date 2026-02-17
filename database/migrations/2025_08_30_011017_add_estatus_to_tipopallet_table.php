<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tipopallet', function (Blueprint $table) {
            $table->enum('estatus', ['activo', 'inactivo'])
                ->default('activo')
                ->after('tipopallet'); // se coloca después del nombre del tipo de pallet
        });
    }

    public function down(): void
    {
        Schema::table('tipopallet', function (Blueprint $table) {
            $table->dropColumn('estatus');
        });
    }

};
