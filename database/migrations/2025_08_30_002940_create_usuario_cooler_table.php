<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuario_cooler', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->unsignedBigInteger('idusuario');
            $table->unsignedBigInteger('idcooler');

            // Llaves foráneas
            $table->foreign('idusuario')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('idcooler')->references('id')->on('cooler')->onDelete('cascade');

            // Evita duplicados (un usuario no puede estar dos veces en el mismo cooler)
            $table->unique(['idusuario', 'idcooler']);

            // Estatus (activo/inactivo)
            $table->enum('estatus', ['activo', 'inactivo'])->default('activo');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario_cooler');
    }

};
