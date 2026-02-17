<?php

namespace App\Http\Controllers;

use App\Models\RolUsuario;
use Illuminate\Http\Request;

class RolUsuarioController extends Controller
{
    // Mostrar todos los registros
    public function index()
    {
        $roles= RolUsuario::where('estatus', 'activo')
                            ->orderBy('created_at', 'desc')->get();
        return view('roles.mostrar', compact('roles'));
    }
 
    // Crear nuevo tipopallet
    public function create()
    {
        return view('roles.crear');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombrerol' => 'required|string|max:100'
        ],
        [
            'nombrerol'=> 'Campo Rol de Usuario obligatorio.',
        ],);

        $nuevo = RolUsuario::create([
            'nombrerol' => $request->nombrerol,
        ]);

        return  redirect()->route('rolusuario.mostrar')->with('success', 'Rol de Usuario creada correctamente.');
    }

    // Mostrar un solo registro
    public function show(RolUsuario $rol)
    {
        $tipo = RolUsuario::findOrFail($id);
        return response()->json($tipo);
    }

    public function edit(RolUsuario $rol)
    {
        return view('roles.editar', compact('rol'));
    }
    // Actualizar un registro
    public function update(Request $request, RolUsuario $rol)
    {
        $request->validate([
            'nombrerol' => 'required|string|max:100'
        ]);

        $rol->update([
            'nombrerol' => $request->nombrerol,
        ]);

        return redirect()->route('rolusuario.mostrar')->with('success', 'Rol Usuario actualizada correctamente.');
    }


    // Eliminar un registro
    public function destroy(RolUsuario $rol)
    {
        $rol->estatus = 'inactivo';
        $rol->save();
        return redirect()->route('rolusuario.mostrar')->with('success', 'Rol Usuario eliminada correctamente.');
    }

}
