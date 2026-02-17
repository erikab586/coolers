<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Models\Comercializadora;
use Illuminate\Http\Request;

class ComercializadoraController extends Controller
{
     public function mostrar()
    {
        $comercializadoras = Comercializadora::where('estatus', 'activo')->orderBy('id', 'desc')->get();
        return view('comercializadora.mostrar', compact('comercializadoras'));
    }

    // Vista para crear nueva comercializadora
    public function crear()
    {
        return view('comercializadora.crear');
    }

    public function registrarComercializadora(Request $request)
    {
        $request->validate([
            'rfc'                   => 'required|string|max:150',
            'nombrerepresentante'   => 'required|string|max:150',
            'numtelefono'           => 'required|string|max:15',
            'correo'                => 'required|email|max:75',
            'banco'                 => 'required|string|max:75',
            'clave'                 => 'required|string|max:75',
            'abreviatura'           => 'required|string|max:150',
            'imgcomercializadora'   => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'nombrecomercializadora'=> 'required|string|max:150',
        ]);

        // Procesar imagen
        $nombreArchivo = null;
        if ($request->hasFile('imgcomercializadora')) {
            $archivo = $request->file('imgcomercializadora');
            $nombreArchivo = time() . '_' . uniqid() . '.' . $archivo->getClientOriginalExtension();
            $archivo->move(public_path('imagenes/comercializadoras'), $nombreArchivo); // guarda en public/imagenes/comercializadoras
        }

        // Guardar en base de datos
        Comercializadora::create([
            'rfc'                   => $request->rfc,
            'nombrerepresentante'   => $request->nombrerepresentante,
            'numtelefono'           => $request->numtelefono,
            'correo'                => $request->correo,
            'banco'                 => $request->banco,
            'clave'                 => $request->clave,
            'abreviatura'           => $request->abreviatura,
            'imgcomercializadora'   => 'imagenes/comercializadoras/' . $nombreArchivo, // solo la ruta relativa
            'nombrecomercializadora'=> $request->nombrecomercializadora,
            'estatus'               => 'activo',
        ]);

        return redirect()->route('comercializadora.mostrar')->with('success', 'Comercializadora creada correctamente');
    }


    // Vista del formulario de edición
    public function formularioEditar(Comercializadora $comercializadora)
    {
        $comercializadora = Comercializadora::findOrFail($idcomercializadora);
        return view('comercializadora.editar', compact('comercializadora'));
    }

    public function update(Request $request, Comercializadora $comercializadora)
    {
        $request->validate([
            'rfc'                    => 'required|string|max:150',
            'nombrerepresentante'    => 'required|string|max:150',
            'numtelefono'            => 'required|string|max:15',
            'correo'                 => 'required|email|max:75',
            'banco'                  => 'required|string|max:75',
            'clave'                  => 'required|string|max:75',
            'abreviatura'            => 'required|string|max:150',
            'imgcomercializadora'    => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'nombrecomercializadora' => 'required|string|max:150',
        ]);

        // Preparamos los datos base
        $data = [
            'rfc'                    => $request->rfc,
            'nombrerepresentante'    => $request->nombrerepresentante,
            'numtelefono'            => $request->numtelefono,
            'correo'                 => $request->correo,
            'banco'                  => $request->banco,
            'clave'                  => $request->clave,
            'abreviatura'            => $request->abreviatura,
            'nombrecomercializadora'=> $request->nombrecomercializadora,
            'estatus'                => 'activo',
        ];

        // Si sube una nueva imagen
        if ($request->hasFile('imgcomercializadora')) {
            $archivo = $request->file('imgcomercializadora');
            $nombreArchivo = time() . '_' . uniqid() . '.' . $archivo->getClientOriginalExtension();
            $ruta = public_path('imagenes/comercializadoras');

            // Crear carpeta si no existe
            if (!file_exists($ruta)) {
                mkdir($ruta, 0755, true);
            }

            // Eliminar la imagen anterior si existe
            if ($comercializadora->imgcomercializadora) {
                $rutaAnterior = public_path($comercializadora->imgcomercializadora);
                if (file_exists($rutaAnterior)) {
                    unlink($rutaAnterior);
                }
            }

            // Guardar la nueva imagen
            $archivo->move($ruta, $nombreArchivo);

            // Actualizamos la ruta relativa
            $data['imgcomercializadora'] = 'imagenes/comercializadoras/' . $nombreArchivo;
        }

        // Actualizar en la base de datos
        $comercializadora->update($data);
        
       return redirect()->route('comercializadora.mostrar')->with('success', 'Comercializadora actualizada con éxito.');
    }



    // Buscar por ID y mostrar en vista
    public function buscarId($idcomercializadora)
    {
        $comercializadora = Comercializadora::findOrFail($idcomercializadora);
        return view('comercializadoras.ver', compact('comercializadora'));
    }

    // Buscar por nombre del representante
    public function buscarNombre($nombre)
    {
        $resultados = Comercializadora::where('nombrerepresentante', 'like', "%$nombre%")->get();
        return view('comercializadoras.resultados', compact('resultados'));
    }

    // Exportar a Excel
    public function exportar()
    {
        return Excel::download(new ComercializadorasExport, 'comercializadoras.xlsx');
    }

    public function edit(Comercializadora $comercializadora)
    {
        return view('comercializadora.editar', compact('comercializadora'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comercializadora $comercializadora)
    {
        $comercializadora->estatus = 'inactivo';
        $comercializadora->save();
        return redirect()->route('comercializadora.mostrar')->with('success', 'comercializadora eliminada');
    }
}
