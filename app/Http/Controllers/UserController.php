<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RolUsuario;
use App\Models\Cooler;
use App\Models\UsuarioCooler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function mostrarLogin() {
        return view('usuario.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $rol = Auth::user()->idrol; // Asumiendo que `RolUsuario` tiene un campo `nombre`
            Auth::user()->update([
                    'fechaconexion' => now(),
                ]);
            switch ($rol) {
                case '1':
                    return redirect()->route('dashboard');
                case '3':
                    return redirect()->route('dashboard');
                default:
                    return redirect()->route('dashboard');
            }
        }

        return back()->with(['email' => 'Credenciales incorrectas'])->onlyInput('email');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
         $request->session()->regenerate();
        // $request->session()->regenerateToken();
        return redirect()->route('usuario.login');
    }

    public function index()
    {
        $user = auth()->user();
        $query = User::with('rol', 'coolers')->where('estatus', 'activo');

        // Filtrar usuarios según el rol del usuario autenticado
        if ($user->rol->nombrerol == 'Administrador') {
            // Mostrar todos los usuarios
        } elseif ($user->rol->nombrerol == 'Supervisor' || $user->rol->nombrerol == 'Operativo') {
            // Mostrar solo usuarios que tienen coolers asignados en común
            $coolerIds = $user->coolers()->pluck('cooler.id');
            $query->whereHas('coolers', function($q) use ($coolerIds) {
                $q->whereIn('cooler.id', $coolerIds);
            });
        }

        $usuarios = $query->get();
        return view('usuario.mostrar', compact('usuarios'));
    }

    public function create()
    {
        $user = auth()->user();
        $roles = RolUsuario::all();
        
        // Filtrar coolers según el rol del usuario autenticado
        if ($user->rol->nombrerol == 'Administrador') {
            $coolers = Cooler::where('estatus', 'activo')->get();
        } elseif ($user->rol->nombrerol == 'Supervisor' || $user->rol->nombrerol == 'Operativo') {
            // Mostrar solo coolers asignados al usuario autenticado
            $coolers = $user->coolers()->where('cooler.estatus', 'activo')->get();
        } else {
            $coolers = collect(); // Colección vacía para otros roles
        }
        
        return view('usuario.crear', compact('roles', 'coolers'));
    }

    public function store(Request $request)
    {
        // Validación condicional
        $rules = [
            'name'      => 'required|string|max:100',
            'apellidos' => 'required|string|max:150',
            'email'     => 'required|email|unique:users',
            'telefono'  => 'nullable|string|max:15',
            'password'  => 'required|string|min:6|confirmed',
            'idrol'     => 'required|exists:rol_usuario,id',
        ];

        // Si NO es administrador general (id = 1), obligamos a elegir coolers
        if ($request->idrol != 1) {
            $rules['idcooler'] = 'required|array|min:1';
            $rules['idcooler.*'] = 'exists:cooler,id';
        }

        $validated = $request->validate($rules);

        // Crear el usuario
        $user = User::create([
            'name'      => $validated['name'],
            'apellidos' => $validated['apellidos'],
            'email'     => $validated['email'],
            'telefono'  => $validated['telefono'] ?? null,
            'password'  => Hash::make($validated['password']),
            'idrol'     => $validated['idrol'],
            'estatus'   => 'activo',
        ]);

        // Asignar coolers en tabla pivote
        if ($validated['idrol'] == 1) {
            // Administrador General: se le asignan todos los coolers activos
            $allCoolers = Cooler::where('estatus', 'activo')->pluck('id');
            if ($allCoolers->isNotEmpty()) {
                $pivotData = $allCoolers->mapWithKeys(function ($idcooler) {
                    return [$idcooler => ['estatus' => 'activo']];
                });
                $user->coolers()->attach($pivotData);
            }
        } elseif (!empty($validated['idcooler'])) {
            // Otros roles: solo los coolers seleccionados
            $coolers = collect($validated['idcooler'])->mapWithKeys(function ($idcooler) {
                return [$idcooler => ['estatus' => 'activo']];
            });
            $user->coolers()->attach($coolers);
        }

        return redirect()->route('usuario.mostrar')->with('success', 'Usuario creado con éxito.');
    }


    public function edit(User $usuario)
    {  
        $user = auth()->user();
        $roles = RolUsuario::all();
        
        // Filtrar coolers según el rol del usuario autenticado
        if ($user->rol->nombrerol == 'Administrador') {
            $coolers = Cooler::where('estatus', 'activo')->get();
        } elseif ($user->rol->nombrerol == 'Supervisor' || $user->rol->nombrerol == 'Operativo') {
            // Mostrar solo coolers asignados al usuario autenticado
            $coolers = $user->coolers()->where('cooler.estatus', 'activo')->get();
        } else {
            $coolers = collect(); // Colección vacía para otros roles
        }
        
        return view('usuario.editar', compact('usuario', 'roles', 'coolers'));
    }

    public function update(Request $request, User $usuario)
    {
        
       $rules = [
            'name'      => 'required|string|max:100',
            'apellidos' => 'required|string|max:150',
            'email'     => 'required|email|unique:users,email,' . $usuario->id,
            'telefono'  => 'nullable|string|max:15',
            'idrol'     => 'required|exists:rol_usuario,id',
        ];

        // Si NO es Administrador General, obligamos a elegir al menos un cooler
        if ($request->idrol != 1) {
            $rules['idcooler'] = 'required|array|min:1';
            $rules['idcooler.*'] = 'exists:cooler,id';
        }

        $validated = $request->validate($rules);

        $usuario->update([
            'name'      => $validated['name'],
            'apellidos' => $validated['apellidos'],
            'email'     => $validated['email'],
            'telefono'  => $validated['telefono'] ?? null,
            'idrol'     => $validated['idrol'],
        ]);

        // Sincronizar coolers según el rol
        if ($validated['idrol'] == 1) {
            // Administrador General: sincronizar todos los coolers activos
            $allCoolers = Cooler::where('estatus', 'activo')->pluck('id');
            if ($allCoolers->isNotEmpty()) {
                $pivotData = $allCoolers->mapWithKeys(function ($idcooler) {
                    return [$idcooler => ['estatus' => 'activo']];
                });
                $usuario->coolers()->sync($pivotData);
            } else {
                $usuario->coolers()->detach();
            }
        } else {
            // Otros roles: solo los coolers seleccionados
            $coolers = collect($validated['idcooler'])->mapWithKeys(function ($idcooler) {
                return [$idcooler => ['estatus' => 'activo']];
            });
            $usuario->coolers()->sync($coolers);
        }

        return redirect()->route('usuario.mostrar')->with('success', 'Usuario actualizado.');
    }


    public function destroy(User $usuario)
    {
        $usuario->estatus = 'inactivo';
        $usuario->save();
        return redirect()->route('usuario.mostrar')->with('success', 'Usuario eliminado.');
    }
  


}
