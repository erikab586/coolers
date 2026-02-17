<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variedad extends Model
{
    protected $table = 'variedad';
    protected $fillable = ['tipofruta', 'estatus'];
    public function detallecontrato()
    {
        return $this->hasMany(DetalleContrato::class, 'iddetalle');
    }

    
}
