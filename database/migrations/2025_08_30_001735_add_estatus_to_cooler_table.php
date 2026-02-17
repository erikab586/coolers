<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cooler', function (Blueprint $table) {
            $table->enum('estatus', ['activo', 'inactivo'])
                ->default('activo')
                ->after('ubicacion'); // lo agregamos después de 'ubicacion'
        });
    }

    public function down(): void
    {
        Schema::table('cooler', function (Blueprint $table) {
            $table->dropColumn('estatus');
        });
    }

};
