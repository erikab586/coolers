<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

/**
 * EJEMPLO DE CONTROLADOR CON PERMISOS
 * 
 * Este archivo muestra cómo implementar permisos en tus controladores existentes
 */
class EjemploUserController extends Controller
{
    /**
     * OPCIÓN 1: Verificar permisos en cada método
     */
    public function index()
    {
        // Verificar si el usuario tiene permiso para ver usuarios
        if (!auth()->user()->hasPermission('ver_usuarios')) {
            abort(403, 'No tienes permiso para ver usuarios');
        }

        $usuarios = User::with('rol')->where('estatus', 'activo')->paginate(10);
        return view('usuario.mostrar', compact('usuarios'));
    }

    public function create()
    {
        // Verificar permiso para crear usuarios
        if (!auth()->user()->hasPermission('crear_usuarios')) {
            abort(403, 'No tienes permiso para crear usuarios');
        }

        return view('usuario.crear');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('crear_usuarios')) {
            abort(403, 'No tienes permiso para crear usuarios');
        }

        // Tu lógica de creación aquí
        // ...
        
        return redirect()->route('usuario.mostrar')
                        ->with('success', 'Usuario creado exitosamente');
    }

    public function edit($id)
    {
        if (!auth()->user()->hasPermission('editar_usuarios')) {
            abort(403, 'No tienes permiso para editar usuarios');
        }

        $usuario = User::findOrFail($id);
        return view('usuario.editar', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->hasPermission('editar_usuarios')) {
            abort(403, 'No tienes permiso para editar usuarios');
        }

        // Tu lógica de actualización aquí
        // ...
        
        return redirect()->route('usuario.mostrar')
                        ->with('success', 'Usuario actualizado exitosamente');
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasPermission('eliminar_usuarios')) {
            abort(403, 'No tienes permiso para eliminar usuarios');
        }

        $usuario = User::findOrFail($id);
        $usuario->estatus = 'inactivo';
        $usuario->save();
        
        return redirect()->route('usuario.mostrar')
                        ->with('success', 'Usuario eliminado exitosamente');
    }

    /**
     * OPCIÓN 2: Usar middleware en el constructor
     * Esta opción es más limpia y centraliza la verificación de permisos
     */
    public function __construct()
    {
        // Aplicar middleware de permisos a métodos específicos
        $this->middleware('permission:ver_usuarios')->only(['index', 'show']);
        $this->middleware('permission:crear_usuarios')->only(['create', 'store']);
        $this->middleware('permission:editar_usuarios')->only(['edit', 'update']);
        $this->middleware('permission:eliminar_usuarios')->only(['destroy']);
    }

    /**
     * OPCIÓN 3: Verificar múltiples permisos
     */
    public function exportar()
    {
        // Verificar si tiene alguno de estos permisos
        if (!auth()->user()->hasAnyPermission(['ver_usuarios', 'exportar_reportes'])) {
            abort(403, 'No tienes permiso para exportar usuarios');
        }

        // Tu lógica de exportación aquí
        // ...
    }

    /**
     * OPCIÓN 4: Verificar todos los permisos requeridos
     */
    public function operacionCompleja()
    {
        // Verificar si tiene TODOS estos permisos
        if (!auth()->user()->hasAllPermissions(['ver_usuarios', 'editar_usuarios', 'ver_reportes'])) {
            abort(403, 'No tienes todos los permisos necesarios para esta operación');
        }

        // Tu lógica compleja aquí
        // ...
    }

    /**
     * OPCIÓN 5: Retornar respuesta personalizada en lugar de abort
     */
    public function metodoConRespuestaPersonalizada()
    {
        if (!auth()->user()->hasPermission('ver_usuarios')) {
            return redirect()->route('dashboard')
                           ->with('error', 'No tienes permiso para acceder a esta sección');
        }

        // Tu lógica aquí
        // ...
    }

    /**
     * OPCIÓN 6: Verificar permisos con lógica condicional
     */
    public function metodoConLogicaCondicional()
    {
        $usuarios = User::query();

        // Si tiene permiso para ver todos los usuarios
        if (auth()->user()->hasPermission('ver_usuarios')) {
            $usuarios = $usuarios->where('estatus', 'activo');
        } else {
            // Si no, solo ver sus propios datos
            $usuarios = $usuarios->where('id', auth()->id());
        }

        return view('usuario.mostrar', [
            'usuarios' => $usuarios->get()
        ]);
    }
}

/**
 * EJEMPLO DE USO EN RUTAS (routes/web.php)
 * 
 * // Proteger una ruta individual
 * Route::get('/usuarios', [UserController::class, 'index'])
 *     ->middleware('permission:ver_usuarios');
 * 
 * // Proteger un grupo de rutas
 * Route::middleware(['auth', 'permission:ver_usuarios'])->group(function () {
 *     Route::get('/usuarios', [UserController::class, 'index']);
 *     Route::get('/usuarios/{id}', [UserController::class, 'show']);
 * });
 * 
 * // Proteger con múltiples middlewares
 * Route::middleware(['auth', 'rol:Administrador', 'permission:crear_usuarios'])->group(function () {
 *     Route::get('/usuarios/crear', [UserController::class, 'create']);
 *     Route::post('/usuarios', [UserController::class, 'store']);
 * });
 */
