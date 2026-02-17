<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detalle_recepcion', function (Blueprint $table) {
            // Eliminar restricciones actuales
            $table->dropForeign(['idrecepcion']);
            $table->dropForeign(['idfruta']);
            $table->dropForeign(['idvariedad']);
            $table->dropForeign(['idpresentacion']);
        });

        Schema::table('detalle_recepcion', function (Blueprint $table) {
            // Agregar nuevas restricciones con onDelete('restrict')
            $table->foreign('idrecepcion')->references('id')->on('recepcion')->onDelete('restrict');
            $table->foreign('idfruta')->references('id')->on('fruta')->onDelete('restrict');
            $table->foreign('idvariedad')->references('id')->on('variedad')->onDelete('restrict');
            $table->foreign('idpresentacion')->references('id')->on('presentacion')->onDelete('restrict');

            // Agregar columna estatus
            $table->enum('estatus', [
                'recepcion',
                'tarima',
                'preenfriado',
                'conservacion',
                'embarcacion',
                'salida'
            ])->default('recepcion')->after('folio');
        });
    }

    public function down(): void
    {
        Schema::table('detalle_recepcion', function (Blueprint $table) {
            $table->dropForeign(['idrecepcion']);
            $table->dropForeign(['idfruta']);
            $table->dropForeign(['idvariedad']);
            $table->dropForeign(['idpresentacion']);
            $table->dropColumn('estatus');
        });

        Schema::table('detalle_recepcion', function (Blueprint $table) {
            $table->foreign('idrecepcion')->references('id')->on('recepcion')->onDelete('cascade');
            $table->foreign('idfruta')->references('id')->on('fruta')->onDelete('cascade');
            $table->foreign('idvariedad')->references('id')->on('variedad')->onDelete('cascade');
            $table->foreign('idpresentacion')->references('id')->on('presentacion')->onDelete('cascade');
        });
    }
};
