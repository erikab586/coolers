<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCruceAnden extends Model
{
    use HasFactory;

    protected $table = 'detalle_cruce_anden';

    protected $fillable = [
        'idcruce_anden',
        'iddetalle',
        'iddetalletarima',
        'hora_entrada',
        'temperatura_entrada',
        'hora_salida',
        'temperatura_salida',
        'tiempototal',
    ];

    // Relación con CruceAnden
    public function cruceAnden()
    {
        return $this->belongsTo(CruceAnden::class, 'idcruce_anden');
    }

    // Relación con DetalleRecepcion
    public function detalleRecepcion()
    {
        return $this->belongsTo(DetalleRecepcion::class, 'iddetalle');
    }

    public function tarimaDetarec()
    {
        return $this->belongsTo(TarimaDetarec::class, 'iddetalletarima');
    }
}
