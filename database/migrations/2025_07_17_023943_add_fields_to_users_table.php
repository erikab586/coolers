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
        Schema::table('users', function (Blueprint $table) {
            // Agregar idrol primero para poder usarlo en after()
            $table->unsignedBigInteger('idrol')->after('id');
            $table->string('apellidos')->after('name');
            $table->string('telefono')->nullable()->after('apellidos');
            $table->string('estatus')->default('activo')->after('password');
            $table->dateTime('fechaconexion')->nullable()->after('estatus');
            $table->unsignedBigInteger('idcooler')->nullable()->after('idrol');
            // Llaves foráneas
            $table->foreign('idrol')->references('id')->on('rol_usuario')->onDelete('cascade');
            $table->foreign('idcooler')->references('id')->on('cooler')->onDelete('cascade');
       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['idrol']);
            $table->dropForeign(['idcooler']);
            $table->dropColumn(['idrol', 'apellidos', 'telefono', 'estatus', 'fechaconexion', 'idcooler']);
     
        });
    }
};
