<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CruceAnden extends Model
{
    use HasFactory;

    protected $table = 'cruce_anden';

    protected $fillable = [
        'idtarima',
        'idcamara',
        'observaciones',
        'nombre_responsable1',
        'nombre_responsable2',
        'firma_responsable1',
        'firma_responsable2',
        'nota_firmas',
    ];

    // Relación con Tarima
    public function tarima()
    {
        return $this->belongsTo(Tarima::class, 'idtarima');
    }

    // Relación con Cámara
    public function camara()
    {
        return $this->belongsTo(Camara::class, 'idcamara');
    }

    // Relación con DetallesCruceAnden
    public function detallesCruceAnden()
    {
        return $this->hasMany(DetalleCruceAnden::class, 'idcruce_anden');
    }
}
