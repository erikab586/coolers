<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class DetallePreenfriado extends Model
    {
        use HasFactory;

        protected $table = 'detalle_preenfriado';

        protected $fillable = [
            'idpreenfrio',
            'iddetalle',
            'iddetalletarima',
            'hora_entrada',
            'temperatura_entrada',
            'hora_salida',
            'temperatura_salida',
            'tiempototal',
        ];

        // Relación con Preenfriado
        public function preenfriado()
        {
            return $this->belongsTo(Preenfriado::class, 'idpreenfrio');
        }

        // Relación con DetalleRecepcion (detalle de la recepción)
        public function detalleRecepcion()
        {
            return $this->belongsTo(DetalleRecepcion::class, 'iddetalle');
        }
        public function tarimaDetarec()
        {
            return $this->belongsTo(TarimaDetarec::class, 'iddetalletarima');
        }


        
    }
