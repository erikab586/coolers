<?php

namespace App\Http\Controllers;

use App\Models\CruceAnden;
use App\Models\DetalleCruceAnden;
use App\Models\Camara;
use App\Models\Preenfriado;
use App\Models\DetalleRecepcion;
use App\Models\Tarima;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CruceAndenController extends Controller
{
    /**
     * Mostrar formulario para enviar tarima a Cruce de Andén
     */
    public function create($idTarima)
    {
        $tarima = Tarima::with([
            'tarimaDetarec.detalle.recepcion.contrato.comercializadora',
            'tarimaDetarec.detalle.fruta',
            'tarimaDetarec.detalle.presentacion',
            'tarimaDetarec.detalle.variedad'
        ])->findOrFail($idTarima);

        // Obtener cámaras de tipo CRUCE DE ANDÉN
        $camarasAnden = Camara::where('tipo', 'CRUCE DE ANDÉN')->get();

        return view('cruce_anden.crear', compact('tarima', 'camarasAnden'));
    }

    /**
     * Guardar tarima en Cruce de Andén
     */
    public function store(Request $request, $idTarima)
    {
        $request->validate([
            'respuesta' => 'required|string|in:Si,No',
            'idcamara'  => 'required|exists:camara,id',
        ]);

        $respuesta = strtolower($request->input('respuesta'));

        if ($respuesta === 'si') {
            // Validar que la tarima no haya sido enviada a cruce de andén previamente
            $cruceAndenExistente = CruceAnden::where('idtarima', $idTarima)->first();
            if ($cruceAndenExistente) {
                return redirect()->back()->with('error', 'Esta tarima ya fue enviada a Cruce de Andén anteriormente.');
            }

            // Crear el registro de Cruce de Andén
            $cruceAnden = CruceAnden::create([
                'idtarima' => $idTarima,
                'idcamara' => $request->idcamara,
            ]);

            // Actualizar la ubicación de la tarima
            Tarima::where('id', $idTarima)->update([
                'ubicacion' => 'cruce_anden'
            ]);

            // Actualizar estatus de los detalles a "cruce_anden"
            $tarimas = \App\Models\TarimaDetarec::with('detalle')
                ->where('idtarima', $idTarima)
                ->get();

            foreach ($tarimas as $tarima) {
                if ($tarima->detalle) {
                    $tarima->detalle->estatus = 'cruce_anden';
                    $tarima->detalle->save();

                    // Actualizar el estatus de la recepción
                    if ($tarima->detalle->recepcion) {
                        $tarima->detalle->recepcion->estatus = 'EN CRUCE DE ANDÉN';
                        $tarima->detalle->recepcion->save();
                    }
                }
            }

            return redirect()->route('cruce_anden.editar', $cruceAnden->id)
                ->with('success', 'Tarima enviada a Cruce de Andén. Ahora registre las horas de entrada.');
        } else {
            return redirect()->route('enfrio.mostrar')->with('info', 'No se envió la tarima a Cruce de Andén.');
        }
    }

    /**
     * Mostrar formulario para editar/completar detalles de Cruce de Andén
     */
    public function edit($id)
    {
        $cruceAnden = CruceAnden::with(['detallesCruceAnden.tarimaDetarec',        
            'detallesCruceAnden.detalleRecepcion', 
            'tarima.tarimaDetarec.detalle.recepcion.contrato.comercializadora'])
            ->find($id);

        // Verificar si ya fue completado (tiene hora_salida)
        $yaEditado = DetalleCruceAnden::where('idcruce_anden', $id)
            ->whereNotNull('hora_salida')
            ->exists();

        return view('cruce_anden.editar', compact('cruceAnden', 'yaEditado'));
    }

    /**
     * Actualizar detalles de Cruce de Andén (horas de entrada/salida)
     */
    public function updateDetalle(Request $request)
    {
        $request->validate([
            'idcruce_anden.*' => 'required|integer',
            'iddetalle.*' => 'required|integer',
            'iddetalletarima.*'   => 'required|integer',
            'hora_entrada.*' => 'required',
            'temperatura_entrada.*' => 'required|numeric',
        ]);

        $idCruceAnden = $request->input('idcruce_anden');
        $idDetalle = $request->input('iddetalle');
        $idDetalleTarima = $request->input('iddetalletarima');
        // Validar que el cruce de andén no haya sido completado previamente
        $detalleYaCompletado = DetalleCruceAnden::where('idcruce_anden', $idCruceAnden[0])
            ->whereNotNull('hora_salida')
            ->first();

        if ($detalleYaCompletado) {
            return redirect()->back()->with('error', 'Este Cruce de Andén ya fue completado anteriormente. Solo se puede editar una vez.');
        }

        $horaEntrada = $request->input('hora_entrada');
        $tempEntrada = $request->input('temperatura_entrada');
        $horaSalida = $request->input('hora_salida');
        $tempSalida = $request->input('temperatura_salida');

        foreach ($idDetalle as $index => $detalleId) {
            $horaEntradaObj = new \DateTime($horaEntrada[$index]);
            $horaSalidaObj  = $horaSalida[$index] ? new \DateTime($horaSalida[$index]) : null;

            $tiempoEnMinutos = null;
            if ($horaSalidaObj) {
                // diferencia en segundos
                $diferenciaSegundos = $horaSalidaObj->getTimestamp() - $horaEntradaObj->getTimestamp();

                // convertir a minutos (entero)
                $tiempoEnMinutos = intdiv($diferenciaSegundos, 60);

                // si es negativo, hay error en las horas capturadas
                if ($tiempoEnMinutos < 0) {
                    return redirect()->back()->withErrors(
                        'La hora de salida debe ser posterior a la hora de entrada.'
                    )->withInput();
                }
            }

            DetalleCruceAnden::updateOrCreate(
            [
                    'idcruce_anden' => $idCruceAnden[$index],
                    'iddetalle'     => $detalleId,
            ],
            [
            'hora_entrada'        => $horaEntrada[$index],
            'temperatura_entrada' => $tempEntrada[$index],
            'hora_salida'         => $horaSalida[$index] ?? null,
            'temperatura_salida'  => $tempSalida[$index] ?? null,
            'tiempototal'         => $tiempoEnMinutos,
            ]);
        }

        // Si se completó (tiene hora_salida), pasar automáticamente a conservación
        if ($horaSalida[0]) {
            $cruceAnden = CruceAnden::find($idCruceAnden[0]);
            
            // Crear conservación con la misma cámara
            $conservacion = \App\Models\Conservacion::create([
                'idtarima' => $cruceAnden->idtarima,
                'idcamara' => $cruceAnden->idcamara,
            ]);

            // Actualizar ubicación de la tarima
            Tarima::where('id', $cruceAnden->idtarima)->update(['ubicacion' => 'conservacion']);

            // Actualizar estatus de los detalles
            $tarimas = \App\Models\TarimaDetarec::with('detalle')->where('idtarima', $cruceAnden->idtarima)->get();
            foreach ($tarimas as $tarima) {
                if ($tarima->detalle && $tarima->detalle->estatus === 'cruce_anden') {
                    $tarima->detalle->estatus = 'conservacion';
                    $tarima->detalle->save();

                    if ($tarima->detalle->recepcion) {
                        $tarima->detalle->recepcion->estatus = 'EN CONSERVACIÓN';
                        $tarima->detalle->recepcion->save();
                    }
                }
            }

            return redirect()->route('conservacion.editar', $cruceAnden->idtarima)
                ->with('success', 'Cruce de Andén completado. Tarima enviada a Conservación. Registre las horas de salida.');
        }

        return redirect()->route('cruce_anden.mostrar')->with('success', 'Detalles de Cruce de Andén guardados correctamente.');
    }

    /**
     * Mostrar listado de tarimas en Cruce de Andén
     */
   /* public function index()
    {
        $user = auth()->user();
        
        // Filtrar cruce de andén según el rol del usuario
        $cruceAndenQuery = CruceAnden::with([
            'tarima.tarimaDetarec.detalle.recepcion.contrato.cooler',
            'camara'
        ]);

        if ($user->rol->nombrerol == 'Administrador') {
            // Mostrar todos los cruces de andén
        } elseif ($user->rol->nombrerol == 'Supervisor' || $user->rol->nombrerol == 'Operativo') {
            // Mostrar solo cruces de andén de recepciones creadas por el usuario y de coolers asignados
            $coolerIds = $user->coolers()->where('usuario_cooler.estatus', 'activo')->pluck('cooler.id');
            $cruceAndenQuery->whereHas('tarima.tarimaDetarec.detalle.recepcion', function($q) use ($coolerIds, $user) {
                $q->where('idusuario', $user->id)
                  ->whereHas('contrato', function($subQ) use ($coolerIds) {
                      $subQ->whereIn('idcooler', $coolerIds);
                  });
            });
        }

        $cruceAnden = $cruceAndenQuery->orderBy('id', 'desc')->get();

        return view('cruce_anden.mostrar', compact('cruceAnden'));
    }*/
    public function index()
    {
        $user = auth()->user();
        
        // Filtrar cruce de andén según el rol del usuario
        $cruceAndenQuery = CruceAnden::with([
            'tarima.tarimaDetarec.detalle.recepcion.contrato.cooler',
            'camara'
        ]);
    
        if ($user->rol->nombrerol == 'Administrador') {
            // Mostrar todos los cruces de andén
        } else {
            // Obtener los IDs de los coolers asignados al usuario
            $coolerIds = $user->coolers()
                             ->wherePivot('estatus', 'activo')
                             ->pluck('cooler.id');
            
            // Mostrar cruces de andén de los coolers asignados, sin filtrar por usuario
            $cruceAndenQuery->whereHas('tarima.tarimaDetarec.detalle.recepcion.contrato', function($q) use ($coolerIds) {
                $q->whereIn('idcooler', $coolerIds);
            });
        }
    
        $cruceAnden = $cruceAndenQuery->orderBy('id', 'desc')->get();
    
        return view('cruce_anden.mostrar', compact('cruceAnden'));
    }

    /**
     * Mostrar detalle de un cruce de andén específico
     */
    public function show($id)
    {
        $cruceAnden = CruceAnden::with(['detallesCruceAnden.tarimaDetarec',
            'tarima.tarimaDetarec.detalle.recepcion.contrato.comercializadora',
            'tarima.tarimaDetarec.detalle.fruta',
            'tarima.tarimaDetarec.detalle.presentacion',
            'tarima.tarimaDetarec.detalle.variedad',
            'camara.cooler',
            'detallesCruceAnden'
        ])->findOrFail($id);

        return view('cruce_anden.mostrarId', compact('cruceAnden'));
    }

    /**
     * Eliminar un cruce de andén
     */
    public function destroy(Request $request, $id)
    {
        // Validar observaciones obligatorias
        $request->validate([
            'observaciones' => 'required|string|max:500',
        ], [
            'observaciones.required' => 'Las observaciones son obligatorias para eliminar un Cruce de Andén.',
            'observaciones.max' => 'Las observaciones no pueden exceder los 500 caracteres.',
        ]);

        $cruceAnden = CruceAnden::findOrFail($id);
        
        // Verificar si ya fue completado
        $completado = $cruceAnden->detallesCruceAnden()->whereNotNull('hora_salida')->exists();
        
        if ($completado) {
            return redirect()->route('cruce_anden.mostrar')->with('error', 'No se puede eliminar un Cruce de Andén que ya fue completado.');
        }

        // Guardar observaciones antes de eliminar
        $cruceAnden->observaciones = $request->observaciones;
        $cruceAnden->save();

        // Eliminar los detalles primero
        $cruceAnden->detallesCruceAnden()->delete();
        
        // Eliminar el cruce de andén
        $cruceAnden->delete();

        return redirect()->route('cruce_anden.mostrar')->with('success', 'Cruce de Andén eliminado correctamente.');
    }

    public function verPdf($id)
    {
        try {
            $cruceAnden = CruceAnden::with([
                'tarima.tarimaDetarec.detalle.recepcion.contrato.comercializadora',
                'tarima.tarimaDetarec.detalle.fruta',
                'tarima.tarimaDetarec.detalle.presentacion',
                'tarima.tarimaDetarec.detalle.variedad',
                'camara.cooler',
                'detallesCruceAnden.tarimaDetarec',
                'detallesCruceAnden.detalleRecepcion',
                'detallesCruceAnden'
            ])->findOrFail($id);

            $pdf = Pdf::loadView('cruce_anden.ver_pdf', compact('cruceAnden'));
            return $pdf->stream('cruce_anden_' . $id . '.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar PDF: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar vista para agregar firmas
     */
    public function firmas($id)
    {
        $cruceAnden = CruceAnden::findOrFail($id);
        return view('cruce_anden.firmas', compact('cruceAnden'));
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
            $cruceAnden = CruceAnden::findOrFail($id);
            
            $cruceAnden->update([
                'nombre_responsable1' => $request->nombre_responsable1,
                'nombre_responsable2' => $request->nombre_responsable2,
                'firma_responsable1' => $request->firma_responsable1,
                'firma_responsable2' => $request->firma_responsable2,
                'nota_firmas' => $request->nota_firmas,
            ]);

            return redirect()->route('cruce_anden.mostrarid', $cruceAnden)
                ->with('success', 'Firmas guardadas correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al guardar las firmas: ' . $e->getMessage());
        }
    }
}
