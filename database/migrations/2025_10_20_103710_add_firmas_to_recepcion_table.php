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
            $table->text('firma_responsable1')->nullable()->after('observaciones');
            $table->string('nombre_responsable1')->nullable()->after('firma_responsable1');
            $table->text('firma_responsable2')->nullable()->after('nombre_responsable1');
            $table->string('nombre_responsable2')->nullable()->after('firma_responsable2');
            $table->text('nota_firmas')->nullable()->after('nombre_responsable2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recepcion', function (Blueprint $table) {
            $table->dropColumn(['firma_responsable1', 'nombre_responsable1', 'firma_responsable2', 'nombre_responsable2', 'nota_firmas']);
        });
    }
};
