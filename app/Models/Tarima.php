<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarima extends Model
{
    protected $table = 'tarimas';

    protected $fillable = [
        'codigo',
        'cantidad',
        'capacidad',
        'estatus',
        'ubicacion',
        'estatuseliminar',
        'observaciones'
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'capacidad' => 'integer',
        'estatus' => 'string',
        'ubicacion' => 'string'
    ];

    // SOLO ESTA RELACIÓN ES NECESARIA
    public function tarimaDetarec()
    {
        return $this->hasMany(TarimaDetarec::class, 'idtarima', 'id');
    }

    // QUITAMOS TODO LO RELACIONADO CON PREENFRIADO / CONSERVACIÓN / ANDÉN

    public function getFolioRecepcion()
    {
        $rel = $this->tarimaDetarec()->with('detalle.recepcion')->first();

        return $rel->detalle->recepcion->folio ?? null;
    }

    // Corrección: usar el campo REAL en la BD
    public function cantidadActual()
    {
        return $this->tarimaDetarec()->sum('cantidad_asignada');
    }

    public function capacidadDisponible()
    {
        return $this->capacidad - $this->cantidadActual();
    }
 

}
