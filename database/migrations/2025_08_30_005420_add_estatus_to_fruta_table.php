<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fruta', function (Blueprint $table) {
            $table->enum('estatus', ['activo', 'inactivo'])
                ->default('activo')
                ->after('imgfruta'); // se coloca después de imgfruta
        });
    }

    public function down(): void
    {
        Schema::table('fruta', function (Blueprint $table) {
            $table->dropColumn('estatus');
        });
    }

};
