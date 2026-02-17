<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('camara', function (Blueprint $table) {
            // Primero eliminar el índice único actual sobre "codigo"
            $table->dropUnique('camara_codigo_unique');

            // Luego agregar índice único compuesto sobre idcooler y codigo
            $table->unique(['idcooler', 'codigo'], 'unique_idcooler_codigo');
        });
    }

    public function down()
    {
        Schema::table('camara', function (Blueprint $table) {
            // Eliminar índice compuesto
            $table->dropUnique('unique_idcooler_codigo');

            // Restaurar índice único solo sobre codigo
            $table->unique('codigo');
        });
    }

};
