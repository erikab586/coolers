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
        Schema::create('detalle_recepcion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idrecepcion');
            $table->unsignedBigInteger('idfruta');
            $table->unsignedBigInteger('idvariedad');
            $table->unsignedBigInteger('idpresentacion');
            $table->time('hora')->nullable();
            $table->decimal('temperatura', 5, 2)->nullable();
            $table->string('tipo')->nullable();
            $table->integer('cantidad')->nullable();
            $table->string('folio')->nullable();
            $table->timestamps();

            // Relaciones (ajustá los nombres de tabla si es necesario)
            $table->foreign('idrecepcion')->references('id')->on('recepcion')->onDelete('cascade');
            $table->foreign('idfruta')->references('id')->on('fruta')->onDelete('cascade');
            $table->foreign('idvariedad')->references('id')->on('variedad')->onDelete('cascade');
            $table->foreign('idpresentacion')->references('id')->on('presentacion')->onDelete('cascade');
       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_recepcion');
    }
};
