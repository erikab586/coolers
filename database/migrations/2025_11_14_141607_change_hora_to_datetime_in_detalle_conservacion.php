<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detalle_conservacion', function (Blueprint $table) {
            $table->dateTime('hora_entrada')->change();
            $table->dateTime('hora_salida')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('detalle_conservacion', function (Blueprint $table) {
            $table->time('hora_entrada')->change();
            $table->time('hora_salida')->nullable()->change();
        });
    }
};