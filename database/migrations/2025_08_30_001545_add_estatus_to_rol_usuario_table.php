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
        Schema::table('rol_usuario', function (Blueprint $table) {
            $table->enum('estatus', ['activo', 'inactivo'])
                ->default('activo')
                ->after('nombrerol'); // lo agrega después del campo nombrerol
        });
    }

    public function down(): void
    {
        Schema::table('rol_usuario', function (Blueprint $table) {
            $table->dropColumn('estatus');
        });
    }

};
