<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fruta extends Model
{
    protected $table = 'fruta';
    protected $fillable = ['nombrefruta', 'imgfruta', 'estatus'];
    public function detallecontrato()
    {
        return $this->hasMany(DetalleContrato::class, 'iddetalle');
    }
}
