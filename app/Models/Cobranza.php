<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cobranza extends Model
{
    protected $table = 'cobranzas';

    protected $fillable = [
        'idrecepcion',
        'iddetallerecepcion',
        'idcontrato',
        'idcomercializadora',
        'idcooler',
        'folio',
        'fruta',
        'presentacion',
        'variedad',
        'cantidad',
        'monto_preenfriado',
        'monto_conservacion',
        'monto_anden',
        'total_conservacion',
        'total_preenfriado',
        'tiempo_preenfriado',
        'tiempo_conservacion',
        'tiempo_anden',
        'moneda',
        'dia_recepcion',
        'fecha_recepcion',
        'estatus',
        // Nuevos campos para reglas de negocio
        'tipo_cambio',
        'moneda_contrato',
        'subtotal_preenfriado',
        'subtotal_conservacion',
        'subtotal_anden',
        'iva',
        'total',
        'regla_aplicada',
        'tiene_cruce_anden',
        'monto_conservacion_extra',
    ];

    protected $casts = [
        'fecha_recepcion' => 'date',
        'monto_preenfriado' => 'decimal:2',
        'monto_conservacion' => 'decimal:2',
        'monto_anden' => 'decimal:2',
        'total_conservacion' => 'decimal:2',
        'total_preenfriado' => 'decimal:2',
        'tipo_cambio' => 'decimal:4',
        'subtotal_preenfriado' => 'decimal:2',
        'subtotal_conservacion' => 'decimal:2',
        'subtotal_anden' => 'decimal:2',
        'iva' => 'decimal:2',
        'total' => 'decimal:2',
        'monto_conservacion_extra' => 'decimal:2',
        'tiene_cruce_anden' => 'boolean',
    ];

    // Relaciones
    public function recepcion()
    {
        return $this->belongsTo(Recepcion::class, 'idrecepcion');
    }

    public function detalleRecepcion()
    {
        return $this->belongsTo(DetalleRecepcion::class, 'iddetallerecepcion');
    }

    public function comercializadora()
    {
        return $this->belongsTo(Comercializadora::class, 'idcomercializadora');
    }

    public function cooler()
    {
        return $this->belongsTo(Cooler::class, 'idcooler');
    }
}
