<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleContrato extends Model
{
    protected $table = 'detalle_contrato';
    protected $fillable = [
        'idcontrato',
        'idfruta',
        'idvariedad',
        'idpresentacion',
        'tiposervicio',
        'monto',
        'moneda',
    ];

    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'idcontrato');
    }
    public function fruta()
    {
        return $this->belongsTo(Fruta::class, 'idfruta');
    }
    public function variedad()
    {
        return $this->belongsTo(Variedad::class, 'idvariedad');
    }
    public function presentacion()
    {
        return $this->belongsTo(Presentacion::class, 'idpresentacion');
    }
}
