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
         Schema::table('camara', function (Blueprint $table) {
            // Eliminar la foreign key existente
            $table->dropForeign(['idcooler']);

            // Crear una nueva foreign key con onDelete('restrict')
            $table->foreign('idcooler')
                ->references('id')
                ->on('cooler')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('camara', function (Blueprint $table) {
            $table->dropForeign(['idcooler']);

            // Restaurar la relación original con cascade
            $table->foreign('idcooler')
                ->references('id')
                ->on('cooler')
                ->onDelete('cascade');
        });
    }
};
