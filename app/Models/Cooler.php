<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cooler extends Model
{
    protected $table = 'cooler';
    protected $fillable = ['nombrecooler', 'codigoidentificador', 'ubicacion', 'estatus'];

    public function camaras()
    {
        return $this->hasMany(Camara::class, 'idcooler');
    }
   
    public function contratos()
    {
        return $this->hasMany(Contrato::class, 'idcooler');
    }
    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'usuario_cooler', 'idcooler', 'idusuario')
                    ->withPivot('estatus')
                    ->withTimestamps();
    }


}
