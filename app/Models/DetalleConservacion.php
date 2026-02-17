<?php 
    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class DetalleConservacion extends Model
    {
        protected $table = 'detalle_conservacion';

        protected $fillable = [
            'idconservacion',
            'iddetalle',
            'iddetalletarima',
            'hora_entrada',
            'temperatura_entrada',
            'hora_salida',
            'temperatura_salida',
            'tiempototal',
        ];

        public function conservacion()
        {
            return $this->belongsTo(Conservacion::class, 'idconservacion');
        }

        public function detalleRecepcion()
        {
            return $this->belongsTo(DetalleRecepcion::class, 'iddetalle');
        }

        public function tarimaDetarec()
        {
            return $this->belongsTo(TarimaDetarec::class, 'iddetalletarima');
        }
        public function embarcacion()
        {
            return $this->hasOne(Embarcacion::class, 'idconservacion');
        }


        
        public function detalleEmbarcacion()
        {
            return $this->hasOne(DetalleEmbarcacion::class, 'idconservacion');
        }
    }
