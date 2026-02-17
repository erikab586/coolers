<?php

namespace App\Http\Controllers;
use App\Models\Conservacion;
use App\Models\Preenfriado;
use App\Models\DetalleConservacion;
use App\Models\DetalleRecepcion;
use App\Models\TarimaDetarec;
use App\Models\Tarima;
use App\Models\DetalleCruceAnden;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ConservacionController extends Controller
{
   /* public function index()
    {
        $user = auth()->user();
        
        // Filtrar conservaciones según el rol del usuario
        $conservacionesQuery = Conservacion::with('tarima.tarimaDetarec.detalle.recepcion.contrato.cooler', 'camara');

        if ($user->rol->nombrerol == 'Administrador') {
            // Mostrar todas las conservaciones
        } elseif ($user->rol->nombrerol == 'Supervisor' || $user->rol->nombrerol == 'Operativo') {
            // Mostrar solo conservaciones de recepciones creadas por el usuario y de coolers asignados
            $coolerIds = $user->coolers()->where('usuario_cooler.estatus', 'activo')->pluck('cooler.id');
            $conservacionesQuery->whereHas('tarima.tarimaDetarec.detalle.recepcion', function($q) use ($coolerIds, $user) {
                $q->where('idusuario', $user->id)
                  ->whereHas('contrato', function($subQ) use ($coolerIds) {
                      $subQ->whereIn('idcooler', $coolerIds);
                  });
            });
        }

        $conservaciones = $conservacionesQuery->orderBy('id', 'desc')->get();
        
        return view('conservacion.mostrar', compact('conservaciones'));
    }*/
    public function index()
    {
        $user = auth()->user();
        
        // Filtrar conservaciones según el rol del usuario
        $conservacionesQuery = Conservacion::with('tarima.tarimaDetarec.detalle.recepcion.contrato.cooler', 'camara');
    
        if ($user->rol->nombrerol == 'Administrador') {
            // Mostrar todas las conservaciones
        } else {
            // Obtener los IDs de los coolers asignados al usuario
            $coolerIds = $user->coolers()
                             ->wherePivot('estatus', 'activo')
                             ->pluck('cooler.id');
            
            // Mostrar conservaciones de los coolers asignados, sin filtrar por usuario
            $conservacionesQuery->whereHas('tarima.tarimaDetarec.detalle.recepcion.contrato', function($q) use ($coolerIds) {
                $q->whereIn('idcooler', $coolerIds);
            });
        }
    
        $conservaciones = $conservacionesQuery->orderBy('id', 'desc')->get();
        
        return view('conservacion.mostrar', compact('conservaciones'));
    }
    //Metodo para guardar el crear de preenfriado compuesto por una camara y varias tarimas
    /*public function store(Request $request, $id)
    {
        $request->validate([
            'respuesta' => 'required|string|in:Si,No',
            'idcamara' => 'required|exists:camara,id',
        ]);
        $respuesta = strtolower($request->input('respuesta'));
        if ($respuesta === 'si') {
            // Crear Preenfriado
            $preenfriado = Conservacion::create([
                'idtarima' => $id,
                'idcamara' => $request->idcamara, 
            ]);

            // Traer las tarimas con relaciones
            $tarimas = TarimaDetarec::with('detalle')
                ->where('idtarima', $id)
                ->get();

            // Actualizar todos los detalles cuyo estatus sea "tarima"
            foreach ($tarimas as $tarima) {
                if ($tarima->detalle && $tarima->detalle->estatus === 'preenfriado') {
                    $tarima->detalle->estatus = 'conservacion';
                    $tarima->detalle->save();
                }
            }

            return redirect()->back()->with('success', 'Tarima enviada a Conservación correctamente.');
        }
        else
            return redirect()->back()->with('info', 'No se envió la tarima a Conservación.');
    }

    //Metodo para crear los datos de entradas en las recepciones de una tarima, se filtra por tarima
    public function create($id)
    {
        $tarima = Tarima::with([
            'tarimaDetarec.detalle.recepcion.contrato.comercializadora',
            'tarimaDetarec.detalle.recepcion.contrato.cooler.camaras',
            'preenfriado.camara'
        ])->findOrFail($id);

        $tarimasAgrupadas = $tarima->tarimaDetarec->groupBy(function ($item) {
            return $item->detalle->recepcion->id ?? null;
        });

        $recepcionesData = $tarimasAgrupadas->map(function ($coleccion) {
            $primera = $coleccion->first();
            $recepcion = $primera->detalle->recepcion;

            return [
                'id' => $recepcion->id,
                'comercializadora' => $recepcion->contrato->comercializadora->nombrecomercializadora ?? '',
                'area' => $recepcion->area ?? '',
                'fechaemision' => $recepcion->fecha_emision ?? '',
                'fecha' => $recepcion->fecha ?? '',
                'cooler' => $recepcion->contrato->cooler->nombrecooler ?? '',
            ];
        });

        $detallesData = $tarimasAgrupadas->mapWithKeys(function ($coleccion, $idRecepcion) {
            return [$idRecepcion => $coleccion->map(function($item) {
                return [
                    'id'=> $item->detalle->id ?? '',
                    'idpreenfrio' => $item->preenfriado->id ?? '',
                    'fruta' => $item->detalle->fruta->nombrefruta ?? '',
                    'presentacion' => $item->detalle->presentacion->nombrepresentacion ?? '',
                    'variedad' => $item->detalle->variedad->tipofruta ?? '',
                ];
            })->toArray()];
        });
       // return $detallesData;
        return view('preenfriado.crear', [
            'tarima' => $tarima,
            'tarimasAgrupadas' => $tarimasAgrupadas,
            'recepcionesData' => $recepcionesData,
            'detallesData' => $detallesData
        ]);
    }


    public function storedetalle(Request $request)
    {
        // Validación básica
        $request->validate([
            'idpreenfriado.*' => 'required|integer',
            'iddetalle.*' => 'required|integer',
            'hora_entrada.*' => 'required',
            'temperatura_entrada.*' => 'required|numeric',
        ]);

        $idpreenfriado = $request->input('idpreenfriado');
        $iddetalle = $request->input('iddetalle');
        $horaEntrada = $request->input('hora_entrada');
        $tempEntrada = $request->input('temperatura_entrada');

        $horaSalida = $request->input('hora_salida');
        $tempSalida = $request->input('temperatura_salida');
        $tiempoTotal = $request->input('tiempototal');

        // Recorrer cada detalle y guardar
        foreach ($iddetalle as $index => $detalleId) {
            DetallePreenfriado::create([
                'idpreenfrio' => $idpreenfriado[$index],
                'iddetalle' => $detalleId,
                'hora_entrada' => $horaEntrada[$index],
                'temperatura_entrada' => $tempEntrada[$index],
                'hora_salida' => $horaSalida[$index] ?? null,
                'temperatura_salida' => $tempSalida[$index] ?? null,
                'tiempototal' => $tiempoTotal[$index] ?? null,
            ]);

        }

        return redirect()->route('enfrio.mostrar')->with('success', 'Datos guardados correctamente.');
    }*/



    //Metodo para editar los datos de salidas en las recepciones de una tarima, se filtra por tarima
    public function edit($id)
    {
        $conservaciones = Preenfriado::with([
            'detallesPreenfriado.detalleRecepcion',
            'camara'
        ])
        ->where('idtarima', $id)->orderBy('created_at', 'desc')
        ->firstOrFail();

        $identificador = Conservacion::where('idtarima', $id)->first();
        
        $yaEditado = false;
        $detallesConservacion = collect();
        $detallesCruce = collect();

        if ($identificador) {
            $yaEditado = DetalleConservacion::where('idconservacion', $identificador->id)
                ->whereNotNull('hora_salida')
                ->exists();

            // clave por iddetalle para encontrar rápido por cada fila
            $detallesConservacion = DetalleConservacion::where('idconservacion', $identificador->id)
                ->get()
                ->keyBy('iddetalle');

            // Cargar detalles de cruce de andén asociados a esta tarima (si viene de cruce)
            $tarimaId = $identificador->idtarima;
            if ($tarimaId) {
                $detallesCruce = DetalleCruceAnden::whereHas('cruceAnden', function ($q) use ($tarimaId) {
                        $q->where('idtarima', $tarimaId);
                    })
                    ->get()
                    ->keyBy('iddetalle');
            }
        }

        return view('conservacion.editar', compact('conservaciones','identificador', 'yaEditado', 'detallesConservacion', 'detallesCruce'));
    }

   
    public function storedetalle(Request $request)
    {
        $request->validate([
            'idconservacion.*' => 'required|integer',
            'iddetalle.*' => 'required|integer',
            'hora_entrada.*' => 'required',
            'temperatura_entrada.*' => 'required|numeric',
        ]);

        $idConservacion = $request->input('idconservacion');
        $idDetalle = $request->input('iddetalle');
        $idDetalleTarima = $request->input('iddetalletarima');
        // Validar que la conservación no haya sido completada previamente (no tenga hora_salida)
        $detalleYaCompletado = DetalleConservacion::where('idconservacion', $idConservacion[0])
            ->whereNotNull('hora_salida')
            ->first();
        
        if ($detalleYaCompletado) {
            return redirect()->back()->with('error', 'Esta Conservación ya fue completada anteriormente. Solo se puede editar una vez.');
        }
        
        $horaEntrada = $request->input('hora_entrada');
        $tempEntrada = $request->input('temperatura_entrada');
        $horaSalida = $request->input('hora_salida');
        $tempSalida = $request->input('temperatura_salida');

        foreach ($idDetalle as $index => $detalleId) {
            // Convertir las fechas/horas ingresadas en el formulario
            $horaEntradaObj = new \DateTime($horaEntrada[$index]);
            $horaSalidaObj = $horaSalida[$index] ? new \DateTime($horaSalida[$index]) : null;

            $tiempoEnMinutos = null;
            if ($horaSalidaObj) {
              // Calcular la diferencia en segundos
                    $diferenciaSegundos = $horaSalidaObj->getTimestamp() - $horaEntradaObj->getTimestamp();

                    // Convertir a minutos (entero)
                    $tiempoEnMinutos = intdiv($diferenciaSegundos, 60);

                    // Si el tiempo es negativo, significa error de entrada
                    if ($tiempoEnMinutos < 0) {
                        return redirect()->back()->withErrors(
                            'La hora de salida debe ser posterior a la hora de entrada.'
                        )->withInput();
                    }
            }
            
            // Crear o actualizar el detalle de conservación con DATETIME completo
            DetalleConservacion::updateOrCreate(
                [
                    'idconservacion' => $idConservacion[$index],
                    'iddetalle' => $detalleId,
                ],
                [
                    'iddetalletarima' => $idDetalleTarima[$index],
                    'hora_entrada' => $horaEntrada[$index], // DATETIME completo
                    'temperatura_entrada' => $tempEntrada[$index],
                    'hora_salida' => $horaSalida[$index] ?? null, // DATETIME completo
                    'temperatura_salida' => 0,
                    'tiempototal' => $tiempoEnMinutos,
                ]
            );

            // Actualizar la ubicación del detalle a "embarque"
            $detalle = DetalleRecepcion::find($detalleId);
            if ($detalle) {
                $detalle->estatus = 'embarcacion';
                $detalle->save();
            }
        }
        
        // Actualizar el estatus de la recepción asociada a la tarima
        $conservacion = Conservacion::find($idConservacion[0]);
        if ($conservacion && $conservacion->tarima) {
            $recepcion = $conservacion->tarima->recepcion;
            $idtarima= $conservacion->tarima->id;
            if ($recepcion) {
                $recepcion->estatus = 'EN EMBARQUE';
                $recepcion->save();
            }
            Tarima::where('id', $idtarima)->update([
                        'ubicacion' => 'embarque'
            ]);
        }

        return redirect()->route('conservacion.mostrar')->with('success', 'Conservación guardada y tarimas enviadas a Embarque correctamente.');
    }

    public function show($id)
    {
         $conservaciones = Conservacion::with(['detallesConservacion.tarimaDetarec',
            'detallesConservacion.detalleRecepcion'
        ])
        ->where('idtarima', $id)
        ->firstOrFail();

        //return $conservaciones;
       return view('conservacion.mostrarId', compact('conservaciones'));
    }

    public function destroy(Request $request, $id)
    {
        // Validar observaciones obligatorias
        $request->validate([
            'observaciones' => 'required|string|max:500',
        ], [
            'observaciones.required' => 'Las observaciones son obligatorias para eliminar una conservación.',
            'observaciones.max' => 'Las observaciones no pueden exceder los 500 caracteres.',
        ]);

        $conservacion = Conservacion::find($id);
        
        if (!$conservacion) {
            return redirect()->route('conservacion.mostrar')->with('error', 'Conservación no encontrada.');
        }

        // Guardar observaciones antes de eliminar
        $conservacion->observaciones = $request->observaciones;
        $conservacion->save();
        
        $conservacion->delete();

        return redirect()->route('conservacion.mostrar')->with('success', 'Conservación eliminada exitosamente.');
    }

    public function verPdf($id)
    {
        try {
            $conservacion = Conservacion::with(['detallesConservacion.tarimaDetarec',
                'tarima.tarimaDetarec.detalle.recepcion.contrato.comercializadora',
                'tarima.tarimaDetarec.detalle.fruta',
                'tarima.tarimaDetarec.detalle.presentacion',
                'tarima.tarimaDetarec.detalle.variedad',
                'camara.cooler',
                'detallesConservacion.detalleRecepcion'
            ])->findOrFail($id);

            $pdf = Pdf::loadView('conservacion.ver_pdf', compact('conservacion'));
            return $pdf->stream('conservacion_' . $id . '.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar PDF: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar vista para agregar firmas
     */
    public function firmas($id)
    {
        $conservacion = Conservacion::findOrFail($id);
        return view('conservacion.firmas', compact('conservacion'));
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
            $conservacion = Conservacion::findOrFail($id);
            
            $conservacion->update([
                'nombre_responsable1' => $request->nombre_responsable1,
                'nombre_responsable2' => $request->nombre_responsable2,
                'firma_responsable1' => $request->firma_responsable1,
                'firma_responsable2' => $request->firma_responsable2,
                'nota_firmas' => $request->nota_firmas,
            ]);

            return redirect()->route('conservacion.mostrarid', $conservacion)
                ->with('success', 'Firmas guardadas correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al guardar las firmas: ' . $e->getMessage());
        }
    }
}
