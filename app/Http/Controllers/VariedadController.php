<?php

namespace App\Http\Controllers;

use App\Models\Variedad;
use Illuminate\Http\Request;

class VariedadController extends Controller
{
    public function index()
    {
        $variedades = Variedad::where('estatus', 'activo')->orderBy('created_at', 'desc')->get();
        return view('variedades.mostrar', compact('variedades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('variedades.crear');
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'tipofruta' => 'required|string|max:255',
        ],
        [
            'tipofruta'=> 'El campo Variedad es obligatorio.'
        ]);

        Variedad::create([
            'tipofruta' => $request->tipofruta,
            'estatus' => 'activo',
        ]);
        return redirect()->route('variedad.mostrar')->with('success', 'Variedad creada correctamente.');
    }

    public function edit(Variedad $variedad)
    {
        return view('variedades.editar', compact('variedad'));
    }

  
    public function update(Request $request, Variedad $variedad)
    {
        $request->validate([
            'tipofruta' => 'required|string|max:255',
        ]);

        $variedad->update($request->all());
        return redirect()->route('variedad.mostrar')->with('success', 'Variedad actualizada con éxito.');
    }

    public function destroy(Variedad $variedad)
    {
        $variedad->estatus = 'inactivo';
        $variedad->save();
         return redirect()->route('variedad.mostrar')->with('success', 'Variedad eliminada.');
    }

}
