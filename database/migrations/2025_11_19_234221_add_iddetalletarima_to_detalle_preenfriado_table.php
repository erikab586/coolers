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
        Schema::table('detalle_preenfriado', function (Blueprint $table) {
            $table->unsignedBigInteger('iddetalletarima')->nullable()->after('iddetalle');
            // Opcional: si quieres FK
            $table->foreign('iddetalletarima')
                   ->references('id')->on('tarima_detarec')
                   ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('detalle_preenfriado', function (Blueprint $table) {
            // Si agregaste FK, primero la quitas:
            // $table->dropForeign(['iddetalletarima']);
            $table->dropColumn('iddetalletarima');
        });
    }
};
