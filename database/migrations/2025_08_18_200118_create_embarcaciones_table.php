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
        Schema::create('embarcaciones', function (Blueprint $table) {
            $table->id(); // id primario incremental

            // Transporte
            $table->string('trans_placa', 100);
            $table->string('trans_placacaja', 100);
            $table->string('trans_temperaturacaja', 100);

            // Condiciones transporte
            $table->boolean('condtrans_estado');
            $table->boolean('condtrans_higiene');
            $table->boolean('condtrans_plagas');

            // Condiciones tarima
            $table->boolean('condtar_desmontado');
            $table->boolean('condtar_flejado');
            $table->boolean('condtar_distribucion');

            // Información carga
            $table->time('infcarga_hrallegada');
            $table->time('infcarga_hracarga');
            $table->time('infcarga_hrasalida');
            $table->string('infcarga_nsello', 100);
            $table->string('infcarga_nchismografo', 100);

            // Usuario responsable
            $table->foreignId('id_usuario')->constrained('users')->onDelete('restrict');
            $table->text('firma_usuario');

            // Cliente
            $table->string('nombre_responsblecliente', 100);
            $table->string('apellido_responsablecliente', 100);
            $table->text('firma_cliente');

            // Chofer
            $table->string('nombre_responsblechofer', 100);
            $table->string('apellido_responsablechofer', 100);
            $table->text('firma_chofer');

            // Transporte
            $table->string('linea_transporte', 100);

            // Totales
            $table->integer('total1');
            $table->integer('total2');
            $table->integer('total3');
            $table->integer('total4');
            $table->integer('total5');
            $table->integer('total6');

            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('embarcaciones');
    }
};
