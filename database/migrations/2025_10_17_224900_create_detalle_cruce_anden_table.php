<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleCruceAndenTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detalle_cruce_anden', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idcruce_anden');
            $table->unsignedBigInteger('iddetalle'); // detalle_recepcion
            $table->time('hora_entrada')->nullable();
            $table->decimal('temperatura_entrada', 5, 2)->nullable();
            $table->time('hora_salida')->nullable();
            $table->decimal('temperatura_salida', 5, 2)->nullable();
            $table->integer('tiempototal')->nullable(); // en minutos
            $table->timestamps();

            $table->foreign('idcruce_anden')->references('id')->on('cruce_anden')->onDelete('cascade');
            $table->foreign('iddetalle')->references('id')->on('detalle_recepcion')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_cruce_anden');
    }
};
