<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up(): void
    {
        Schema::table('embarcaciones', function (Blueprint $table) {
            $table->string('prod_ultimacarga', 150)->nullable()->after('condtrans_plagas');
        });
    }

    public function down(): void
    {
        Schema::table('embarcaciones', function (Blueprint $table) {
            $table->dropColumn('prod_ultimacarga');
        });
    }
};
