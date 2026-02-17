<?php

namespace App\Http\Controllers;

use App\Models\DetalleRecepcion;
use App\Models\Recepcion;
use App\Models\Tarima;
use App\Models\Frutas;
use App\Models\Variedad;
use App\Models\Presentaciones;
use Illuminate\Http\Request;

class DetalleRecepcionController extends Controller
{
   public function index()
    {
        $detalles = DetalleRecepcion::with(['recepcion', 'fruta'])
                    ->orderBy('created_at', 'desc') // Ordena de más reciente a más antiguo
                    ->get();

        return view('detalle.mostrar', compact('detalles'));
    }



    public function create($idrecepcion)
    {
        $recepcion = Recepcion::findOrFail($idrecepcion);
        $frutas = Frutas::all();
        $variedades = Variedad::all();
        $presentaciones = Presentaciones::all();
        $tarimas = Tarima::where('idrecepcion', $idrecepcion)
                     ->where('estatus', '!=', 'completa')
                     ->get();
        return view('detalle.crear', [
            'idrecepcion' => $recepcion->id,
            'folio'       => $recepcion->folio,
            'frutas'      => $frutas,
            'variedades'  => $variedades,
            'presentaciones' => $presentaciones,
            'tarimas' => $tarimas,
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'idrecepcion'        => 'required|exists:recepcion,id',
            'folio'              => 'required|string|max:255',
            'hora'               => 'required|array',
            'temperatura'        => 'required|array',
            'tipo_temperatura'   => 'required|array',
            'presentacion'       => 'required|array', 
            'idfruta'            => 'required|array',
            'variedad'           => 'required|array',
            'cantidad'           => 'required|array',
            'idtarima'           => 'required|array',
        ]);
            
        // Guardado normal
        for ($i = 0; $i < count($request->hora); $i++) {
            DetalleRecepcion::create([
                'idrecepcion'        => $request->idrecepcion,
                'folio'              => $request->folio,
                'hora'               => $request->hora[$i],
                'temperatura'        => $request->temperatura[$i],
                'tipo_temperatura'   => $request->tipo_temperatura[$i],
                'presentacion'       => $request->presentacion[$i],
                'idfruta'            => $request->idfruta[$i],
                'variedad'           => $request->variedad[$i],
                'cantidad'           => $request->cantidad[$i],
                'pendientes'         => $request->cantidad[$i],
                'idtarima'         => $request->idtarima[$i],
            ]);
        }
        foreach ($request->cantidad as $i => $cantidad) {
            $tarima = Tarima::find($request->idtarima[$i]);

            $tarima->cantidad_usada += $cantidad;

            if ($tarima->cantidad_usada >= $tarima->capacidad) {
                $tarima->estatus = 'Completa';
            } else {
                $tarima->estatus = 'Ocupada';
            }

            $tarima->save();
        }
        // Actualizar el estatus de la recepción a 'CON DETALLE'
        $recepcion = Recepcion::find($request->idrecepcion);
        if ($recepcion) {
            $recepcion->estatus = 'CON DETALLE';
            $recepcion->save();
        }
        return redirect()->route('detallerecepcion.mostrar')->with('success', 'Detalles guardados exitosamente.');
    }

   
    public function edit(DetalleRecepcion $detallerecepcion)
    {
        $frutas = Frutas::all();
        $presentaciones = Presentaciones::all();
        $variedades = Variedad::all(); // si las usas

        return view('detalle.editar', compact('detallerecepcion', 'frutas', 'presentaciones', 'variedades'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DetalleRecepcion $detallerecepcion)
    {
        $data = $request->validate([
            'idrecepcion'       => 'required|integer|exists:recepcion,id',
            'folio'             => 'required|string|max:255',
            'hora'              => 'required|string',
            'temperatura'       => 'required|string',
            'tipo_temperatura'  => 'required|string',
            'presentacion'      => 'required|string',
            'idfruta'           => 'required|integer|exists:frutas,id',
            'variedad'          => 'required|string',
            'cantidad'          => 'required|numeric|min:0',
        
        ]);
        // Actualiza pendientes según la nueva cantidad
        $data['pendientes'] = $data['cantidad'];

        $detallerecepcion->update($data);
        return redirect()->route('detallerecepcion.mostrar')->with('success', 'Detalle actualizado exitosamente.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DetalleRecepcion $detallerecepcion)
    {
        $detallerecepcion->delete();
        return redirect()->route('detallerecepcion.mostrar')->with('success', 'Detalle eliminado.');
    }
}
