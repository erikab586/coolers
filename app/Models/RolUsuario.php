<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolUsuario extends Model
{
    protected $table = 'rol_usuario';
    protected $fillable = ['nombrerol', 'estatus'];

    public function users()
    {
        return $this->hasMany(User::class, 'id','idrol');
    }

    /**
     * Relación con permisos
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_rol', 'rol_usuario_id', 'permission_id')
                    ->withTimestamps();
    }

    /**
     * Verifica si el rol tiene un permiso específico
     */
    public function hasPermission($permissionName)
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }
}
