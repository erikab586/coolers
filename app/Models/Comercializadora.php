<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comercializadora extends Model
{
    protected $table = 'comercializadora';
    protected $fillable = [
        'rfc',
        'nombrerepresentante',
        'numtelefono',
        'correo',
        'banco',
        'clave',
        'abreviatura',
        'imgcomercializadora',
        'nombrecomercializadora',
        'estatus'
    ];

     public function contrato()
    {
        return $this->hasMany(Contrato::class);
    }
}
