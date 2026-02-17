<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Primero eliminar la restricción de clave foránea
            $table->dropForeign(['idcooler']);
            // Luego eliminar la columna
            $table->dropColumn('idcooler');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Volvemos a crear la columna en caso de rollback
            $table->unsignedBigInteger('idcooler')->nullable()->after('idrol');
            $table->foreign('idcooler')->references('id')->on('cooler')->onDelete('cascade');
        });
    }

};
