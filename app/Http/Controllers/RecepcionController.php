<?php

namespace App\Http\Controllers;
use App\Models\Comercializadora;
use App\Models\Contrato;
use App\Models\User;
use App\Models\Recepcion;
use App\Models\DetalleRecepcion;
use App\Models\Presentacion;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RecepcionController extends Controller
{
    /*public function index()
    {
        $user = auth()->user();
        $query = Recepcion::with('contrato.cooler');

        // Filtrar según el rol del usuario
        if ($user->rol->nombrerol == 'Administrador') {
            // Mostrar todas las recepciones
        } elseif ($user->rol->nombrerol == 'Supervisor' || $user->rol->nombrerol == 'Operativo') {
            // Mostrar solo recepciones creadas por el usuario y de coolers asignados
            $coolerIds = $user->coolers()->where('usuario_cooler.estatus', 'activo')->pluck('cooler.id');
            $query->where('idusuario', $user->id)
                  ->whereHas('contrato', function($q) use ($coolerIds) {
                      $q->whereIn('idcooler', $coolerIds);
                  });
        }

        $recepciones = $query->orderBy('created_at', 'desc')->get();
        return view('recepcion.mostrar', compact('recepciones'));
    }*/
    
    public function index()
    {
        $user = auth()->user();
        $query = Recepcion::with('contrato.comercializadora', 'usuario');
    
        // Filtrar según el rol del usuario
        if ($user->rol->nombrerol == 'Administrador') {
            // Mostrar todas las recepciones
        } else {
            // Obtener los IDs de los coolers asignados al usuario
            $coolerIds = $user->coolers()
                             ->wherePivot('estatus', 'activo')
                             ->pluck('cooler.id');
            
            // Mostrar recepciones de los coolers asignados, sin filtrar por usuario
            $query->whereHas('contrato', function($q) use ($coolerIds) {
                $q->whereIn('idcooler', $coolerIds);
            });
        }
    
        $recepciones = $query->orderBy('created_at', 'desc')->get();
        return view('recepcion.mostrar', compact('recepciones'));
    }

    public function create($idcontrato)
    {
        $comercializadoras = Comercializadora::all();
        //$presentaciones= Presentacion::all();
        $contratos = Contrato::with('detallecontrato')->findOrFail($idcontrato);
        $presentaciones = $contratos->detallecontrato->pluck('presentacion')->unique('id')->values();
        $frutas = $contratos->detallecontrato->pluck('fruta')->unique('id')->values();
        $variedades = $contratos->detallecontrato->pluck('variedad')->unique('id')->values();
        $ultimo = Recepcion::latest('id')->first();
        if ($ultimo && preg_match('/^N°-([A-Z])(\d{5})$/', $ultimo->folio, $matches)) {
            $letra = $matches[1];
            $numero = intval($matches[2]) + 1;

            if ($numero > 99999) {
                $letra = chr(ord($letra) + 1);
                $numero = 1;
            }
        } else {
            $letra = 'A';
            $numero = 1;
        }

        $folio = "N°-" . $letra . str_pad($numero, 5, '0', STR_PAD_LEFT);
      return view('recepcion.crear', compact('presentaciones','contratos','comercializadoras', 'folio', 'frutas', 'variedades'));
      
    }


    public function store(Request $request)
    {
        // Validación
        $data = $request->validate([
            'datosclave' => 'required|string',
            'area' => 'required|string',
            'revision' => 'required|string',
            'fechaemision' => 'required|date',
            'folio' => 'required|string',
            'idusuario' => 'required|integer',
            'idcontrato' => 'required|integer',
        ], [
            'datosclave.required' => 'El campo Clave es obligatorio.',
            'area.required' => 'El campo Área es obligatorio.',
            'revision.required' => 'El campo Revisión es obligatorio.',
            'fechaemision.required' => 'El campo Fecha Emisión es obligatorio.',
            'folio.required' => 'El campo Folio es obligatorio.',
            'idusuario.required' => 'El campo Usuario es obligatorio.',
        ]);

        // Crear la recepción
        $data['estatus'] = 'CON DETALLE';
        $recepcion = Recepcion::create($data);

        // Guardar los detalles de recepción (tabla)
        $horas         = $request->hora;
        $cantidades    = $request->cantidad;
        $frutas        = $request->idfruta;
        $variedades    = $request->variedad;
        $presentaciones = $request->presentacion;
        $temperaturas  = $request->temperatura;
        $tipos         = $request->tipo_temperatura;

        // Validar que los arrays existan y tengan la misma longitud
        if ($horas && is_array($horas)) {
            for ($i = 0; $i < count($horas); $i++) {
                DetalleRecepcion::create([
                    'idrecepcion'      => $recepcion->id,
                    'hora'             => $horas[$i],
                    'cantidad'         => $cantidades[$i],
                    'pendientes'       => $cantidades[$i], // Inicializar pendientes = cantidad
                    'idfruta'          => $frutas[$i],
                    'idvariedad'       => $variedades[$i],
                    'idpresentacion'   => $presentaciones[$i],
                    'temperatura'      => $temperaturas[$i],
                    'tipo'             => $tipos[$i],
                    'folio'            => $data['folio'], // o uno nuevo si es necesario
                ]);
            }
        }

        return redirect()->back()->with('success', 'Recepción creada con éxito');
    }



    public function show(Recepcion $recepcion)
    {
        $recepcion->load('detalles'); 
        //return $recepcion;
        return view('recepcion.mostrarId', compact('recepcion'));
    }


    public function edit(Recepcion $recepcion)
    {
        $contrato = Contrato::with('detallecontrato')->findOrFail($recepcion->idcontrato);
        $frutas = $contrato->detallecontrato->pluck('fruta')->unique('id')->values();
        $variedades = $contrato->detallecontrato->pluck('variedad')->unique('id')->values();
        $presentaciones = Presentacion::all(); // O filtradas si lo deseas
        $recepcionado=$recepcion->load('detalles');
        //return $variedades;
        return view('recepcion.editar', compact(
            'recepcion','frutas','variedades','presentaciones',
        ));
    }




    public function update(Request $request, Recepcion $recepcion)
    {
        $data = $request->validate([
            'datosclave' => 'required|string',
            'area' => 'required|string',
            'revision' => 'required|string',
            'fechaemision' => 'required|date',
            'folio' => 'required|string',
            'idcontrato' => 'required|integer',
            'idusuario' => 'required|integer',
            'observaciones' => 'nullable|string',
        ]);

        $recepcion->update($data);

        // Obtener arrays del formulario
        $detalleIds = $request->detalle_id;
        $horas = $request->hora;
        $cantidades = $request->cantidad;
        $frutas = $request->idfruta;
        $variedades = $request->variedad;
        $presentaciones = $request->presentacion;
        $temperaturas = $request->temperatura;
        $tipos = $request->tipo_temperatura;

        for ($i = 0; $i < count($horas); $i++) {
            $detalleData = [
                'hora' => $horas[$i],
                'cantidad' => $cantidades[$i],
                'idfruta' => $frutas[$i],
                'idvariedad' => $variedades[$i],
                'idpresentacion' => $presentaciones[$i],
                'temperatura' => $temperaturas[$i],
                'tipo' => $tipos[$i],
                'folio' => $recepcion->folio,
            ];

            if (!empty($detalleIds[$i])) {
                // Actualizar detalle existente
                $detalle = DetalleRecepcion::find($detalleIds[$i]);
                if ($detalle && $detalle->idrecepcion == $recepcion->id) {
                    $detalle->update($detalleData);
                }
            } else {
                // Crear nuevo detalle
                $recepcion->detalles()->create($detalleData);
            }
        }

        return redirect()->route('recepcion.mostrar')->with('success', 'Recepción actualizada correctamente.');
    }




    public function destroy(Request $request, Recepcion $recepcion)
    {
        // Validar observaciones
        $request->validate([
            'observaciones' => 'required|string|max:500',
        ], [
            'observaciones.required' => 'Debe ingresar una observación para cancelar la recepción.',
        ]);

        $recepcion->estatuseliminar = 'inactivo';
        $recepcion->estatus = 'CANCELADA';
        $recepcion->observaciones = $request->observaciones;
        $recepcion->save();
        
        return redirect()->route('recepcion.mostrar')->with('success', 'Recepción cancelada con éxito');
    }

    public function getInfo($id)
    {
        $recepcion = Recepcion::with('detalleRecepcion', 'tarimas', 'comercializadora')->find($id);

        if (!$recepcion) {
            return response()->json(['error' => 'Recepción no encontrada'], 404);
        }
        
        return response()->json([
            'area' => $recepcion->area,
            'cliente' => $recepcion->comercializadora->nombrecomercializadora,
            'fecha' => $recepcion->fecha,
            'fechaemision' => $recepcion->fechaemision,
            'detalles' => $recepcion->detalleRecepcion->map(function ($detalle) {
                return [
                    'temperatura' => $detalle->temperatura,
                    'idfruta' => $detalle->idfruta,
                    'variedad'=> $detalle->variedad,
                    'presentacion'=> $detalle->presentacion,
                    'nombrefruta' => optional($detalle->fruta)->nombrefruta ?? '', 
                    'tarima'=> $detalle->iddetalle,
                ];
            }),
            'tarimas' => $recepcion->tarimas->map(function ($tarima) {
                return [
                    'id' => $tarima->id,
                    'codigo' => $tarima->codigo,
                    'numpallet' => $tarima->numpallet,
                    'tipopallet' => $tarima->tipopallet,
                    'cantidad' => $tarima->cantidad,
                ];
            }),
        ]);
    }

    /**
     * Mostrar recepciones filtradas por comercializadora
     */
    public function porComercializadora($idComercializadora)
    {
        $user = auth()->user();
        $comercializadora = Comercializadora::findOrFail($idComercializadora);
        
        $query = Recepcion::with('contrato.cooler', 'contrato.comercializadora')
            ->where('estatuseliminar', 'activo')
            ->whereHas('contrato', function($q) use ($idComercializadora) {
                $q->where('idcomercializadora', $idComercializadora);
            });

        // Filtrar según el rol del usuario
        if ($user->rol->nombrerol == 'Administrador') {
            // Mostrar todas las recepciones
        } elseif ($user->rol->nombrerol == 'Supervisor' || $user->rol->nombrerol == 'Operativo') {
            // Mostrar solo recepciones creadas por el usuario y de coolers asignados
            $coolerIds = $user->coolers()->where('usuario_cooler.estatus', 'activo')->pluck('cooler.id');
            $query->where('idusuario', $user->id)
                  ->whereHas('contrato', function($q) use ($coolerIds) {
                      $q->whereIn('idcooler', $coolerIds);
                  });
        }

        $recepciones = $query->orderBy('created_at', 'desc')->get();
        return view('recepcion.mostrar', compact('recepciones', 'comercializadora'));
    }

    public function verPdf($id)
    {
       try {
            $recepcion = Recepcion::with([
                'contrato.comercializadora',
                'contrato.cooler',
                'detalles.fruta',
                'detalles.variedad',
                'detalles.presentacion',
                'usuario'
            ])->findOrFail($id);

            // Log para depuración
            \Log::info('Generando PDF', [
                'recepcion_id' => $id,
                'tiene_firma1' => !empty($recepcion->firma_responsable1),
                'tiene_firma2' => !empty($recepcion->firma_responsable2),
            ]);

            $pdf = Pdf::loadView('recepcion.ver_pdf', compact('recepcion'))
                ->setPaper('letter', 'portrait')
                ->setOption('isHtml5ParserEnabled', true)
                ->setOption('isRemoteEnabled', true);
                
            return $pdf->stream('recepcion_' . $recepcion->folio . '.pdf');
        } catch (\Exception $e) {
            \Log::error('Error al generar PDF', [
                'recepcion_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Error al generar PDF: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar vista para agregar firmas
     */
    public function firmas($id)
    {
        $recepcion = Recepcion::findOrFail($id);
        return view('recepcion.firmas', compact('recepcion'));
    }

    /**
     * Guardar firmas digitales
     */
    public function guardarFirmas(Request $request, $id)
    {
        $request->validate([
            'nombre_responsable1' => 'required|string|max:255',
            'nombre_responsable2' => 'required|string|max:255',
            'firma_responsable1' => 'required|string',
            'firma_responsable2' => 'required|string',
            'nota_firmas' => 'nullable|string|max:500',
        ], [
            'nombre_responsable1.required' => 'El nombre del Responsable 1 es obligatorio.',
            'nombre_responsable2.required' => 'El nombre del Responsable 2 es obligatorio.',
            'firma_responsable1.required' => 'La firma del Responsable 1 es obligatoria.',
            'firma_responsable2.required' => 'La firma del Responsable 2 es obligatoria.',
        ]);

        try {
            $recepcion = Recepcion::findOrFail($id);
            
            // Log para depuración
            \Log::info('Guardando firmas', [
                'recepcion_id' => $id,
                'nombre1' => $request->nombre_responsable1,
                'nombre2' => $request->nombre_responsable2,
                'firma1_length' => strlen($request->firma_responsable1),
                'firma2_length' => strlen($request->firma_responsable2),
            ]);
            
            $recepcion->update([
                'nombre_responsable1' => $request->nombre_responsable1,
                'nombre_responsable2' => $request->nombre_responsable2,
                'firma_responsable1' => $request->firma_responsable1,
                'firma_responsable2' => $request->firma_responsable2,
                'nota_firmas' => $request->nota_firmas,
            ]);

            \Log::info('Firmas guardadas exitosamente', ['recepcion_id' => $id]);

            return redirect()->route('recepcion.show', $recepcion)
                ->with('success', 'Firmas guardadas correctamente.');
        } catch (\Exception $e) {
            \Log::error('Error al guardar firmas', [
                'recepcion_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al guardar las firmas: ' . $e->getMessage());
        }
    }
}
