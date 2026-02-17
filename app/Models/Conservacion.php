<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conservacion extends Model
{
    protected $table = 'conservacion';

    protected $fillable = [
        'idcamara',
        'idtarima',
        'observaciones',
        'nombre_responsable1',
        'nombre_responsable2',
        'firma_responsable1',
        'firma_responsable2',
        'nota_firmas',
    ];

    public function camara()
    {
        return $this->belongsTo(Camara::class, 'idcamara');
    }

    public function tarima()
    {
        return $this->belongsTo(Tarima::class, 'idtarima', 'id');
    }


    public function detallesConservacion()
    {
        return $this->hasMany(DetalleConservacion::class, 'idconservacion');
    }
}
