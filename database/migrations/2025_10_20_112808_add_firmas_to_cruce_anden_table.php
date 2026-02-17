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
        Schema::table('cruce_anden', function (Blueprint $table) {
            $table->string('nombre_responsable1')->nullable()->after('idcamara');
            $table->string('nombre_responsable2')->nullable()->after('nombre_responsable1');
            $table->longText('firma_responsable1')->nullable()->after('nombre_responsable2');
            $table->longText('firma_responsable2')->nullable()->after('firma_responsable1');
            $table->longText('nota_firmas')->nullable()->after('firma_responsable2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cruce_anden', function (Blueprint $table) {
            $table->dropColumn(['nombre_responsable1', 'nombre_responsable2', 'firma_responsable1', 'firma_responsable2', 'nota_firmas']);
        });
    }
};
