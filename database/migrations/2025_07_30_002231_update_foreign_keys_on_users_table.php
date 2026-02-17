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
         Schema::table('users', function (Blueprint $table) {
            // Eliminar las foreign keys actuales
            $table->dropForeign(['idrol']);
            $table->dropForeign(['idcooler']);

            // Crear las nuevas foreign keys con onDelete('restrict')
            $table->foreign('idrol')
                ->references('id')
                ->on('rol_usuario')
                ->onDelete('restrict');

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
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['idrol']);
            $table->dropForeign(['idcooler']);

            // Restaurar las foreign keys con onDelete('cascade')
            $table->foreign('idrol')
                ->references('id')
                ->on('rol_usuario')
                ->onDelete('cascade');

            $table->foreign('idcooler')
                ->references('id')
                ->on('cooler')
                ->onDelete('cascade');
        });
    }
};
