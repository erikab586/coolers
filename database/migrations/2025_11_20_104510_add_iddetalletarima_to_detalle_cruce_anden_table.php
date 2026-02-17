<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
    {
        Schema::table('detalle_cruce_anden', function (Blueprint $table) {
            $table->unsignedBigInteger('iddetalletarima')->nullable()->after('iddetalle');

            $table->foreign('iddetalletarima')
                ->references('id')->on('tarima_detarec')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('detalle_cruce_anden', function (Blueprint $table) {
            $table->dropForeign(['iddetalletarima']);
            $table->dropColumn('iddetalletarima');
        });
    }
};
