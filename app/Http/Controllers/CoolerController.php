<?php

namespace App\Http\Controllers;

use App\Models\Cooler;
use Illuminate\Http\Request;

class CoolerController extends Controller
{
     // Mostrar vista principal de los coolers
    public function mostrar()
    {
        $user = auth()->user();
        
        // Consulta base
        $coolersQuery = Cooler::where('estatus', 'activo')->orderBy('created_at', 'desc');

        // Filtrar según el rol del usuario
        if ($user->rol && $user->rol->nombrerol === 'Administrador') {
            // Administrador ve todos los coolers
            $coolers = $coolersQuery->get();
            
        } elseif ($user->rol && in_array($user->rol->nombrerol, ['Supervisor', 'Operativo'])) {
            // Supervisor y Operativo ven solo los coolers asignados
            $coolerIds = $user->coolers()->pluck('cooler.id');
            
            if ($coolerIds->isEmpty()) {
                // Si no tiene coolers asignados, mostrar mensaje
                $coolers = collect();
            } else {
                $coolers = $coolersQuery->whereIn('cooler.id', $coolerIds)->get();
            }
            
        } else {
            // Si no tiene rol o rol no reconocido, no mostrar nada
            $coolers = collect();
        }

        return view('cooler.mostrar', compact('coolers'));
    }

    // Mostrar formulario de creación
    public function crear()
    {
        return view('cooler.crear');
    }

    // Guardar nuevo cooler
    public function registrarCooler(Request $request)
    {
        $request->validate([
            'nombrecooler' => 'required|string|max:150',
            'codigoidentificador' => 'required|string|max:10|unique:cooler',
            'ubicacion' => 'required|string|max:150',
        ],
        [
            'nombrecooler' => 'El campo Nombre Cooler es obligatorio.',
            'codigoidentificador' => 'El campo codigo identificador es obligatorio y único.',
            'ubicacion' => ' El campo Ubicación es obligatorio.',
        ]);

        $cooler = Cooler::create([
            'nombrecooler' => $request->nombrecooler,
            'codigoidentificador' => $request->codigoidentificador,
            'ubicacion' => $request->ubicacion,
        ]);

        return redirect()->route('cooler.mostrar')->with('success', 'Cooler registrado');
    }

    // Mostrar formulario de edición
    public function formularioEditar($id)
    {
        $cooler = Cooler::findOrFail($id);
        return view('cooler.editar', compact('cooler'));
    }

    // Guardar cambios del cooler
    public function editar(Request $request, $id)
    {
        $cooler = Cooler::findOrFail($id);

        $request->validate([
            'nombrecooler' => 'required|string|max:150',
            'codigoidentificador' => 'required|string|max:10|unique:coolers,codigoidentificador,' . $id,
            'ubicacion' => 'required|string|max:150',
        ]);

        $cooler->update($request->all());

        //return response()->json(['success' => true, 'cooler' => $cooler]);
        return redirect()->route('cooler.mostrar')->with('success', 'Cooler registrado');
    }

    // Exportar lista a Excel
    public function exportar()
    {
        return Excel::download(new CoolersExport, 'coolers.xlsx');
    }

    // Buscar por ID
    public function buscarId($id)
    {
        $cooler = Cooler::findOrFail($id);
        return view('coolers.ver', compact('cooler'));
    }

    // Buscar por nombre (búsqueda AJAX o similar)
    public function buscarNomb(Request $request)
    {
        $nombre = $request->nombre;
        $coolers = Cooler::where('nombrecooler', 'like', '%' . $nombre . '%')->get();
        return response()->json($coolers);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cooler $cooler)
    {
        return view('cooler.editar', compact('cooler'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cooler = Cooler::findOrFail($id);
        $cooler->update($request->all());

         return redirect()->route('cooler.mostrar')->with('success', 'Cooler editado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cooler $cooler)
    {
        $cooler->estatus = 'inactivo';
        $cooler->save();
        return redirect()->route('cooler.mostrar')->with('success', 'Cooler eliminado');
    }
}
