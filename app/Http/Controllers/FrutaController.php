<?php

namespace App\Http\Controllers;

use App\Models\Fruta;
use Illuminate\Http\Request;

class FrutaController extends Controller
{
     public function index()
    {
        $frutas = Fruta::where('estatus', 'activo')
                   ->orderBy('created_at', 'desc')
                   ->get();
        return view('fruta.mostrar', compact('frutas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('fruta.crear');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombrefruta' => 'required|string|max:255',
            'imgfruta' => 'required|file|mimes:svg,png,jpg,jpeg',
        ],
        [
            'nombrefruta.required' => 'El campo Fruta es obligatorio.',
            'imgfruta.required'    => 'El campo Imagen es obligatorio.',
            'imgfruta.mimes'       => 'Solo se permiten archivos .svg, .png, .jpg, .jpeg',
        ]);

        // Procesar imagen SVG
        if ($request->hasFile('imgfruta')) {
            $archivo = $request->file('imgfruta');
            $nombreArchivo = time() . '_' . uniqid() . '.' . $archivo->getClientOriginalExtension();
            $archivo->move(public_path('imagenes/frutas'), $nombreArchivo); // carpeta public/imagenes/frutas
        }

        // Guardar en base de datos
        Fruta::create([
            'nombrefruta' => $request->nombrefruta,
            'imgfruta' => 'imagenes/frutas/' . $nombreArchivo, // guarda la ruta relativa
            'estatus' => 'activo'
        ]);

        return redirect()->route('fruta.mostrar')->with('success', 'Fruta creada correctamente.');
    }


    /**
     * Display the specified resource.
     */
     public function show(Fruta $fruta)
    {
        //return view('frutas.show', compact('fruta'));
    }

    public function edit(Fruta $fruta)
    {
        return view('fruta.editar', compact('fruta'));
    }

    public function update(Request $request, Fruta $fruta)
    {
        $request->validate([
            'nombrefruta' => 'required|string|max:255',
            'imgfruta' => 'nullable|file|mimes:svg,png,jpg,jpeg|max:2048', // max 2MB
        ], [
            'nombrefruta.required' => 'El campo Fruta es obligatorio.',
            'imgfruta.mimes'       => 'Solo se permiten archivos .svg, .png, .jpg, .jpeg',
        ]);


        $data = ['nombrefruta' => $request->nombrefruta];

        // Si el usuario sube una nueva imagen
        if ($request->hasFile('imgfruta')) {
            $archivo = $request->file('imgfruta');
            $nombreArchivo = time() . '_' . uniqid() . '.' . $archivo->getClientOriginalExtension();
            $archivo->move(public_path('imagenes/frutas'), $nombreArchivo);
            $data['imgfruta'] = 'imagenes/frutas/' . $nombreArchivo;
        }

        $fruta->update($data);

        return redirect()->route('fruta.mostrar')->with('success', 'Fruta actualizada con éxito.');
        //return $request;
    }


    public function destroy(Fruta $fruta)
    {
        $fruta->estatus = 'inactivo';
        $fruta->save();
        return redirect()->route('fruta.mostrar')->with('success', 'Fruta eliminada.');
    }
}
