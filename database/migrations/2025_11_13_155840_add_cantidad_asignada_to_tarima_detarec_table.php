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
        Schema::table('tarima_detarec', function (Blueprint $table) {
            $table->integer('cantidad_asignada')
                  ->default(0)
                  ->after('idtarima');
        });
    }

    public function down(): void
    {
        Schema::table('tarima_detarec', function (Blueprint $table) {
            $table->dropColumn('cantidad_asignada');
        });
    }
};
