<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('embarcaciones', function (Blueprint $table) {
            $table->dateTime('infcarga_hrallegada')->change();
            $table->dateTime('infcarga_hracarga')->change();
            $table->dateTime('infcarga_hrasalida')->change();
        });
    }

    public function down(): void
    {
        Schema::table('embarcaciones', function (Blueprint $table) {
            $table->time('infcarga_hrallegada')->change();
            $table->time('infcarga_hracarga')->change();
            $table->time('infcarga_hrasalida')->change();
        });
    }
};
