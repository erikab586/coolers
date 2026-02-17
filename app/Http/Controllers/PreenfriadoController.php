<?php

namespace App\Http\Controllers;
use App\Models\Preenfriado;
use App\Models\DetallePreenfriado;
use App\Models\TarimaDetarec;
use App\Models\Tarima;
use App\Models\Camara;
use App\Models\Conservacion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PreenfriadoController extends Controller
{
    //Metodo para mostrar el listado en preenfriado
    /*public function index()
    {
        $user = auth()->user();
        
        // Filtrar preenfriados según el rol del usuario
        $preenfriadosQuery = Preenfriado::with('tarima.tarimaDetarec.detalle.recepcion.contrato.cooler', 'camara');

        if ($user->rol->nombrerol == 'Administrador') {
            // Mostrar todos los preenfriados
        } elseif ($user->rol->nombrerol == 'Supervisor' || $user->rol->nombrerol == 'Operativo') {
            // Mostrar solo preenfriados de recepciones creadas por el usuario y de coolers asignados
            $coolerIds = $user->coolers()->where('usuario_cooler.estatus', 'activo')->pluck('cooler.id');
            $preenfriadosQuery->whereHas('tarima.tarimaDetarec.detalle.recepcion', function($q) use ($coolerIds, $user) {
                $q->where('idusuario', $user->id)
                  ->whereHas('contrato', function($subQ) use ($coolerIds) {
                      $subQ->whereIn('idcooler', $coolerIds);
                  });
            });
        }

        $preenfriados = $preenfriadosQuery->orderBy('id', 'desc')->get();

        // Filtrar cámaras según el rol del usuario
        $camarasQuery = Camara::with('cooler')->where('tipo', 'CONSERVACIÓN');

        if ($user->rol->nombrerol == 'Administrador') {
            $camaras = $camarasQuery->get();
        } elseif ($user->rol->nombrerol == 'Supervisor' || $user->rol->nombrerol == 'Operativo') {
            $coolerIds = $user->coolers()->pluck('cooler.id');
            $camaras = $camarasQuery->whereIn('idcooler', $coolerIds)->get();
        } else {
            $camaras = collect(); // Colección vacía para otros roles
        }

        return view('preenfriado.mostrar', compact('preenfriados', 'camaras'));
    }*/
    public function index()
    {
        $user = auth()->user();
        
        // Filtrar preenfriados según el rol del usuario
        $preenfriadosQuery = Preenfriado::with('tarima.tarimaDetarec.detalle.recepcion.contrato.cooler', 'camara');
    
        if ($user->rol->nombrerol == 'Administrador') {
            // Mostrar todos los preenfriados
        } else {
            // Obtener los IDs de los coolers asignados al usuario
            $coolerIds = $user->coolers()
                             ->wherePivot('estatus', 'activo')
                             ->pluck('cooler.id');
            
            // Mostrar preenfriados de los coolers asignados, sin filtrar por usuario
            $preenfriadosQuery->whereHas('tarima.tarimaDetarec.detalle.recepcion.contrato', function($q) use ($coolerIds) {
                $q->whereIn('idcooler', $coolerIds);
            });
        }
    
        $preenfriados = $preenfriadosQuery->orderBy('id', 'desc')->get();
    
        // Filtrar cámaras según el rol del usuario
        $camarasQuery = Camara::with('cooler')->where('tipo', 'CONSERVACIÓN');
    
        if ($user->rol->nombrerol == 'Administrador') {
            $camaras = $camarasQuery->get();
        } else {
            // Obtener cámaras de los coolers asignados al usuario
            $coolerIds = $user->coolers()
                             ->wherePivot('estatus', 'activo')
                             ->pluck('cooler.id');
            
            $camaras = $camarasQuery->whereIn('idcooler', $coolerIds)->get();
        }
    
        return view('preenfriado.mostrar', compact('preenfriados', 'camaras'));
    }
    //Metodo para guardar el crear de preenfriado compuesto por una camara y varias tarimas

   /* public function store(Request $request, $id) 
    {
        $request->validate([
            'respuesta' => 'required|string|in:Si,No',
            'idcamara'  => 'required|exists:camara,id',
        ]);

        $respuesta = strtolower($request->input('respuesta'));

        if ($respuesta === 'si') {
            // Validar que la tarima no haya sido enviada a preenfriado previamente
            $preenfriado_existente = Preenfriado::where('idtarima', $id)->first();
            if ($preenfriado_existente) {
                return redirect()->back()->with('error', 'Esta tarima ya fue enviada a Pre-Enfriado anteriormente. Una tarima solo puede pasar por Pre-Enfriado una vez.');
            }
            
            // 1️⃣ Crear el Preenfriado
            $preenfriado = Preenfriado::create([
                'idtarima' => $id,
                'idcamara' => $request->idcamara,
            ]);

            // 2️⃣ Actualizar la ubicación de la tarima
            Tarima::where('id', $id)->update([
                'ubicacion' => 'preenfriado'
            ]);

            // 3️⃣ Traer todos los registros de TarimaDetarec vinculados
            $tarimas = TarimaDetarec::with('detalle')
                ->where('idtarima', $id)
                ->get();

            foreach ($tarimas as $tarima) {
                if ($tarima->detalle) {
                    // 3.1 Cambiar el estatus a 'preenfriado'
                    if ($tarima->detalle->estatus === 'tarima') {
                        $tarima->detalle->estatus = 'preenfriado';
                        $tarima->detalle->save();
                        
                        // Actualizar el estatus de la recepción a 'EN PREENFRIADO'
                        if ($tarima->detalle->recepcion) {
                            $tarima->detalle->recepcion->estatus = 'EN PREENFRIADO';
                            $tarima->detalle->recepcion->save();
                        }
                    }

                    // 3.2 Crear un registro en DetallePreenfriado
                    DetallePreenfriado::create([
                        'idpreenfrio'         => $preenfriado->id,
                        'iddetalle'           => $tarima->iddetalle,
                        'iddetalletarima'     => $tarima->id,
                        'hora_entrada'        => Carbon::now(),
                        'temperatura_entrada' => 25,
                    ]);
                }
            }
            
            return redirect()->route('enfrio.mostrar')->with('success', 'Enviado a Pre-Enfriado correctamente.');
        } else {
             return redirect()->route('enfrio.mostrar')->with('info', 'No se envió la tarima a Pre-Enfriado.');
        }
    }*/
    public function store(Request $request, $id) 
    {
        $request->validate([
            'respuesta' => 'required|string|in:Si,No',
            'idcamara'  => 'required|exists:camara,id',
        ]);

        $respuesta = strtolower($request->input('respuesta'));

        if ($respuesta === 'si') {
            // Validar que la tarima no haya sido enviada a preenfriado previamente
            $preenfriado_existente = Preenfriado::where('idtarima', $id)->first();
            if ($preenfriado_existente) {
                // ⬅ Igual estilo que storeMultiple: regresar a tarimas con mensaje
                return redirect()
                    ->route('tarima.mostrar')
                    ->with('info', 'Esta tarima ya había sido enviada a Pre-Enfriado anteriormente.');
            }
            
            // 1️⃣ Crear el Preenfriado
            $preenfriado = Preenfriado::create([
                'idtarima' => $id,
                'idcamara' => $request->idcamara,
            ]);

            // 2️⃣ Actualizar la ubicación de la tarima
            Tarima::where('id', $id)->update([
                'ubicacion' => 'preenfriado'
            ]);

            // 3️⃣ Traer todos los registros de TarimaDetarec vinculados
            $tarimas = TarimaDetarec::with('detalle')
                ->where('idtarima', $id)
                ->get();

            foreach ($tarimas as $tarima) {
                if ($tarima->detalle) {
                    // 3.1 Cambiar el estatus a 'preenfriado'
                    if ($tarima->detalle->estatus === 'tarima') {
                        $tarima->detalle->estatus = 'preenfriado';
                        $tarima->detalle->save();
                        
                        // Actualizar el estatus de la recepción a 'EN PREENFRIADO'
                        if ($tarima->detalle->recepcion) {
                            $tarima->detalle->recepcion->estatus = 'EN PREENFRIADO';
                            $tarima->detalle->recepcion->save();
                        }
                    }

                    // 3.2 Crear un registro en DetallePreenfriado
                    DetallePreenfriado::create([
                        'idpreenfrio'         => $preenfriado->id,
                        'iddetalle'           => $tarima->iddetalle,
                        'iddetalletarima'     => $tarima->id,
                        'hora_entrada'        => Carbon::now(),
                        'temperatura_entrada' => 25,
                    ]);
                }
            }
            
            // ⬅ Igual estilo que storeMultiple: regresar a tarimas
            return redirect()
                ->route('tarima.mostrar')
                ->with('success', 'Se envió la tarima a Pre-Enfriado correctamente.');
        } else {
            // Si respondió "No", también regresar a tarimas
            return redirect()
                ->route('tarima.mostrar')
                ->with('info', 'No se envió la tarima a Pre-Enfriado.');
        }
    }
    /**
     * Método para enviar múltiples tarimas a Pre-Enfriado
     */
    public function storeMultiple(Request $request)
    {
        $request->validate([
            'idcamara' => 'required|exists:camara,id',
            'tarimas_ids' => 'required|string',
        ]);

        $tarimasIds = explode(',', $request->tarimas_ids);
        $tarimasEnviadas = 0;
        $tarimasYaExistentes = 0;

        foreach ($tarimasIds as $idTarima) {
            // Validar que la tarima no haya sido enviada a preenfriado previamente
            $preenfriado_existente = Preenfriado::where('idtarima', $idTarima)->first();
            if ($preenfriado_existente) {
                $tarimasYaExistentes++;
                continue; // Saltar esta tarima
            }

            // 1️⃣ Crear el Preenfriado
            $preenfriado = Preenfriado::create([
                'idtarima' => $idTarima,
                'idcamara' => $request->idcamara,
            ]);

            // 2️⃣ Actualizar la ubicación de la tarima
            Tarima::where('id', $idTarima)->update([
                'ubicacion' => 'preenfriado'
            ]);

            // 3️⃣ Traer todos los registros de TarimaDetarec vinculados
            $tarimas = TarimaDetarec::with('detalle')
                ->where('idtarima', $idTarima)
                ->get();

            foreach ($tarimas as $tarima) {
                if ($tarima->detalle) {
                    // 3.1 Cambiar el estatus a 'preenfriado'
                    if ($tarima->detalle->estatus === 'tarima') {
                        $tarima->detalle->estatus = 'preenfriado';
                        $tarima->detalle->save();
                        
                        // Actualizar el estatus de la recepción a 'EN PREENFRIADO'
                        if ($tarima->detalle->recepcion) {
                            $tarima->detalle->recepcion->estatus = 'EN PREENFRIADO';
                            $tarima->detalle->recepcion->save();
                        }
                    }

                    // 3.2 Crear un registro en DetallePreenfriado
                    DetallePreenfriado::create([
                        'idpreenfrio'         => $preenfriado->id,
                        'iddetalle'           => $tarima->iddetalle,
                        'iddetalletarima'     => $tarima->id,
                        'hora_entrada'        => now(), // Usar helper now() de Laravel
                        'temperatura_entrada' => 25,
                    ]);
                }
            }

            $tarimasEnviadas++;
        }

        $mensaje = "Se enviaron $tarimasEnviadas tarima(s) a Pre-Enfriado correctamente.";
        if ($tarimasYaExistentes > 0) {
            $mensaje .= " $tarimasYaExistentes tarima(s) ya habían sido enviadas previamente.";
        }

        return redirect()->route('tarima.mostrar')->with('success', $mensaje);
    }

    //Metodo para editar los datos de salidas en las recepciones de una tarima, se filtra por tarima
    public function edit($id)
    {
        $preenfriado = Preenfriado::with(['detallesPreenfriado',
            'tarima.tarimaDetarec.detalle.recepcion.contrato.comercializadora'])
            ->find($id);
        
        // Verificar si ya fue completado (tiene hora_salida)
        $yaEditado = DetallePreenfriado::where('idpreenfrio', $id)
            ->whereNotNull('hora_salida')
            ->exists();
        return view('preenfriado.editar', compact('preenfriado', 'yaEditado'));
    }

   
    public function updatedetalle(Request $request)
    {
        $request->validate([
            'idpreenfriado.*' => 'required|integer',
            'iddetalle.*' => 'required|integer',
            'hora_entrada.*' => 'required',
            'temperatura_entrada.*' => 'required|numeric',
        ]);

        $idpreenfriado = $request->input('idpreenfriado');
        $iddetalle = $request->input('iddetalle');
        
        // Validar que el preenfriado no haya sido completado previamente (no tenga hora_salida)
        $detalleYaCompletado = DetallePreenfriado::where('idpreenfrio', $idpreenfriado[0])
            ->whereNotNull('hora_salida')
            ->first();
        
        if ($detalleYaCompletado) {
            return redirect()->back()->with('error', 'Este Pre-Enfriado ya fue completado anteriormente. Solo se puede editar una vez.');
        }
        
        $horaEntrada = $request->input('hora_entrada');
        $tempEntrada = $request->input('temperatura_entrada');
        $horaSalida = $request->input('hora_salida');
        $tempSalida = $request->input('temperatura_salida');

        foreach ($iddetalle as $index => $detalleId) {
            $horaEntradaObj = new \DateTime($horaEntrada[$index]);
            $horaSalidaObj = $horaSalida[$index] ? new \DateTime($horaSalida[$index]) : null;

            // Tiempo total en MINUTOS
            $tiempoEnMinutos = null;
            if ($horaSalidaObj) {
                $diff = $horaEntradaObj->diff($horaSalidaObj);
                // días a minutos + horas a minutos + minutos
                $tiempoEnMinutos = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
            }

            DetallePreenfriado::updateOrCreate(
                [
                    'idpreenfrio' => $idpreenfriado[$index],
                    'iddetalle' => $detalleId
                ],
                [
                    'hora_entrada' => $horaEntrada[$index],
                    'temperatura_entrada' => $tempEntrada[$index],
                    'hora_salida' => $horaSalida[$index] ?? null,
                    'temperatura_salida' => $tempSalida[$index] ?? null,
                    'tiempototal' => $tiempoEnMinutos,
                ]
            );
        }

        // --- DECIDIR SIGUIENTE PASO: CONSERVACIÓN O CRUCE DE ANDÉN ---
        $preenfriado = Preenfriado::find($idpreenfriado[0]);
        if ($preenfriado && $horaSalida[0]) {
            // Solo si se completó el preenfriado (tiene hora_salida)
            
            // Verificar si ya fue enviado a conservación o cruce de andén
            $conservacion_existente = Conservacion::where('idtarima', $preenfriado->idtarima)->first();
            $cruceAnden_existente = \App\Models\CruceAnden::where('idtarima', $preenfriado->idtarima)->first();
            
            if ($conservacion_existente || $cruceAnden_existente) {
                return redirect()->route('enfrio.mostrar')
                    ->with('success', 'Pre-Enfriado completado.');
            }
            
            // Redirigir a una vista para elegir: Conservación o Cruce de Andén
            return redirect()->back()
                ->with('success', 'Pre-Enfriado completado. Elija el siguiente destino.');
        }
        
        // Si no se completó (sin hora_salida), solo guardar
        if ($preenfriado && !$horaSalida[0]) {
            return redirect()->route('enfrio.mostrar')
                ->with('success', 'Horas de entrada guardadas. Complete las horas de salida más tarde.');
        }

        return redirect()->route('enfrio.mostrar')->with('success', 'Pre-Enfriado actualizado correctamente.');
    }


    /**
     * Mostrar vista para elegir destino después de preenfriado
     * - Filtra cámaras según:
     *   1) Coolers asignados al usuario en sesión (usuario_cooler.estatus = activo)
     *   2) Cooler del contrato de la comercializadora de la recepción de la tarima
     */
   public function elegirDestino($idTarima)
    {
        $tarima = Tarima::with([
            'tarimaDetarec.detalle.recepcion.contrato.comercializadora'
        ])->findOrFail($idTarima);

        $user = auth()->user();

        // Coolers asignados al usuario (pivot usuario_cooler estatus=activo)
        $coolersUsuarioIds = $user->coolers()
            ->wherePivot('estatus', 'activo')
            ->pluck('cooler.id');

        // Cooler del contrato de la comercializadora (tomo el primero de la tarima)
        $primerDetalleTarima = $tarima->tarimaDetarec->first();
        $contrato            = optional(optional(optional($primerDetalleTarima)->detalle)->recepcion)->contrato;
        $coolerContratoId    = $contrato ? $contrato->idcooler : null;

        // Si no hay coolerContratoId o el usuario no tiene coolers asignados, devolvemos colecciones vacías
        if (!$coolerContratoId || $coolersUsuarioIds->isEmpty()) {
            $camarasConservacion = collect();
            $camarasCruceAnden   = collect();
        } else {
            // CONSERVACIÓN en el cooler del contrato y asignado al usuario
            $camarasConservacion = Camara::whereIn('tipo', ['CONSERVACIÓN', 'CONSERVACION'])
                ->where('idcooler', $coolerContratoId)
                ->whereIn('idcooler', $coolersUsuarioIds)
                ->where('estatus','activo')
                ->get();

            // CRUCE DE ANDÉN en el cooler del contrato y asignado al usuario
            $camarasCruceAnden = Camara::whereIn('tipo', ['CRUCE DE ANDÉN', 'CRUCE DE ANDEN'])
                ->where('idcooler', $coolerContratoId)
                ->whereIn('idcooler', $coolersUsuarioIds)
                ->where('estatus','activo')
                ->get();
        }
        //dd($coolersUsuarioIds, $coolerContratoId);
        return view('preenfriado.elegir_destino', compact('tarima', 'camarasConservacion', 'camarasCruceAnden'));
    }

    /**
     * Procesar la elección de destino
     */
    public function procesarDestino(Request $request, $idTarima)
    {
        
        $request->validate([
            'destino' => 'required|in:conservacion,cruce_anden',
            'idcamara' => 'required|exists:camara,id',
        ]);

        if ($request->destino === 'conservacion') {
            // Crear conservación
            $conservacion = Conservacion::create([
                'idtarima' => $idTarima,
                'idcamara' => $request->idcamara,
            ]);

            // Actualizar ubicación de la tarima
            Tarima::where('id', $idTarima)->update(['ubicacion' => 'conservacion']);

            // Actualizar estatus de los detalles
            $tarimas = TarimaDetarec::with('detalle')->where('idtarima', $idTarima)->get();
            foreach ($tarimas as $tarima) {
                if ($tarima->detalle && $tarima->detalle->estatus === 'preenfriado') {
                    $tarima->detalle->estatus = 'conservacion';
                    $tarima->detalle->save();

                    if ($tarima->detalle->recepcion) {
                        $tarima->detalle->recepcion->estatus = 'EN CONSERVACIÓN';
                        $tarima->detalle->recepcion->save();
                    }
                }
            }

            return redirect()->route('conservacion.editar', $idTarima)
                ->with('success', 'Tarima enviada a Conservación. Registre las horas de salida.')
                ->with('destino', 'conservacion'); // Agregar destino a la sesión
        } else {
            // Crear cruce de andén
            $cruceAnden = \App\Models\CruceAnden::create([
                'idtarima' => $idTarima,
                'idcamara' => $request->idcamara,
            ]);

            // Actualizar ubicación de la tarima
            Tarima::where('id', $idTarima)->update(['ubicacion' => 'cruce_anden']);

            // Determinar hora de entrada al cruce de andén
            $horaEntradaCruce = now();

            $preenfriado = Preenfriado::with('detallesPreenfriado')
                ->where('idtarima', $idTarima)
                ->first();

            if ($preenfriado && $preenfriado->detallesPreenfriado->isNotEmpty()) {
                $ultimaSalida = $preenfriado->detallesPreenfriado->max('hora_salida');
                if ($ultimaSalida) {
                    $horaEntradaCruce = $ultimaSalida;
                }
            }

            $temperaturaEntrada = 0; // Puedes ajustar esto según tu lógica

            // Actualizar estatus de los detalles y crear registros en detalle_cruce_anden
            $tarimas = TarimaDetarec::with('detalle')->where('idtarima', $idTarima)->get();
            foreach ($tarimas as $tarima) {
                if ($tarima->detalle) {
                    $tarima->detalle->estatus = 'cruce_anden';
                    $tarima->detalle->save();

                    // Crear registro en detalle_cruce_anden con hora_entrada y temperatura_entrada
                    \App\Models\DetalleCruceAnden::create([
                        'idcruce_anden' => $cruceAnden->id,
                        'iddetalletarima' => $tarima->id,
                        'iddetalle' => $tarima->detalle->id,
                        'hora_entrada' => $horaEntradaCruce,
                        'temperatura_entrada' => $temperaturaEntrada,
                    ]);

                    if ($tarima->detalle->recepcion) {
                        $tarima->detalle->recepcion->estatus = 'EN CRUCE DE ANDÉN';
                        $tarima->detalle->recepcion->save();
                    }
                }
            }

            return redirect()->route('cruce_anden.editar', $cruceAnden->id)
                ->with('success', 'Tarima enviada a Cruce de Andén. Complete las horas de salida.')
                ->with('destino', 'cruce_anden'); // Agregar destino a la sesión
        }
    }
    public function show($id)
    {
        $preenfriado = Preenfriado::with(['detallesPreenfriado.tarimaDetarec',
            'tarima.tarimaDetarec.detalle.recepcion.contrato.comercializadora'])
            ->find($id);
        return view('preenfriado.mostrarId', compact('preenfriado'));
    }

    public function destroy(Request $request, $id)
    {
        // Validar observaciones obligatorias
        $request->validate([
            'observaciones' => 'required|string|max:500',
        ], [
            'observaciones.required' => 'Las observaciones son obligatorias para eliminar un preenfriado.',
            'observaciones.max' => 'Las observaciones no pueden exceder los 500 caracteres.',
        ]);

        $preenfriado = Preenfriado::find($id);
        
        if (!$preenfriado) {
            return redirect()->route('enfrio.mostrar')->with('error', 'Preenfriado no encontrado.');
        }

        // Guardar observaciones antes de eliminar
        $preenfriado->observaciones = $request->observaciones;
        $preenfriado->save();
        
        $preenfriado->delete();

        return redirect()->route('enfrio.mostrar')->with('success', 'Preenfriado eliminado exitosamente.');
    }

    public function verPdf($id)
    {
        try {
            $preenfriado = Preenfriado::with(['detallesPreenfriado.tarimaDetarec',
                'tarima.tarimaDetarec.detalle.recepcion.contrato.comercializadora',
                'tarima.tarimaDetarec.detalle.fruta',
                'tarima.tarimaDetarec.detalle.presentacion',
                'tarima.tarimaDetarec.detalle.variedad',
                'camara.cooler',
                'detallesPreenfriado.detalleRecepcion'
            ])->findOrFail($id);

            $pdf = Pdf::loadView('preenfriado.ver_pdf', compact('preenfriado'));
            return $pdf->stream('preenfriado_' . $id . '.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar PDF: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar vista para agregar firmas
     */
    public function firmas($id)
    {
        $preenfriado = Preenfriado::findOrFail($id);
        return view('preenfriado.firmas', compact('preenfriado'));
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
            $preenfriado = Preenfriado::findOrFail($id);
            
            $preenfriado->update([
                'nombre_responsable1' => $request->nombre_responsable1,
                'nombre_responsable2' => $request->nombre_responsable2,
                'firma_responsable1' => $request->firma_responsable1,
                'firma_responsable2' => $request->firma_responsable2,
                'nota_firmas' => $request->nota_firmas,
            ]);

            return redirect()->route('enfrio.mostrarid', $preenfriado)
                ->with('success', 'Firmas guardadas correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al guardar las firmas: ' . $e->getMessage());
        }
    }
}
