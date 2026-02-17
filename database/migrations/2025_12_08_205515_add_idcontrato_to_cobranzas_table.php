<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Verificar si la columna ya existe
        if (!Schema::hasColumn('cobranzas', 'idcontrato')) {
            Schema::table('cobranzas', function (Blueprint $table) {
                // Agregar la columna
                $table->unsignedBigInteger('idcontrato')
                      ->nullable()
                      ->after('iddetallerecepcion');
                
                // Agregar la llave foránea
                $table->foreign('idcontrato')
                      ->references('id')
                      ->on('contrato')
                      ->onDelete('set null');
            });
        }
    }

    public function down()
    {
        // Verificar si la columna existe antes de intentar eliminarla
        if (Schema::hasColumn('cobranzas', 'idcontrato')) {
            Schema::table('cobranzas', function (Blueprint $table) {
                // Eliminar la llave foránea primero
                $table->dropForeign(['idcontrato']);
                // Luego eliminar la columna
                $table->dropColumn('idcontrato');
            });
        }
    }
};