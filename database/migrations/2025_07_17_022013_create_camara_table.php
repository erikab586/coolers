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
        Schema::create('camara', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idcooler')->constrained('cooler')->onDelete('cascade');
            $table->string('codigo')->unique();
            $table->integer('capacidadminima');
            $table->integer('capacidadmaxima');
            $table->enum('tipo', ['PRE ENFRIADO', 'CONSERVACIÓN']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('camara');
    }
};
