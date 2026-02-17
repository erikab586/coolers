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
        Schema::table('detalle_contrato', function (Blueprint $table) {
            // agregamos las nuevas columnas
            $table->unsignedBigInteger('idpresentacion')->nullable()->after('idvariedad');
            $table->string('tiposervicio', 15)->nullable()->after('idpresentacion');

            // agregamos la relación foránea
            $table->foreign('idpresentacion')
                  ->references('id')
                  ->on('presentacion')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_contrato', function (Blueprint $table) {
            // primero quitamos la foreign key
            $table->dropForeign(['idpresentacion']);
            
            // luego eliminamos las columnas
            $table->dropColumn(['idpresentacion', 'tiposervicio']);
        });
    }
};
