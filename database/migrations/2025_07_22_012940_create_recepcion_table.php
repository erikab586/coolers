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
        Schema::create('recepcion', function (Blueprint $table) {
            $table->id(); // ID
            $table->string('datosclave')->nullable(); // datosclave
            $table->string('area')->nullable();       // área
            $table->string('revision')->nullable();   // revisión
            $table->date('fechaemision')->nullable(); // fecha de emisión
            $table->string('imagen')->nullable();     // imagen (URL o nombre de archivo)
            $table->string('folio')->nullable();      // folio
            $table->enum('estatus', [
            'CON DETALLE', 'TARIMA', 'EN PREENFRIADO',
            'EN CONSERVACIÓN', 'EN EMBARQUE', 'FINALIZADO'
            ])->default('CON DETALLE');
            // Relaciones
            $table->unsignedBigInteger('idusuario');
            $table->unsignedBigInteger('idcontrato');
            // Claves foráneas
            $table->foreign('idusuario')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('idcontrato')->references('id')->on('contrato')->onDelete('cascade');
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recepcion');
    }
};
