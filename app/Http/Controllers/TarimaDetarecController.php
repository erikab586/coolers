<?php

namespace App\Http\Controllers;
use App\Models\TarimaDetarec;
use App\Models\Tarima; 
use App\Models\DetalleRecepcion;
use App\Models\Recepcion;
use App\Models\TipoPallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TarimaDetarecController extends Controller
{
    /**
     * Mostrar reporte de asignaciones de tarimas
     */
    public function index()
    {
        $asignaciones = TarimaDetarec::with([
            'tarima',
            'detalle.recepcion.contrato.comercializadora',
            'detalle.fruta',
            'detalle.variedad',
            'detalle.presentacion',
            'tipopallet'
        ])
        ->orderBy('created_at', 'desc')
        ->get();

        return view('asignartarima.mostrar', compact('asignaciones'));
    }

  /*  public function create()
    {
        $tarimas = Tarima::where('estatus','disponible')->get();
        $pallets = TipoPallet::all();
        $detalles = DetalleRecepcion::with(['recepcion','fruta','variedad'])
        ->where('pendientes','>',0)
        ->orderBy('created_at','desc')
        ->get();

        //return $detalles;
        return view('asignartarima.crear', compact('tarimas','pallets','detalles'));
    }*/

     public function create(Request $request)
    {
        $tarimas = Tarima::where('estatus', 'disponible')->get();
        $pallets = TipoPallet::all();

        $idContrato = $request->input('idcontrato'); // viene de ?idcontrato=...

        $detallesQuery = DetalleRecepcion::with(['recepcion','fruta','variedad'])
            ->where('pendientes', '>', 0)
            ->orderBy('created_at', 'desc');

        // Si se envió idcontrato, filtramos solo recepciones de ese contrato
        if ($idContrato) {
            $detallesQuery->whereHas('recepcion', function ($q) use ($idContrato) {
                $q->where('idcontrato', $idContrato);
            });
        }

        $detalles = $detallesQuery->get();

        //return $detalles;
        return view('asignartarima.crear', compact('tarimas', 'pallets', 'detalles', 'idContrato'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'idtarima' => 'required|exists:tarimas,id',
            'idtipopallet' => 'required|array',
            'iddetalle' => 'required|array',
            'cantidad_asignada' => 'required|array',
        ]);


        $idTarima = $request->input('idtarima');
        $idTipoPallet = $request->input('idtipopallet')[0] ?? null;
        $detalles = $request->input('iddetalle');
        $cantidadesAsignadas = $request->input('cantidad_asignada');
        $completado= $request->input('completar_tarima') ?? 0;

        $tarima = Tarima::findOrFail($idTarima);

        // VALIDACIÓN PREVIA 1: Validar límite por tipo de fruta ANTES de procesar
        foreach ($detalles as $index => $idDetalle) {
            $cantidadSolicitada = (int) ($cantidadesAsignadas[$index] ?? 0);
            if ($cantidadSolicitada <= 0) continue;
            
            $detalle = DetalleRecepcion::findOrFail($idDetalle);
            $nombreFruta = strtolower($detalle->fruta->nombrefruta ?? '');
            $capacidadFruta = ($nombreFruta === 'arandanos' || $nombreFruta === 'arándanos' || $nombreFruta === 'arandano') ? 120 : 240;
            
            if ($cantidadSolicitada > $capacidadFruta) {
                return redirect()->back()->withErrors(
                    "No puedes asignar {$cantidadSolicitada} cajas de {$detalle->fruta->nombrefruta} en una tarima. "
                    . "El límite máximo para esta fruta es {$capacidadFruta} cajas por tarima."
                )->withInput();
            }
        }

        // VALIDACIÓN PREVIA 2: Calcular el total que se va a asignar
        $totalAAsignar = 0;
        foreach ($cantidadesAsignadas as $cant) {
            $totalAAsignar += (int) ($cant ?? 0);
        }

        // Validar que el total no exceda el disponible de la tarima
        $cantidadActualTarima = $tarima->cantidadActual();
        $disponibleTarima = $tarima->capacidad - $cantidadActualTarima;
       
        if ($totalAAsignar > $disponibleTarima) {
            return redirect()->back()->withErrors(
                "El total a asignar ({$totalAAsignar} cajas) excede el espacio disponible de la tarima ({$disponibleTarima} cajas). "
                . "Capacidad: {$tarima->capacidad} | En uso: {$cantidadActualTarima}"
            )->withInput();
        }


        // Recorremos cada asignacion parcial
        foreach ($detalles as $index => $idDetalle) {
        $cantidad = (int) ($cantidadesAsignadas[$index] ?? 0);
        if ($cantidad <= 0) continue; // ignorar ceros


        $detalle = DetalleRecepcion::findOrFail($idDetalle);


        // Validar contra pendientes del detalle
        if ($cantidad > $detalle->pendientes) {
            return redirect()->back()->withErrors(
                "Cantidad asignada ({$cantidad}) excede pendientes en la recepción ({$detalle->pendientes})."
            )->withInput();
        }

        // La validación de capacidad por fruta ya se hizo antes del loop
        // Este código ya no es necesario aquí porque rechazamos antes


        // generar codigo: Lote:{iddetalle}-{idtarima}
        $codigo = 'Lote:' . $detalle->id . '-' . $tarima->id;

        // Obtener datos adicionales del detalle de recepción
        $folio = $detalle->recepcion->folio ?? null;
        $idFruta = $detalle->idfruta ?? null;
        $idPresentacion = $detalle->idpresentacion ?? null;
        $idVariedad = $detalle->idvariedad ?? null;
        $idComercializadora = $detalle->recepcion->contrato->idcomercializadora ?? null;

        \Log::info('Creando TarimaDetarec', [
            'idtarima' => $tarima->id,
            'iddetalle' => $detalle->id,
            'cantidad_asignada' => $cantidad,
            'idtipopallet' => $idTipoPallet,
            'codigo' => $codigo,
            'folio' => $folio,
            'idfruta' => $idFruta,
            'idpresentacion' => $idPresentacion,
            'idvariedad' => $idVariedad,
            'idcomercializadora' => $idComercializadora,
            'tarima_capacidad' => $tarima->capacidad,
            'tarima_actual' => $tarima->cantidadActual(),
            'tarima_disponible' => $disponibleTarima
        ]);

        // crear asignación con datos desnormalizados
        $tarimaDetarec = TarimaDetarec::create([
            'idtarima' => $tarima->id,
            'iddetalle' => $detalle->id,
            'cantidad_asignada' => $cantidad,
            'cantidadcarga' => $cantidad, // Inicialmente igual a cantidad_asignada
            'idtipopallet' => $idTipoPallet,
            'codigo' => $codigo,
            'estatus' => 'completo',
            'folio' => $folio,
            'idfruta' => $idFruta,
            'idpresentacion' => $idPresentacion,
            'idvariedad' => $idVariedad,
            'idcomercializadora' => $idComercializadora,
        ]);

        \Log::info('TarimaDetarec creado exitosamente', [
            'tarima_detarec_id' => $tarimaDetarec->id,
            'cantidad' => $cantidad
        ]);

        // actualizar detalle recepcion: restar pendientes y si llega a cero cambiar estatus
        $detalle->decrement('pendientes', $cantidad);
        $detalle->refresh();
        
        if ($detalle->pendientes <= 0) {
            $detalle->pendientes = 0;
            $detalle->estatus = 'tarima'; // Cambiar a 'tarima' cuando todas las cajas fueron asignadas
        }
        $detalle->save();
        } // Fin del foreach

        // ACTUALIZAR ESTATUS DE LA RECEPCIÓN según el estado de sus detalles
        // Obtener el primer detalle para acceder a la recepción
        $primerDetalle = DetalleRecepcion::find($detalles[0]);
        if ($primerDetalle && $primerDetalle->recepcion) {
            $recepcion = $primerDetalle->recepcion;
            
            // Contar detalles de esta recepción
            $totalDetalles = $recepcion->detalles()->count();
            $detallesCompletados = $recepcion->detalles()->where('pendientes', 0)->count();
            
            if ($detallesCompletados == $totalDetalles && $totalDetalles > 0) {
                // Todos los detalles tienen pendientes = 0
                $recepcion->estatus = 'TARIMA';
            } else if ($detallesCompletados > 0) {
                // Algunos detalles completados pero no todos
                $recepcion->estatus = 'TARIMA';
            }
            // Si no hay detalles completados, mantiene su estatus actual
            
            $recepcion->save();
            
            \Log::info('Estatus de recepción actualizado', [
                'recepcion_id' => $recepcion->id,
                'folio' => $recepcion->folio,
                'nuevo_estatus' => $recepcion->estatus,
                'total_detalles' => $totalDetalles,
                'detalles_completados' => $detallesCompletados
            ]);
        }

        // ACTUALIZAR ESTATUS DE LA TARIMA (después de TODAS las asignaciones)
        $tarima->refresh();
        $cantidadFinalTarima = $tarima->cantidadActual();
        
        // Si el usuario marcó "completar tarima" O si llegó a la capacidad máxima
        if ($completado == 1 || $cantidadFinalTarima >= $tarima->capacidad) {
            $tarima->estatus = 'completo';
            $tarima->save();
            
            \Log::info('Tarima actualizada a completo', [
                'tarima_id' => $tarima->id,
                'tarima_codigo' => $tarima->codigo,
                'cantidad_final' => $cantidadFinalTarima,
                'capacidad' => $tarima->capacidad,
                'forzado_por_checkbox' => $completado == 1,
                'llego_a_capacidad' => $cantidadFinalTarima >= $tarima->capacidad
            ]);
        }

        return redirect()->back()->with('success','Tarima(s) cargada(s) correctamente.');
    }
    
}
