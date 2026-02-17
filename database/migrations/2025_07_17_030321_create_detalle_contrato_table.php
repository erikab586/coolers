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
        Schema::create('detalle_contrato', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idcontrato');
            $table->unsignedBigInteger('idfruta');
            $table->unsignedBigInteger('idvariedad');
            $table->decimal('monto', 12, 2);
            $table->timestamps();
            $table->foreign('idcontrato')->references('id')->on('contrato')->onDelete('cascade');
            $table->foreign('idfruta')->references('id')->on('fruta')->onDelete('cascade');
            $table->foreign('idvariedad')->references('id')->on('variedad')->onDelete('cascade');
       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_contrato');
    }
};
