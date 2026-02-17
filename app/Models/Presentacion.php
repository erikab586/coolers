<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presentacion extends Model
{
    protected $table = 'presentacion';
    protected $fillable = ['nombrepresentacion', 'descripcionpresentacion','estatus'];
}
