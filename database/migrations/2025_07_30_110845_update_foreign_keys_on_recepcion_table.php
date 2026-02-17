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
        Schema::table('recepcion', function (Blueprint $table) {
            $table->dropForeign(['idusuario']);
            $table->dropForeign(['idcontrato']);

            $table->foreign('idusuario')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('idcontrato')->references('id')->on('contrato')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('recepcion', function (Blueprint $table) {
            $table->dropForeign(['idusuario']);
            $table->dropForeign(['idcontrato']);

            $table->foreign('idusuario')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('idcontrato')->references('id')->on('contrato')->onDelete('cascade');
        });
    }
};
