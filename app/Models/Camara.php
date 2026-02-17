<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Camara extends Model
{
    protected $table = 'camara';
    protected $fillable = ['idcooler', 'codigo', 'capacidadminima', 'capacidadmaxima', 'tipo', 'estatus'];

     public function cooler()
    {
        return $this->belongsTo(Cooler::class, 'idcooler');
    }
}