<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioCooler extends Model
{
    use HasFactory;

    protected $table = 'usuario_cooler';

    protected $fillable = [
        'idusuario',
        'idcooler',
        'estatus',
    ];

    // Relación con User
    public function usuario()
    {
        return $this->belongsTo(User::class, 'idusuario');
    }

    // Relación con Cooler
    public function cooler()
    {
        return $this->belongsTo(Cooler::class, 'idcooler');
    }
}
