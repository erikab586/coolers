<?php

namespace App\Http\Controllers;

use App\Models\TipoPallet;
use Illuminate\Http\Request;

class TipoPalletController extends Controller
{
    // Mostrar todos los registros
    public function index()
    {
        $tipospallets = TipoPallet::where('estatus', 'activo')->orderBy('created_at', 'desc')->get();
        return view('tipopallets.mostrar', compact('tipospallets'));
    }
 
    // Crear nuevo tipopallet
    public function create()
    {
        return view('tipopallets.crear');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipopallet' => 'required|string|max:100'
        ],
        [
            'tipopallet'=> 'Campo Tipo Pallets obligatorio.',
        ],);

        $nuevo = TipoPallet::create([
            'tipopallet' => $request->tipopallet,
        ]);

        return  redirect()->route('tipopallets.mostrar')->with('success', 'Tipo Pallet creada correctamente.');
    }

    // Mostrar un solo registro
    public function show(TipoPallet $tipopallet)
    {
        $tipo = TipoPallet::findOrFail($id);
        return response()->json($tipo);
    }

    public function edit(TipoPallet $tipopallet)
    {
        return view('tipopallets.editar', compact('tipopallet'));
    }
    // Actualizar un registro
    public function update(Request $request, TipoPallet $tipopallet)
    {
        $request->validate([
            'tipopallet' => 'required|string|max:100'
        ]);

        $tipopallet->update([
            'tipopallet' => $request->tipopallet,
        ]);

        return redirect()->route('tipopallets.mostrar')->with('success', 'Tipo Pallet actualizada correctamente.');
    }


    // Eliminar un registro
    public function destroy(TipoPallet $tipopallet)
    {
        $tipopallet->estatus = 'inactivo';
        $tipopallet->save();
        return redirect()->route('tipopallets.mostrar')->with('success', 'Tipo Pallet eliminada correctamente.');
    }

}
