<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tarima_detarec', function (Blueprint $table) {
            // Agregar campos adicionales para desnormalización
            if (!Schema::hasColumn('tarima_detarec', 'folio')) {
                $table->string('folio')->nullable()->after('codigo');
            }
            if (!Schema::hasColumn('tarima_detarec', 'idfruta')) {
                $table->unsignedBigInteger('idfruta')->nullable()->after('folio');
            }
            if (!Schema::hasColumn('tarima_detarec', 'idpresentacion')) {
                $table->unsignedBigInteger('idpresentacion')->nullable()->after('idfruta');
            }
            if (!Schema::hasColumn('tarima_detarec', 'idvariedad')) {
                $table->unsignedBigInteger('idvariedad')->nullable()->after('idpresentacion');
            }
            if (!Schema::hasColumn('tarima_detarec', 'idcomercializadora')) {
                $table->unsignedBigInteger('idcomercializadora')->nullable()->after('idvariedad');
            }

            // Agregar foreign keys (solo si las columnas existen)
            if (Schema::hasColumn('tarima_detarec', 'idfruta')) {
                $table->foreign('idfruta')->references('id')->on('fruta')->onDelete('set null');
            }
            if (Schema::hasColumn('tarima_detarec', 'idpresentacion')) {
                $table->foreign('idpresentacion')->references('id')->on('presentacion')->onDelete('set null');
            }
            if (Schema::hasColumn('tarima_detarec', 'idvariedad')) {
                $table->foreign('idvariedad')->references('id')->on('variedad')->onDelete('set null');
            }
            if (Schema::hasColumn('tarima_detarec', 'idcomercializadora')) {
                $table->foreign('idcomercializadora')->references('id')->on('comercializadora')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tarima_detarec', function (Blueprint $table) {
            // Eliminar foreign keys primero
            $table->dropForeign(['idfruta']);
            $table->dropForeign(['idpresentacion']);
            $table->dropForeign(['idvariedad']);
            $table->dropForeign(['idcomercializadora']);
            
            // Eliminar columnas
            $table->dropColumn([
                'folio',
                'idfruta',
                'idpresentacion',
                'idvariedad',
                'idcomercializadora'
            ]);
        });
    }
};