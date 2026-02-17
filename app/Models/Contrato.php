<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    protected $table = 'contrato';
    protected $fillable = [
        'tipocliente',
        'tipocontrato',
        'idcomercializadora',
        'idusuario',
        'idcooler',
        'imagen',
        'estatus',
        'fechacontrato'
    ];

    public function detallecontrato()
    {
        return $this->hasMany(DetalleContrato::class, 'idcontrato');
    }

    public function cooler()
    {
        return $this->belongsTo(Cooler::class, 'idcooler');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'idusuario');
    }
    public function comercializadora()
    {
        return $this->belongsTo(Comercializadora::class, 'idcomercializadora');
    }
    public function recepcion()
    {
        return $this->hasMany(Recepcion::class, 'idrecepcion');
    }
    
}

