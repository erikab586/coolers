<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tarima_detarec', function (Blueprint $table) {
            if (Schema::hasColumn('tarima_detarec', 'cantidad')) {
                $table->dropColumn('cantidad');
            }

            if (Schema::hasColumn('tarima_detarec', 'cantidad_usada')) {
                $table->dropColumn('cantidad_usada');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tarima_detarec', function (Blueprint $table) {
            // Solo si deseas volver atrás
            $table->integer('cantidad')->nullable();
            $table->integer('cantidad_usada')->nullable();
        });
    }
};
