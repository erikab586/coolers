<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tarima_detarec', function (Blueprint $table) {
            if (!Schema::hasColumn('tarima_detarec', 'cantidadcarga')) {
                $table->integer('cantidadcarga')->nullable()->after('cantidad_asignada');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tarima_detarec', function (Blueprint $table) {
            $table->dropColumn('cantidadcarga');
        });
    }
};