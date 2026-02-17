<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarimaDetarec extends Model
{
    protected $table = 'tarima_detarec';

    protected $fillable = [
        'iddetalle',
        'idtarima',
        'cantidad_asignada',
        'cantidadcarga',
        'idtipopallet',
        'codigo',
        'estatus',
        'folio',
        'idfruta',
        'idpresentacion',
        'idvariedad',
        'idcomercializadora',
    ];

    // Relación con la tarima
    public function tarima()
    {
        return $this->belongsTo(Tarima::class, 'idtarima', 'id');
    }

    // Relación con detalles de recepción
    public function detalle()
    {
        return $this->belongsTo(DetalleRecepcion::class, 'iddetalle');
    }

    // Relación con tipo de pallet
    public function tipopallet()
    {
        return $this->belongsTo(TipoPallet::class, 'idtipopallet');
    }

    // Relación con fruta
    public function fruta()
    {
        return $this->belongsTo(Fruta::class, 'idfruta');
    }

    // Relación con presentación
    public function presentacion()
    {
        return $this->belongsTo(Presentacion::class, 'idpresentacion');
    }

    // Relación con variedad
    public function variedad()
    {
        return $this->belongsTo(Variedad::class, 'idvariedad');
    }

    // Relación con comercializadora
    public function comercializadora()
    {
        return $this->belongsTo(Comercializadora::class, 'idcomercializadora');
    }
}
