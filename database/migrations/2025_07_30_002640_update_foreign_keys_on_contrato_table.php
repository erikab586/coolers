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
        Schema::table('contrato', function (Blueprint $table) {
            $table->dropForeign(['idcomercializadora']);
            $table->dropForeign(['idusuario']);
            $table->dropForeign(['idcooler']);

            $table->foreign('idcomercializadora')
                  ->references('id')
                  ->on('comercializadora')
                  ->onDelete('restrict');

            $table->foreign('idusuario')
                  ->references('id')
                  ->on('users')
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
        Schema::table('contrato', function (Blueprint $table) {
            $table->dropForeign(['idcomercializadora']);
            $table->dropForeign(['idusuario']);
            $table->dropForeign(['idcooler']);

            $table->foreign('idcomercializadora')
                  ->references('id')
                  ->on('comercializadora')
                  ->onDelete('cascade');

            $table->foreign('idusuario')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('idcooler')
                  ->references('id')
                  ->on('cooler')
                  ->onDelete('cascade');
        });
    }
};
