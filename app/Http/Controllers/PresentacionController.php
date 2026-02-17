<?php

namespace App\Http\Controllers;

use App\Models\Presentacion;
use Illuminate\Http\Request;

class PresentacionController extends Controller
{
    public function index()
    {
        $presentaciones=Presentacion::where('estatus', 'activo')->orderBy('created_at', 'desc')->get();
        return view('presentacion.mostrar', compact('presentaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('presentacion.crear');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            'nombrepresentacion' => 'required|string|max:255',
            'descripcionpresentacion' => 'nullable|string',
        ]);

        Presentacion::create($request->all());
        return redirect()->route('presentacion.mostrar')->with('success', 'Presentación guardada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Presentacion::findOrFail($id);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Presentacion $presentacion)
    {
        return view('presentacion.editar', compact('presentacion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $presentacion = Presentacion::findOrFail($id);
        $presentacion->update($request->all());

         return redirect()->route('presentacion.mostrar')->with('success', 'Presentación editada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Presentacion $presentacion)
    {
        $presentacion->estatus = 'inactivo';
        $presentacion->save();
        return redirect()->route('presentacion.mostrar')->with('success', 'Presentación eliminada');
    }
}

