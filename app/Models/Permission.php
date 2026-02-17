<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'module'
    ];

    /**
     * Relación con roles
     */
    public function roles()
    {
        return $this->belongsToMany(RolUsuario::class, 'permission_rol', 'permission_id', 'rol_usuario_id')
                    ->withTimestamps();
    }
}
