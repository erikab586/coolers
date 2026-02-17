<?php

namespace App\Traits;

trait HasPermissions
{
    /**
     * Verifica si el usuario tiene un permiso específico
     * Verifica primero permisos directos del usuario, luego permisos del rol
     */
    public function hasPermission($permissionName)
    {
        // Verificar permisos directos del usuario
        if ($this->permissions()->where('name', $permissionName)->exists()) {
            return true;
        }

        // Si no tiene permiso directo, verificar permisos del rol
        if (!$this->rol) {
            return false;
        }

        return $this->rol->hasPermission($permissionName);
    }

    /**
     * Verifica si el usuario tiene alguno de los permisos especificados
     */
    public function hasAnyPermission($permissions)
    {
        if (!$this->rol) {
            return false;
        }

        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verifica si el usuario tiene todos los permisos especificados
     */
    public function hasAllPermissions($permissions)
    {
        if (!$this->rol) {
            return false;
        }

        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Obtiene todos los permisos del usuario (directos + del rol)
     */
    public function getPermissions()
    {
        $permissions = collect([]);

        // Agregar permisos directos del usuario
        $permissions = $permissions->merge($this->permissions);

        // Agregar permisos del rol
        if ($this->rol) {
            $permissions = $permissions->merge($this->rol->permissions);
        }

        // Eliminar duplicados por ID
        return $permissions->unique('id');
    }

    /**
     * Verifica si el usuario tiene un permiso directo (no del rol)
     */
    public function hasDirectPermission($permissionName)
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }

    /**
     * Verifica si el usuario tiene un permiso a través de su rol
     */
    public function hasRolePermission($permissionName)
    {
        if (!$this->rol) {
            return false;
        }

        return $this->rol->hasPermission($permissionName);
    }
}
