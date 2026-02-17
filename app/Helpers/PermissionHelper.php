<?php

if (!function_exists('hasPermission')) {
    /**
     * Verifica si el usuario autenticado tiene un permiso específico
     */
    function hasPermission($permission)
    {
        if (!auth()->check()) {
            return false;
        }

        return auth()->user()->hasPermission($permission);
    }
}

if (!function_exists('hasAnyPermission')) {
    /**
     * Verifica si el usuario autenticado tiene alguno de los permisos especificados
     */
    function hasAnyPermission($permissions)
    {
        if (!auth()->check()) {
            return false;
        }

        return auth()->user()->hasAnyPermission($permissions);
    }
}

if (!function_exists('hasAllPermissions')) {
    /**
     * Verifica si el usuario autenticado tiene todos los permisos especificados
     */
    function hasAllPermissions($permissions)
    {
        if (!auth()->check()) {
            return false;
        }

        return auth()->user()->hasAllPermissions($permissions);
    }
}
