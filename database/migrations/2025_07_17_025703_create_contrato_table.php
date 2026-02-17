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
        Schema::create('contrato', function (Blueprint $table) {
            $table->id();
            $table->enum('tipocliente', ['EXPORTACIÓN', 'IMPORTACIÓN']);
            $table->enum('tipomoneda', ['PESO', 'DOLAR']);
            $table->enum('tipocontrato', ['TEMPORADA', 'ANUAL']);
            $table->unsignedBigInteger('idcomercializadora');
            $table->unsignedBigInteger('idusuario');
            $table->unsignedBigInteger('idcooler');
            $table->string('imagen')->nullable();
            $table->string('estatus')->default('activo');
            $table->date('fechacontrato');
            $table->timestamps();
            $table->foreign('idcomercializadora')->references('id')->on('comercializadora')->onDelete('cascade');
            $table->foreign('idusuario')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('idcooler')->references('id')->on('cooler')->onDelete('cascade');
       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrato');
    }
};
