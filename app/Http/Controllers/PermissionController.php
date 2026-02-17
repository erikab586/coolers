<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\RolUsuario;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Muestra la vista de gestión de permisos por rol
     */
    public function index()
    {
        $roles = RolUsuario::with('permissions')->where('estatus', 'activo')->get();
        $permissions = Permission::orderBy('module')->orderBy('name')->get();
        
        // Agrupar permisos por módulo
        $permissionsByModule = $permissions->groupBy('module');

        return view('permisos.index', compact('roles', 'permissionsByModule'));
    }

    /**
     * Muestra el formulario para asignar permisos a un rol específico
     */
    public function edit($rolId)
    {
        $rol = RolUsuario::with('permissions')->findOrFail($rolId);
        $permissions = Permission::orderBy('module')->orderBy('name')->get();
        
        // Agrupar permisos por módulo
        $permissionsByModule = $permissions->groupBy('module');

        return view('permisos.editar', compact('rol', 'permissionsByModule'));
    }

    /**
     * Actualiza los permisos de un rol
     */
    public function update(Request $request, $rolId)
    {
        $rol = RolUsuario::findOrFail($rolId);

        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        // Sincronizar permisos
        $rol->permissions()->sync($request->permissions ?? []);

        return redirect()->route('permisos.index')
                        ->with('success', 'Permisos actualizados correctamente para el rol: ' . $rol->nombrerol);
    }

    /**
     * Asigna todos los permisos a un rol (útil para administradores)
     */
    public function assignAll($rolId)
    {
        $rol = RolUsuario::findOrFail($rolId);
        $allPermissions = Permission::all();
        
        $rol->permissions()->sync($allPermissions->pluck('id'));

        return redirect()->route('permisos.index')
                        ->with('success', 'Todos los permisos asignados al rol: ' . $rol->nombrerol);
    }

    /**
     * Remueve todos los permisos de un rol
     */
    public function removeAll($rolId)
    {
        $rol = RolUsuario::findOrFail($rolId);
        $rol->permissions()->detach();

        return redirect()->route('permisos.index')
                        ->with('success', 'Todos los permisos removidos del rol: ' . $rol->nombrerol);
    }
}
