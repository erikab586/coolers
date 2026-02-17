<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conservacion', function (Blueprint $table) {
            $table->text('observaciones')->nullable()->after('idcamara');
        });
    }

    public function down(): void
    {
        Schema::table('conservacion', function (Blueprint $table) {
            $table->dropColumn('observaciones');
        });
    }
};
