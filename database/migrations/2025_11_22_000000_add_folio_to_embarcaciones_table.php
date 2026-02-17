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
        Schema::table('embarcaciones', function (Blueprint $table) {
            // Nuevo folio tipo E00001, E00002, etc.
            $table->string('folio', 10)->nullable()->unique()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('embarcaciones', function (Blueprint $table) {
            $table->dropColumn('folio');
        });
    }
};
