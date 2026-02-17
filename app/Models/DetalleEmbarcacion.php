<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleEmbarcacion extends Model
{
    protected $table = 'detalle_embarcaciones';

    protected $fillable = [
        'idembarcacion',
        'idconservacion',
        'iddetalletarima'
    ];

    // Relación con embarcación
    public function embarcacion()
    {
        return $this->belongsTo(Embarcacion::class, 'idembarcacion');
    }

    // Relación con conservación
    public function conservacion()
    {
        return $this->belongsTo(Conservacion::class, 'idconservacion', 'id');
    }

    public function tarimaDetarec()
    {
        return $this->belongsTo(TarimaDetarec::class, 'iddetalletarima');
    }
}
