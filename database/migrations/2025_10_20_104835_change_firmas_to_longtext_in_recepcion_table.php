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
            $table->longText('firma_responsable1')->nullable()->change();
            $table->longText('firma_responsable2')->nullable()->change();
            $table->longText('nota_firmas')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recepcion', function (Blueprint $table) {
            $table->text('firma_responsable1')->nullable()->change();
            $table->text('firma_responsable2')->nullable()->change();
            $table->text('nota_firmas')->nullable()->change();
        });
    }
};
