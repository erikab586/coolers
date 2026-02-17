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
        Schema::create('cobranzas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idrecepcion');
            $table->unsignedBigInteger('iddetallerecepcion');
            $table->string('folio');
            $table->string('fruta');
            $table->string('presentacion');
            $table->string('variedad');
            $table->integer('cantidad');
            $table->decimal('monto_preenfriado', 10, 2)->default(0);
            $table->decimal('monto_conservacion', 10, 2)->default(0);
            $table->decimal('monto_anden', 10, 2)->default(0);
            $table->decimal('total_conservacion', 10, 2)->default(0);
            $table->decimal('total_preenfriado', 10, 2)->default(0);
            $table->integer('tiempo_preenfriado')->default(0); // en minutos
            $table->integer('tiempo_conservacion')->default(0); // en minutos
            $table->integer('tiempo_anden')->default(0); // en minutos
            $table->string('moneda')->default('MXN');
            $table->string('dia_recepcion')->nullable();
            $table->date('fecha_recepcion')->nullable();
            $table->enum('estatus', ['PAGADA', 'PENDIENTE'])->default('PENDIENTE');
            $table->timestamps();

            // Foreign keys
            $table->foreign('idrecepcion')->references('id')->on('recepcion')->onDelete('cascade');
            $table->foreign('iddetallerecepcion')->references('id')->on('detalle_recepcion')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cobranzas');
    }
};
