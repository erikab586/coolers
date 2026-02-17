<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recepcion extends Model
{
     protected $table = 'recepcion';

    protected $fillable = [
        'datosclave',
        'area',
        'revision',
        'fechaemision',
        'imagen',
        'folio',
        'estatuseliminar',
        'estatus', //estatus para eliminar 
        'idusuario',
        'idcontrato',
        'observaciones',
        'firma_responsable1',
        'nombre_responsable1',
        'firma_responsable2',
        'nombre_responsable2',
        'nota_firmas',
    ];

    // Relaciones

    public function users()
    {
        return $this->belongsTo(User::class, 'idusuario');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idusuario');
    }

    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'idcontrato');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleRecepcion::class, 'idrecepcion');
    }
}
