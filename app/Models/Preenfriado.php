<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Preenfriado extends Model
{

    protected $table = 'preenfriado'; // Por si Laravel no lo infiere bien

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

    // Relaciones (opcional, si quieres acceder a los modelos relacionados)
    public function camara()
    {
        return $this->belongsTo(Camara::class, 'idcamara');
    }

    public function tarima()
    {
        return $this->belongsTo(Tarima::class, 'idtarima', 'id');
    }

    // Relación con DetallePreenfriado
    public function detallesPreenfriado()
    {
        return $this->hasMany(DetallePreenfriado::class, 'idpreenfrio');
    }

}
