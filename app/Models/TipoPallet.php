<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoPallet extends Model
{
    protected $table = 'tipopallet';
    protected $fillable = ['tipopallet', 'estatus'];

    /*public function users()
    {
        return $this->hasMany(User::class);
    }*/
}
