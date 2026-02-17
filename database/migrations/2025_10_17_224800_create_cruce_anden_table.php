<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCruceAndenTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cruce_anden', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idtarima');
            $table->unsignedBigInteger('idcamara');
            $table->timestamps();

            $table->foreign('idtarima')->references('id')->on('tarimas')->onDelete('cascade');
            $table->foreign('idcamara')->references('id')->on('camara')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cruce_anden');
    }
};
