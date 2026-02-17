<?php

namespace App\Http\Controllers;
use App\Models\Contrato;
use App\Models\Comercializadora;
use App\Models\User;
use App\Models\Variedad;
use App\Models\Cooler;
use App\Models\Fruta;
use App\Models\DetalleContrato;
use App\Models\Presentacion;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContratoController extends Controller
{
    public function mostrar()
    {
        $user = auth()->user();
        $query = Contrato::with(['comercializadora', 'users', 'cooler'])
            ->where('estatus', 'activo');

        // Filtrar según el rol del usuario
        if ($user->rol->nombrerol == 'Administrador') {
            // Mostrar todos los contratos
        } elseif ($user->rol->nombrerol == 'Supervisor' || $user->rol->nombrerol == 'Operativo') {
            // Mostrar solo contratos de coolers asignados al usuario (activos)
            $coolerIds = $user->coolers()->where('usuario_cooler.estatus', 'activo')->pluck('cooler.id');
            $query->whereIn('idcooler', $coolerIds);
        }

        $contratos = $query->orderBy('id', 'desc')->get();

        return view('contratos.mostrar', compact('contratos'));
    }

    public function mostrarRecepcion()
    {
        $user = auth()->user();
        $query = Contrato::with(['comercializadora', 'users', 'cooler']);

        // Filtrar según el rol del usuario
        if ($user->rol->nombrerol == 'Administrador') {
            // Mostrar todos los contratos
        } elseif ($user->rol->nombrerol == 'Supervisor' || $user->rol->nombrerol == 'Operativo') {
            // Mostrar solo contratos de coolers asignados al usuario (activos)
            $coolerIds = $user->coolers()->where('usuario_cooler.estatus', 'activo')->pluck('cooler.id');
            $query->whereIn('idcooler', $coolerIds);
        }

        $contratos = $query->orderBy('id', 'desc')->get();

        return view('recepcion.mostrarRecepcion', compact('contratos'));
    }

    // Formulario de creación
    public function crear()
    {
        $user = auth()->user();
        $comercializadoras = Comercializadora::all()->where('estatus', 'activo');
        
        // Filtrar coolers según el rol del usuario
        if ($user->rol->nombrerol == 'Administrador') {
            $coolers = Cooler::where('estatus', 'activo')->get();
        } elseif ($user->rol->nombrerol == 'Supervisor' || $user->rol->nombrerol == 'Operativo') {
            // Mostrar solo coolers asignados al usuario
            $coolers = $user->coolers()->where('cooler.estatus', 'activo')->get();
        } else {
            $coolers = collect(); // Colección vacía para otros roles
        }
        
        $frutas = Fruta::where('estatus', 'activo')->get();
        $presentaciones = Presentacion::where('estatus', 'activo')->get();
        $variedades = Variedad::where('estatus', 'activo')->get();
        return view('contratos.crear', compact('comercializadoras', 'coolers', 'frutas', 'variedades', 'presentaciones'));
    }

    // Registro (retorna JSON)
    public function registrarContrato(Request $request)
    {
        // 1. Validar los campos principales
        $request->validate([
            'idcomercializadora' => 'required|exists:comercializadora,id',
            'tipocliente' => 'required',
            'tipocontrato' => 'required',
            'idcooler' => 'required|exists:cooler,id',
            'fechacontrato' => 'required|date',
            'idfruta' => 'required|array',
            'idfruta.*' => 'required|exists:fruta,id',
            'idvariedad' => 'required|array',
            'idvariedad.*' => 'required|exists:variedad,id',
            'idpresentacion' => 'required|array',
            'idpresentacion.*' => 'required|exists:presentacion,id',
            'tiposervicio' => 'required|array',
            'tiposervicio.*' => 'required|string',
            'monto' => 'required|array',
            'monto.*' => 'required|numeric|min:0',
            'moneda' => 'required|array',
            'moneda.*' => 'required|string',
        ]);

        // 2. Crear el contrato
        $contrato = Contrato::create([
            'idcomercializadora' => $request->idcomercializadora,
            'idusuario'          => $request->idusuario,
            'tipocliente'        => $request->tipocliente,
            'tipocontrato'       => $request->tipocontrato,
            'idcooler'           => $request->idcooler,
            'estatus'            => 'activo',
            'fechacontrato'      => $request->fechacontrato,
        ]);

        // 3. Crear los detalles
        foreach ($request->idfruta as $index => $idfruta) {
            DetalleContrato::create([
                'idcontrato' => $contrato->id,
                'idfruta'    => $idfruta,
                'idvariedad' => $request->idvariedad[$index],
                'idpresentacion' => $request->idpresentacion[$index],
                'tiposervicio' => $request->tiposervicio[$index],
                'monto'      => $request->monto[$index],
                'moneda'     => $request->moneda[$index],
            ]);
        }

        return redirect()->route('contrato.mostrar')->with('success', 'Contrato creado con éxito');
    }


    // Vista de edición
    public function formularioEditar($idcontrato)
    {
        $user = auth()->user();
        $contrato = Contrato::findOrFail($idcontrato);
        $comercializadoras = Comercializadora::all()->where('estatus', 'activo');
        $usuarios = User::all();
        $presentaciones = Presentacion::all();
        // Filtrar coolers según el rol del usuario
        if ($user->rol->nombrerol == 'Administrador') {
            $coolers = Cooler::where('estatus', 'activo')->get();
        } elseif ($user->rol->nombrerol == 'Supervisor' || $user->rol->nombrerol == 'Operativo') {
            // Mostrar solo coolers asignados al usuario
            $coolers = $user->coolers()->where('cooler.estatus', 'activo')->get();
        } else {
            $coolers = collect(); // Colección vacía para otros roles
        }
        
        $frutas = Fruta::where('estatus', 'activo')->get();
        $variedades = Variedad::where('estatus', 'activo')->get();
        return view('contratos.editar', compact('contrato','variedades', 'presentaciones', 'comercializadoras', 'usuarios', 'coolers', 'frutas'));
    }

    
    public function editar(Request $request, $id)
    {
        $validatedData = $request->validate([
            'idcomercializadora' => 'required|exists:comercializadora,id',
            'idusuario' => 'required|exists:users,id',
            'tipocliente' => 'required|string',
            'tipocontrato' => 'required|string',
            'idcooler' => 'required|exists:cooler,id',
            'fechacontrato' => 'required|date',
            'idfruta' => 'required|array',
            'idvariedad' => 'required|array',
            'monto' => 'required|array',
            'moneda' => 'required|array',
            'iddetalle' => 'required|array', // este campo nuevo es necesario
        ]);

        $contrato = Contrato::findOrFail($id);

        // Actualizar campos del contrato principal
        $contrato->update([
            'idcomercializadora' => $request->idcomercializadora,
            'idusuario' => $request->idusuario,
            'tipocliente' => $request->tipocliente,
            'tipocontrato' => $request->tipocontrato,
            'idcooler' => $request->idcooler,
            'estatus' => 'activo',
            'fechacontrato' => $request->fechacontrato,
        ]);

        // Datos del detalle
        $frutas = $request->idfruta;
        $variedades = $request->idvariedad;
        $montos = $request->monto;
        $monedas = $request->moneda;
        $detalleIds = $request->iddetalle ?? []; // puede que no vengan

        for ($i = 0; $i < count($frutas); $i++) {
            $detalleData = [
                'idfruta' => $frutas[$i],
                'idvariedad' => $variedades[$i],
                'idpresentacion' => $request->idpresentacion[$i],
                'tiposervicio' => $request->tiposervicio[$i],
                'monto' => $montos[$i],
                'moneda' => $monedas[$i],
            ];

            if (!empty($detalleIds[$i])) {
                $detalle = DetalleContrato::find($detalleIds[$i]);
                if ($detalle && $detalle->idcontrato == $contrato->id) {
                    $detalle->update($detalleData);
                }
            } else {
                $contrato->detalleContrato()->create($detalleData);
            }
        }


        return redirect()->route('contrato.mostrar')->with('success', 'Contrato actualizado correctamente.');
    }

    // Buscar por ID y mostrar en vista
    public function buscarId($idcontrato)
    {
        $contrato = Contrato::with(['comercializadora', 'users', 'cooler'])->findOrFail($idcontrato);
        return view('contratos.ver', compact('contrato'));
    }

    // Exportar a Excel
    public function exportar()
    {
        return Excel::download(new ContratosExport, 'contratos.xlsx');
    }

    // Generar factura en PDF
    public function generarFactura(Request $request)
    {
        $contrato = Contrato::findOrFail($request->idcontrato);
        $pdf = PDF::loadView('pdf.factura', compact('contrato'));
        return $pdf->download("factura_contrato_{$contrato->idcontrato}.pdf");
    }

    // Generar contrato en PDF
    public function generarContrato(Request $request)
    {
        $contrato = Contrato::findOrFail($request->idcontrato);
        $pdf = PDF::loadView('pdf.contrato', compact('contrato'));
        return $pdf->download("contrato_{$contrato->idcontrato}.pdf");
    }

    public function asignarCooler($idcooler)
    {
        $cooler = Cooler::findOrFail($idcooler);
        return response()->json(['idcooler' => $cooler->id]);
    }

    public function asignarFruta($idfruta)
    {
        $fruta = Fruta::findOrFail($idfruta);
        return response()->json(['idfruta' => $fruta->id]);
    }

    public function destroy(Contrato $contrato)
    {
        $contrato->estatus = 'inactivo';
        $contrato->save();
        return redirect()->route('contrato.mostrar')->with('success', 'Contrato eliminado.');
    }
}
