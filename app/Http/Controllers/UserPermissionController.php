<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;

class UserPermissionController extends Controller
{
    /**
     * Muestra el formulario para asignar permisos directos a un usuario
     */
    public function edit($userId)
    {
        $user = User::with(['permissions', 'rol.permissions'])->findOrFail($userId);
        $allPermissions = Permission::orderBy('module')->orderBy('name')->get();
        
        // Agrupar permisos por módulo
        $permissionsByModule = $allPermissions->groupBy('module');

        // Obtener permisos del rol para mostrarlos como referencia
        $rolePermissions = $user->rol ? $user->rol->permissions->pluck('id')->toArray() : [];
        
        // Obtener permisos directos del usuario
        $userDirectPermissions = $user->permissions->pluck('id')->toArray();

        return view('usuario.permisos', compact('user', 'permissionsByModule', 'rolePermissions', 'userDirectPermissions'));
    }

    /**
     * Actualiza los permisos directos de un usuario
     */
    public function update(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        // Sincronizar permisos directos del usuario
        $user->permissions()->sync($request->permissions ?? []);

        return redirect()->route('usuario.mostrar')
                        ->with('success', 'Permisos del usuario actualizados correctamente');
    }

    /**
     * Asigna todos los permisos a un usuario
     */
    public function assignAll($userId)
    {
        $user = User::findOrFail($userId);
        $allPermissions = Permission::all();
        
        $user->permissions()->sync($allPermissions->pluck('id'));

        return redirect()->route('usuario.mostrar')
                        ->with('success', 'Todos los permisos asignados al usuario: ' . $user->name);
    }

    /**
     * Remueve todos los permisos directos de un usuario
     */
    public function removeAll($userId)
    {
        $user = User::findOrFail($userId);
        $user->permissions()->detach();

        return redirect()->route('usuario.mostrar')
                        ->with('success', 'Todos los permisos directos removidos del usuario: ' . $user->name);
    }
}
