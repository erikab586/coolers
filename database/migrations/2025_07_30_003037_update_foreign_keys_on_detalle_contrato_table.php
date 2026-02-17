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
            $table->dropForeign(['idcontrato']);
            $table->dropForeign(['idfruta']);
            $table->dropForeign(['idvariedad']);

            $table->foreign('idcontrato')
                  ->references('id')
                  ->on('contrato')
                  ->onDelete('restrict');

            $table->foreign('idfruta')
                  ->references('id')
                  ->on('fruta')
                  ->onDelete('restrict');

            $table->foreign('idvariedad')
                  ->references('id')
                  ->on('variedad')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('detalle_contrato', function (Blueprint $table) {
            $table->dropForeign(['idcontrato']);
            $table->dropForeign(['idfruta']);
            $table->dropForeign(['idvariedad']);

            $table->foreign('idcontrato')
                  ->references('id')
                  ->on('contrato')
                  ->onDelete('cascade');

            $table->foreign('idfruta')
                  ->references('id')
                  ->on('fruta')
                  ->onDelete('cascade');

            $table->foreign('idvariedad')
                  ->references('id')
                  ->on('variedad')
                  ->onDelete('cascade');
        });
    }
};
