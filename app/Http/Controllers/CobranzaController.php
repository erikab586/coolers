<?php

namespace App\Http\Controllers;

use App\Models\Cobranza;
use App\Models\Comercializadora;
use App\Models\Recepcion;
use App\Models\Contrato;
use App\Models\DetalleRecepcion;
use App\Models\Tarima;
use App\Models\Preenfriado;
use App\Models\Conservacion;
use App\Models\CruceAnden;
use App\Models\TarimaDetaRec;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class CobranzaController extends Controller
{
    /**
    * Mostrar todas las comercializadoras con contratos
    */
    public function index()
    {
        $user = auth()->user();
        $query = Contrato::with(['comercializadora', 'users', 'cooler'])
            ->where('estatus', 'activo');
        if ($user->rol->nombrerol == 'Administrador') {
        } elseif ($user->rol->nombrerol == 'Supervisor' || $user->rol->nombrerol == 'Operativo') {
            $coolerIds = $user->coolers()->where('usuario_cooler.estatus', 'activo')->pluck('cooler.id');
            $query->whereIn('idcooler', $coolerIds);
        }

        $contratos = $query->orderBy('id', 'desc')->get();

        return view('cobranza.index', compact('contratos'));
    }
    /**
    * Listar recepciones con estatus finalizado
    */
   /* public function cobrarPendiente($idcontrato)
    {
        $recepciones = Recepcion::with([
            'detalles.tarimaDetalle' => function($query) {
                $query->with('tarima');
            },
            'contrato.comercializadora'
        ])
        ->where('idcontrato', $idcontrato)
        ->where('estatus', 'FINALIZADO')
        ->get();

        // Get all unique pallets from all receptions
        $tarimas = collect();
        foreach ($recepciones as $recepcion) {
            foreach ($recepcion->detalles as $detalle) {
                if ($detalle->tarimaDetalle && $detalle->tarimaDetalle->tarima) {
                    $tarimas->push($detalle->tarimaDetalle->tarima);
                }
            }
        }

        // Remove duplicates in case a pallet is in multiple reception details
        $tarimas = $tarimas->unique('id');

        return view('cobranza.pendiente', [
            'tarimas' => $tarimas,
            'recepciones' => $recepciones
        ]); //return $tarimas;
    }*/
  /* public function cobrarPendiente($idContrato)
{
    $contrato = Contrato::with('comercializadora')->findOrFail($idContrato);

    $recepciones = Recepcion::where('idcontrato', $idContrato)
        ->with(['detalles' => function($query) {
            $query->with([
                'tarimaDetalle' => function($q) {
                    $q->whereHas('tarima', function($q2) {
                        $q2->where('ubicacion', 'finalizado');
                    })
                    ->with(['tarima']); // Solo cargamos tarima aquí
                },
                'fruta', 
                'variedad', 
                'presentacion',
                'detalleCruceAnden',  // Movido al nivel de detalles
                'detalleConservacion', // Movido al nivel de detalles
                'detallePreenfriado'   // Movido al nivel de detalles
            ]);
        }])
        ->get();

    $tarimasPendientes = collect();

    foreach ($recepciones as $recepcion) {
        foreach ($recepcion->detalles as $detalle) {
            if ($detalle->tarimaDetalle) {
                $tarima = $detalle->tarimaDetalle;
                $formatTime = function($seconds) {
                    if (!$seconds) return '00:00';
                    $hours = floor($seconds / 3600);
                    $minutes = floor(($seconds % 3600) / 60);
                    return sprintf('%02d:%02d', $hours, $minutes);
                };
                $tarimasPendientes->push([
                    'idtarima' => $tarima->id,
                    'folio' => $recepcion->folio,
                    'fruta' => $detalle->fruta->nombrefruta,
                    'variedad' => $detalle->variedad->tipofruta,
                    'presentacion' => $detalle->presentacion->nombrepresentacion,
                    'cantidad' => $detalle->cantidad,
                    'cantidad_preenfriado' => $detalle->detallePreenfriado ? $detalle->detallePreenfriado->tiempototal : 0,
                    'cantidad_cruce_anden' => $detalle->detalleCruceAnden ? $detalle->detalleCruceAnden->tiempototal : 0,
                    'cantidad_conservacion' => $detalle->detalleConservacion ? $detalle->detalleConservacion->tiempototal : 0,
                    'fecha_ingreso' => $tarima->updated_at
                ]);
            }
        }
    }

    return view('cobranza.pendiente', [
        'tarimas' => $tarimasPendientes,
        'contrato' => $contrato
    ]);
}*/
    public function cobrarPendiente($idContrato)
    {
        $contrato = Contrato::with('comercializadora')->findOrFail($idContrato);

        // Obtener recepciones finalizadas
        $recepciones = Recepcion::where('idcontrato', $idContrato)
            ->with(['detalles' => function($query) {
                $query->with([
                    'fruta',
                    'variedad',
                    'presentacion',
                    'tarimaDetalle' => function($q) {
                        $q->whereHas('tarima', function($q2) {
                            $q2->where('ubicacion', 'finalizado');
                        })->with('tarima');
                    },
                    'detalleConservacion' => function($q) {
                        $q->with('conservacion');
                    },
                    'detallePreenfriado',
                    'detalleCruceAnden'
                ]);
            }])
            ->get();

        $tarimasPendientes = collect();

        foreach ($recepciones as $recepcion) {
            foreach ($recepcion->detalles as $detalle) {

                // Si ya existe cobranza para ESTE detalle, no procesamos ninguna tarima de él
                $cobranzaExistente = Cobranza::where('iddetallerecepcion', $detalle->id)->exists();
                if ($cobranzaExistente) {
                    continue;
                }

                // 🔥 IMPORTANTE: recorrer todas las tarimas del detalle
                foreach ($detalle->tarimaDetalle as $tarimaDetalle) {

                    $tarima = $tarimaDetalle->tarima;

                    if (!$tarima) {
                        continue; // por si acaso
                    }

                    // Calcular tiempos
                    $tiempoPreenfriado = $this->calcularTiempoPreenfriado($tarima->id);
                    $tiempoConservacion = $this->calcularTiempoConservacionPorTarima($tarima->id);
                    $tiempoAnden = $this->calcularTiempoAnden($tarima->id);
                    $tiempoTotal = $tiempoPreenfriado + $tiempoConservacion + $tiempoAnden;

                    // Obtener montos
                    $montoPreenfriado = $this->getMontoPreenfriado(
                        $recepcion->idcontrato,
                        $detalle->idfruta,
                        $detalle->idvariedad,
                        $detalle->idpresentacion
                    );

                    // Agregar tarima a la colección
                    $tarimasPendientes->push([
                        'id' => $tarima->id,
                        'folio' => $recepcion->folio,
                        'fecha_ingreso'=> $tarima->created_at,
                        'fecha_salida' => $tarima->updated_at,
                        'fruta' => $detalle->fruta->nombrefruta ?? 'N/A',
                        'variedad' => $detalle->variedad->tipofruta ?? 'N/A',
                        'presentacion' => $detalle->presentacion->nombrepresentacion ?? 'N/A',
                        'cantidad' => $tarimaDetalle->cantidadcarga,
                        'tiempo_preenfriado' => $tiempoPreenfriado,
                        'tiempo_conservacion' => $tiempoConservacion,
                        'tiempo_anden' => $tiempoAnden,
                        'tiempo_total' => $tiempoTotal,
                        'monto_preenfriado' => $montoPreenfriado,
                        'monto_estimado' => $this->calcularMontoEstimado(
                            $recepcion->contrato,
                            $tiempoTotal,
                            $detalle->cantidad,
                            $montoPreenfriado,
                            $detalle->idfruta,
                            $detalle->idvariedad,
                            $detalle->idpresentacion
                        ),
                        'id_detalle' => $detalle->id
                    ]);
                }
            }
        }

       // return $tarimasPendientes;
       return view('cobranza.pendiente', [ 'tarimas' => $tarimasPendientes, 'contrato' => $contrato]);
    }


    private function calcularTiempoPreenfriado($idtarima)
    {
        $preenfriado = Preenfriado::with('detallesPreenfriado')
            ->where('idtarima', $idtarima)
            ->first();

        if (!$preenfriado || $preenfriado->detallesPreenfriado->isEmpty()) {
            return 0;
        }

        $minEntrada = $preenfriado->detallesPreenfriado->min('hora_entrada');
        $maxSalida  = $preenfriado->detallesPreenfriado->max('hora_salida');

        if (!$minEntrada || !$maxSalida) {
            return 0;
        }

        $inicio = Carbon::parse($minEntrada);
        $fin    = Carbon::parse($maxSalida);

        if ($fin->lessThanOrEqualTo($inicio)) {
            return 0;
        }

        $minutos = $inicio->diffInMinutes($fin);

        // Devolver en horas (float)
        return $minutos > 0 ? $minutos / 60 : 0;
    }

    /**
     * Calcular tiempo de conservación en horas para un detalle específico
     */
    private function calcularTiempoConservacion($detalleConservacion)
    {
        // Retornar tiempo total en horas (ya guardado en horas) para un solo detalle
        return $detalleConservacion->tiempototal ?? 0;
    }

    /**
     * Calcular tiempo total de conservación en horas por tarima
     * Usa la diferencia entre la primera hora_entrada y la última hora_salida
     * de todos los DetalleConservacion asociados a las conservaciones de la tarima.
     */
    private function calcularTiempoConservacionPorTarima($idtarima)
    {
        $conservaciones = Conservacion::with('detallesConservacion')
            ->where('idtarima', $idtarima)
            ->get();

        if ($conservaciones->isEmpty()) {
            return 0;
        }

        $minEntrada = null;
        $maxSalida  = null;

        foreach ($conservaciones as $cons) {
            foreach ($cons->detallesConservacion as $detalle) {
                if ($detalle->hora_entrada && ($minEntrada === null || $detalle->hora_entrada < $minEntrada)) {
                    $minEntrada = $detalle->hora_entrada;
                }
                if ($detalle->hora_salida && ($maxSalida === null || $detalle->hora_salida > $maxSalida)) {
                    $maxSalida = $detalle->hora_salida;
                }
            }
        }

        if (!$minEntrada || !$maxSalida) {
            return 0;
        }

        $inicio = Carbon::parse($minEntrada);
        $fin    = Carbon::parse($maxSalida);

        if ($fin->lessThanOrEqualTo($inicio)) {
            return 0;
        }

        $minutos = $inicio->diffInMinutes($fin);

        // Devolver en horas (float)
        return $minutos > 0 ? $minutos / 60 : 0;
    }

    /**
     * Calcular tiempo de cruce de andén en horas (por tarima)
     * Usa la diferencia entre la primera hora_entrada y la última hora_salida
     * de los registros de DetalleCruceAnden asociados a la tarima.
     */
    private function calcularTiempoAnden($idtarima)
    {
        $cruceAnden = CruceAnden::with('detallesCruceAnden')
            ->where('idtarima', $idtarima)
            ->first();
        
        if (!$cruceAnden || $cruceAnden->detallesCruceAnden->isEmpty()) {
            return 0;
        }

        $minEntrada = $cruceAnden->detallesCruceAnden->min('hora_entrada');
        $maxSalida  = $cruceAnden->detallesCruceAnden->max('hora_salida');

        if (!$minEntrada || !$maxSalida) {
            return 0;
        }

        $inicio = Carbon::parse($minEntrada);
        $fin    = Carbon::parse($maxSalida);

        if ($fin->lessThanOrEqualTo($inicio)) {
            return 0;
        }

        $minutos = $inicio->diffInMinutes($fin);

        // Devolver en horas (float)
        return $minutos > 0 ? $minutos / 60 : 0;
    }

    /**
     * Obtener monto de preenfriado del contrato
     */
    private function getMontoPreenfriado($idContrato, $idFruta, $idVariedad, $idPresentacion)
    {
        $detalle = \App\Models\DetalleContrato::where('idcontrato', $idContrato)
            ->where('idfruta', $idFruta)
            ->where('idvariedad', $idVariedad)
            ->where('idpresentacion', $idPresentacion)
            ->where(function($query) {
                $query->where('tiposervicio', 'preenfrio');
            })
            ->first();
            
        return $detalle ? $detalle->monto : 0;
    }

    /**
     * Obtener monto de cruce de andén del contrato
     */
    private function getMontoAnden($idContrato, $idFruta, $idVariedad, $idPresentacion)
    {
        $detalle = \App\Models\DetalleContrato::where('idcontrato', $idContrato)
            ->where('idfruta', $idFruta)
            ->where('idvariedad', $idVariedad)
            ->where('idpresentacion', $idPresentacion)
            ->where('tiposervicio', 'anden')
            ->first();

        return $detalle ? $detalle->monto : 0;
    }

    /**
     * Obtener moneda del contrato
     */
    private function getMonedaContrato($idContrato, $idFruta, $idVariedad, $idPresentacion)
    {
        $detalle = \App\Models\DetalleContrato::where('idcontrato', $idContrato)
            ->where('idfruta', $idFruta)
            ->where('idvariedad', $idVariedad)
            ->where('idpresentacion', $idPresentacion)
            ->first();

        return $detalle ? ($detalle->moneda ?? 'MXN') : 'MXN';
    }

    

    /**
     * Obtener monto de conservación contratada del contrato
     */
    private function getMontoConservacionContratada($idContrato, $idFruta, $idVariedad, $idPresentacion)
    {
        $detalle = \App\Models\DetalleContrato::where('idcontrato', $idContrato)
            ->where('idfruta', $idFruta)
            ->where('idvariedad', $idVariedad)
            ->where('idpresentacion', $idPresentacion)
            ->where('tiposervicio', 'conservacion')
            ->first();

        return $detalle ? $detalle->monto : 0;
    }

    /**
     * Verificar si el contrato tiene servicio de conservación
     */
    private function tieneServicioConservacion($idContrato, $idFruta, $idVariedad, $idPresentacion)
    {
        return \App\Models\DetalleContrato::where('idcontrato', $idContrato)
            ->where('idfruta', $idFruta)
            ->where('idvariedad', $idVariedad)
            ->where('idpresentacion', $idPresentacion)
            ->where('tiposervicio', 'conservacion')
            ->exists();
    }
    /**
     * Verificar si el contrato tiene servicio de cruce de andén
     */
    private function tieneServicioCruceAnden($idContrato, $idFruta, $idVariedad, $idPresentacion)
    {
        return \App\Models\DetalleContrato::where('idcontrato', $idContrato)
            ->where('idfruta', $idFruta)
            ->where('idvariedad', $idVariedad)
            ->where('idpresentacion', $idPresentacion)
            ->where('tiposervicio', 'anden')
            ->exists();
    }
    /**
     * Verificar si el contrato tiene servicio de cruce de andén
     */
    private function tieneServicioPreenfriado($idContrato, $idFruta, $idVariedad, $idPresentacion)
    {
        return \App\Models\DetalleContrato::where('idcontrato', $idContrato)
            ->where('idfruta', $idFruta)
            ->where('idvariedad', $idVariedad)
            ->where('idpresentacion', $idPresentacion)
            ->where('tiposervicio', 'preenfrio')
            ->exists();
    }
    private function calcularMontoEstimado($contrato, $tiempoTotal, $cantidad, $montoPreenfriado, $idFruta, $idVariedad, $idPresentacion)
    {
        $nombreComercializadora = strtoupper(trim($contrato->comercializadora->nombrecomercializadora ?? ''));
        $esCalgiant = ($nombreComercializadora === 'CALGIANT');
        
        $montoConservacion = $this->getMontoConservacionContratada(
            $contrato->id,
            $idFruta,
            $idVariedad,
            $idPresentacion
        );
        
        $montoAnden = $this->getMontoAnden(
            $contrato->id,
            $idFruta,
            $idVariedad,
            $idPresentacion
        );

        // Lógica de cálculo similar a generarCobranzasDesdeConservacion
        if ($tiempoTotal <= 48 && !$esCalgiant) {
            return $montoPreenfriado * $cantidad;
        } 
      
        $montoEstimado=0;
        // Retornar el monto estimado
        return $montoEstimado;
    }
    

    public function crearMultiple(Request $request)
    {
        // 🔥 Ahora validamos tarima_ids, NO conservacion_ids
        $request->validate([
            'tarima_ids' => 'required|array',
            'tarima_ids.*' => 'required|integer|exists:conservacion,idtarima'
        ]);

        $estadisticas = [
            'total' => count($request->tarima_ids),
            'creadas' => 0,
            'omitidas' => 0,
            'errores' => 0,
            'detalles' => []
        ];

        foreach ($request->tarima_ids as $idTarima) {
            try {

                // Buscar la conservación por idtarima
                $conservacion = Conservacion::where('idtarima', $idTarima)
                    ->with([
                        'detallesConservacion.detalleRecepcion.recepcion.contrato.comercializadora',
                        'detallesConservacion.detalleRecepcion.fruta',
                        'detallesConservacion.detalleRecepcion.variedad',
                        'detallesConservacion.detalleRecepcion.presentacion'
                    ])
                    ->first();

                if (!$conservacion) {
                    $estadisticas['omitidas']++;
                    $estadisticas['detalles'][] = [
                        'id' => $idTarima,
                        'estado' => 'omitida',
                        'mensaje' => "No se encontró conservación para la tarima $idTarima"
                    ];
                    continue;
                }

                // Generar cobranza
                $this->generarCobranzasDesdeConservacion($conservacion);

                $estadisticas['creadas']++;
                $estadisticas['detalles'][] = [
                    'id' => $idTarima,
                    'estado' => 'creada',
                    'mensaje' => "Cobranza generada correctamente"
                ];

            } catch (\Exception $e) {

                \Log::error("Error procesando tarima {$idTarima}: " . $e->getMessage());

                $estadisticas['errores']++;
                $estadisticas['detalles'][] = [
                    'id' => $idTarima,
                    'estado' => 'error',
                    'mensaje' => $e->getMessage()
                ];
            }
        }

        return response()->json([
            'exito' => $estadisticas['errores'] === 0,
            'mensaje' =>
                "Procesamiento completado. Creadas: {$estadisticas['creadas']} | 
                Omitidas: {$estadisticas['omitidas']} | 
                Errores: {$estadisticas['errores']}",
            'estadisticas' => $estadisticas
        ]);
    }
    private function generarCobranzasDesdeConservacion(Conservacion $conservacion)
    {
        
        foreach ($conservacion->detallesConservacion as $detalleConservacion) {
            if ($detalleConservacion->detalleRecepcion) {
                $detalleRecepcion = $detalleConservacion->detalleRecepcion;
                $recepcion = $detalleRecepcion->recepcion;

                if (!$recepcion || !$recepcion->contrato) {
                    continue;
                }

                // Validar que no exista ya una cobranza para este detalle de recepción
                $cobranza_existente = Cobranza::where('iddetallerecepcion', $detalleRecepcion->id)->first();
                if ($cobranza_existente) {
                    continue; // Saltar este detalle si ya tiene cobranza
                }

                // Calcular tiempos y generar cobranza (usar la misma lógica existente)
              
                $cantidad= $cantidad = $detalleRecepcion->tarimaDetalle->sum('cantidadcarga');
                $idContrato = $recepcion->contrato->id; 
                $idFruta = $detalleRecepcion->idfruta;
                $idVariedad = $detalleRecepcion->idvariedad;
                $idPresentacion = $detalleRecepcion->idpresentacion;

                // Calcular tiempos totales por tarima
                $tiempoPreenfriado   = $this->calcularTiempoPreenfriado($conservacion->idtarima);
                $tiempoConservacion  = $this->calcularTiempoConservacionPorTarima($conservacion->idtarima);
                $tiempoAnden         = $this->calcularTiempoAnden($conservacion->idtarima);

                // Obtener montos del contrato
                $montoPreenfriado = $this->getMontoPreenfriado($idContrato, $idFruta, $idVariedad, $idPresentacion);
                $montoAnden = $this->getMontoAnden($idContrato, $idFruta, $idVariedad, $idPresentacion);
                $montoConservacionContratada = $this->getMontoConservacionContratada($idContrato, $idFruta, $idVariedad, $idPresentacion);
                $monedaContrato = $this->getMonedaContrato($idContrato, $idFruta, $idVariedad, $idPresentacion);
                $tieneServicioAnden = $this->tieneServicioCruceAnden($idContrato, $idFruta, $idVariedad, $idPresentacion);
                $tieneServicioConservacion = $this->tieneServicioConservacion($idContrato, $idFruta, $idVariedad, $idPresentacion);
                $tieneServicioPrenfriado = $this->tieneServicioPreenfriado($idContrato, $idFruta, $idVariedad, $idPresentacion);

                // Calcular cantidad total del folio completo (para Reglas 2 y 7)
                $cantidadTotalFolio = DetalleRecepcion::where('idrecepcion', $recepcion->id)->sum('cantidad');

                // Obtener nombre de comercializadora para validación de reglas
                $nombreComercializadora = $recepcion->contrato->comercializadora->nombrecomercializadora ?? '';

                // Aplicar reglas de negocio (nueva lógica basada en tiempo total y comercializadora)
                $reglaAplicada = 0;
                $subtotalPreenfriado = 0;
                $subtotalConservacion = 0;
                $subtotalAnden = 0;
                $montoConservacionExtra = 0;

                // Normalizar nombre de comercializadora para comparación
                $nombreComercializadoraUpper = strtoupper(trim($nombreComercializadora));

                // Calcular tiempo total (preenfriado + conservación + andén)
                $tiempoTotal = $tiempoPreenfriado + $tiempoConservacion + $tiempoAnden;

                // Determinar si efectivamente hay cruce (servicio + tiempo + monto)
                $tieneCruce = ($tieneServicioAnden && $montoAnden > 0 && $tiempoAnden > 0) ? 1 : 0;



                // =====================
                // NUEVA REGLA 5 Solo cobra cruce de ánden cada 24 horas
                // =====================
                $bloques24h = (int) floor($tiempoAnden / 24);

                if ($tieneServicioAnden && !$tieneServicioConservacion && $tieneServicioPrenfriado && $nombreComercializadoraUpper !== 'CALGIANT' && $bloques24h >= 0) {
                   
                    $reglaAplicada = 9;
                    $subtotalAnden = $montoAnden* $cantidad * $bloques24h;
                }


                // =====================
                // CASO 1: tiempototal <= 48h y comercializadora != CALGIANT
                // =====================
                if ($tiempoTotal <= 48 && $nombreComercializadoraUpper !== 'CALGIANT') {
                    $reglaAplicada = 1;
                    $subtotalPreenfriado = $montoPreenfriado * $cantidad;
                    $subtotalConservacion = 0;
                    $subtotalAnden = 0;
                }

                // =====================
                // CASO 2: tiempototal > 48h y comercializadora != CALGIANT
                // =====================
                elseif ($tiempoTotal > 48 && $nombreComercializadoraUpper !== 'CALGIANT') {
                    $reglaAplicada = 3;

                    // Base: siempre se cobra preenfriado y conservación
                    $subtotalPreenfriado = $montoPreenfriado * $cantidad;
                    $subtotalConservacion = $montoConservacionContratada * $cantidad;
                    $subtotalAnden = 0;

                    if ($tieneCruce) {
                        $subtotalAnden = $montoAnden * $cantidad;
                    }

                    // 24 horas adicionales después de las primeras 48
                    $horasExtra = max(0, $tiempoTotal - 48);
                    $bloques24h = (int) floor($horasExtra / 24);

                    if ($bloques24h > 0) {
                        // Cada bloque de 24h repite conservación + andén
                        $reglaAplicada = 4;
                        $extraConservacion = $bloques24h * $subtotalConservacion;
                        $extraAnden = $bloques24h * $subtotalAnden;
                        $subtotalConservacion += $extraConservacion;
                        $subtotalAnden += $extraAnden;
                    }

                    $montoConservacionExtra = $montoConservacionContratada;
                }

                // =====================
                // CASO 3: tiempototal <= 48h y comercializadora == CALGIANT
                // =====================
                elseif ($tiempoTotal <= 48 && $nombreComercializadoraUpper === 'CALGIANT') {
                    $reglaAplicada = 5;
                    // Para CALGIANT y <=48h, siempre se cobra solo preenfriado
                    // (la condición cantidad == 3000 no cambia el cálculo)
                    $subtotalPreenfriado = $montoPreenfriado * $cantidad;
                    $subtotalConservacion = 0;
                    $subtotalAnden = 0;
                }

                // =====================
                // CASO 4: tiempototal > 48h y comercializadora == CALGIANT
                // =====================
                else {
                    // tiempototal > 48 y comercializadora CALGIANT
                    // Para el criterio de 3000 cajas usamos la cantidad total del folio
                    if ($cantidadTotalFolio > 3000) {
                        // cantidad > 3000
                        $reglaAplicada = 6;
                        $tieneCruce = 1;
                        $subtotalAnden = $montoAnden * $cantidad;
                        $subtotalConservacion = $montoConservacionContratada * $cantidad;
                        $subtotalPreenfriado = $montoPreenfriado * $cantidad;
                    } else {
                        $reglaAplicada = 8;
                        // cantidad <= 3000
                        $tieneCruce = 0;
                        $subtotalAnden = 0;
                        $subtotalConservacion = $montoConservacionContratada * $cantidad;
                        $subtotalPreenfriado = $montoPreenfriado * $cantidad;
                    }

                    // 24 horas adicionales después de las primeras 48
                    $horasExtra = max(0, $tiempoTotal - 48);
                    $bloques24h = (int) floor($horasExtra / 24);

                    if ($bloques24h > 0) {
                        $reglaAplicada = 7;
                        $extraConservacion = $bloques24h * $subtotalConservacion;
                        $extraAnden = $bloques24h * $subtotalAnden;
                        $subtotalConservacion += $extraConservacion;
                        $subtotalAnden += $extraAnden;
                    }

                    $montoConservacionExtra = $montoConservacionContratada;
                }

                $subtotal = $subtotalPreenfriado + $subtotalConservacion + $subtotalAnden;
                $iva = round($subtotal * 0.16, 2);
                $total = round($subtotal + $iva, 2);

                // Crear cobranza
                Cobranza::create([
                    'idrecepcion' => $recepcion->id,
                    'iddetallerecepcion' => $detalleRecepcion->id,
                    'idcontrato'=> $recepcion->contrato->id ?? null,
                    'idcomercializadora' => $recepcion->contrato->idcomercializadora ?? null,
                    'idcooler' => $recepcion->contrato->idcooler ?? null,
                    'folio' => $recepcion->folio,
                    'fruta' => $detalleRecepcion->fruta->nombrefruta ?? 'N/A',
                    'presentacion' => $detalleRecepcion->presentacion->nombrepresentacion ?? 'N/A',
                    'variedad' => $detalleRecepcion->variedad->tipofruta ?? 'N/A',
                    'cantidad' => $cantidad,
                    'monto_preenfriado' => $montoPreenfriado,
                    'monto_conservacion' => $montoConservacionExtra,
                    'monto_anden' => $montoAnden,
                    'tiempo_preenfriado' => $tiempoPreenfriado,
                    'tiempo_conservacion' => $tiempoConservacion,
                    'tiempo_anden' => $tiempoAnden,
                    'moneda' => 'MXN',
                    'tipo_cambio' => 20.00,
                    'moneda_contrato' => $monedaContrato,
                    'subtotal_preenfriado' => round($subtotalPreenfriado, 2),
                    'subtotal_conservacion' => round($subtotalConservacion, 2),
                    'subtotal_anden' => round($subtotalAnden, 2),
                    'iva' => $iva,
                    'total' => $total,
                    'regla_aplicada' => $reglaAplicada,
                    'tiene_cruce_anden' => $tieneCruce,
                    'monto_conservacion_extra' => $montoConservacionExtra,
                    'dia_recepcion' => Carbon::parse($recepcion->created_at)->dayOfWeek,
                    'fecha_recepcion' => Carbon::parse($recepcion->created_at)->format('Y-m-d'),
                    'estatus' => 'PENDIENTE',
                ]);
            }
        }
    }

  

    public function mostrar($idcontrato)
    {
        //return $idcontrato;
        // Obtener todas las comercializadoras para el filtro
        $comercializadoras = Comercializadora::orderBy('nombrecomercializadora')->get();

        // Query base: solo cobranzas de recepciones FINALIZADAS
        $query = \App\Models\Cobranza::with(['recepcion.contrato.comercializadora', 'detalleRecepcion'])
            ->where('idcontrato', $idcontrato)
            ->whereHas('recepcion', function ($q) {
                $q->where('estatus', 'FINALIZADO');
            });

        // ===== FILTROS =====
        /*if ($request->filled('comercializadora_id')) {
            $query->whereHas('recepcion.contrato', function($q) use ($request) {
                $q->where('idcomercializadora', $request->comercializadora_id);
            });
        }

        if ($request->filled('fecha')) {
            $query->whereDate('fecha_recepcion', $request->fecha);
        }

        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('fecha_recepcion', [$request->fecha_inicio, $request->fecha_fin]);
        }

        if ($request->filled('mes') && $request->filled('anio')) {
            $query->whereMonth('fecha_recepcion', $request->mes)
                ->whereYear('fecha_recepcion', $request->anio);
        } elseif ($request->filled('anio')) {
            $query->whereYear('fecha_recepcion', $request->anio);
        }*/

        // Obtener cobranzas (aún sin agrupar), ordenadas de la fecha más reciente a la más antigua
        $cobranzasRaw = $query->orderBy('fecha_recepcion', 'desc')->get();

        // ===== AGRUPAMIENTO (SOLO POR FOLIO) =====
        $cobranzasAgrupadas = [];

        foreach ($cobranzasRaw as $cobranza) {
            // Clave de agrupación: únicamente el folio
            $key = $cobranza->folio;

            if (!isset($cobranzasAgrupadas[$key])) {
                // Inicializamos el grupo y añadimos campos que la vista espera
                $cobranzasAgrupadas[$key] = [
                    'id' => $cobranza->id, // usamos el primer id del grupo para acciones
                    'folio' => $cobranza->folio,
                    'fruta' => $cobranza->fruta,
                    'presentacion' => $cobranza->presentacion,
                    'variedad' => $cobranza->variedad,
                    'moneda' => $cobranza->moneda,
                    'moneda_contrato' => $cobranza->moneda_contrato ?? $cobranza->moneda ?? null,
                    'cantidad' => 0,
                    'subtotal_preenfriado' => 0,
                    'subtotal_conservacion' => 0,
                    'subtotal_anden' => 0,
                    'iva' => 0,
                    'total' => 0,
                    'fecha_recepcion' => $cobranza->fecha_recepcion, // mantener para la vista
                    'regla_aplicada' => $cobranza->regla_aplicada ?? null,
                    'estatus' => $cobranza->estatus ?? null,
                    'recepcion' => $cobranza->recepcion,
                    'detalleRecepcion' => $cobranza->detalleRecepcion,
                    'cobranzas_ids' => [],
                ];
            }

            // Acumular
            $cobranzasAgrupadas[$key]['cantidad'] += $cobranza->cantidad ?? 0;
            $cobranzasAgrupadas[$key]['subtotal_preenfriado'] += $cobranza->subtotal_preenfriado ?? 0;
            $cobranzasAgrupadas[$key]['subtotal_conservacion'] += $cobranza->subtotal_conservacion ?? 0;
            $cobranzasAgrupadas[$key]['subtotal_anden'] += $cobranza->subtotal_anden ?? 0;
            $cobranzasAgrupadas[$key]['iva'] += $cobranza->iva ?? 0;
            $cobranzasAgrupadas[$key]['total'] += $cobranza->total ?? 0;
            $cobranzasAgrupadas[$key]['cobranzas_ids'][] = $cobranza->id;
        }

        // ===== CONVERTIR A COLECCIÓN DE OBJETOS (stdClass) =====
        $cobranzas = collect(array_values($cobranzasAgrupadas))
            ->map(function ($item) {
                // Si 'fecha_recepcion' es Carbon ya, se mantiene; si no, puedes convertir aquí.
                // Convertimos array a objeto para que la vista pueda usar $cobranza->campo
                return (object) $item;
            });

        // ===== FILTROS para la vista =====
      /*  $filtros = [
            'comercializadora_id' => $request->comercializadora_id,
            'fecha' => $request->fecha,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'mes' => $request->mes,
            'anio' => $request->anio,
        ];*/

        return view('cobranza.mostrar', compact('cobranzas', 'comercializadoras'));
    }


















    /**
     * Mostrar el detalle de una cobranza específica
     */
    public function verDetalle($folio)
    {
        // Obtener todas las cobranzas de ese folio con sus relaciones
        $cobranzas = Cobranza::with([
                'recepcion.contrato.comercializadora',
                'recepcion.contrato.cooler',
                'detalleRecepcion'
            ])
            ->where('folio', $folio)
            ->orderBy('id')
            ->get();

        if ($cobranzas->isEmpty()) {
            abort(404);
        }

        // Usamos la primera como referencia para datos generales
        $cobranza = $cobranzas->first();

        // Totales agregados por folio
        $totalCantidad           = $cobranzas->sum('cantidad');
        $totalSubtotalPreenfriado = $cobranzas->sum('subtotal_preenfriado');
        $totalSubtotalConservacion = $cobranzas->sum('subtotal_conservacion');
        $totalSubtotalAnden        = $cobranzas->sum('subtotal_anden');
        $totalIva                  = $cobranzas->sum('iva');
        $totalGeneral              = $cobranzas->sum('total');

        return view('cobranza.detalle', compact(
            'cobranza',
            'cobranzas',
            'totalCantidad',
            'totalSubtotalPreenfriado',
            'totalSubtotalConservacion',
            'totalSubtotalAnden',
            'totalIva',
            'totalGeneral'
        ));
    }
    

    /**
     * Cambiar el estatus de una cobranza (PENDIENTE <-> PAGADA)
     */
    public function cambiarEstatus($id)
    {
        try {
            $cobranza = Cobranza::findOrFail($id);
            
            // Cambiar el estatus
            if ($cobranza->estatus == 'PENDIENTE') {
                $cobranza->estatus = 'PAGADA';
                $mensaje = 'Cobranza marcada como PAGADA exitosamente';
            } else {
                $cobranza->estatus = 'PENDIENTE';
                $mensaje = 'Cobranza marcada como PENDIENTE exitosamente';
            }
            
            $cobranza->save();
            
            return redirect()->route('cobranza')->with('success', $mensaje);
        } catch (\Exception $e) {
            return redirect()->route('cobranza')->with('error', 'Error al cambiar el estatus: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar vista consolidada con detalle de múltiples cobranzas filtradas
     */
    public function verDetalleConsolidado(Request $request)
    {
        // Query base
        $query = Cobranza::with(['recepcion.contrato.comercializadora', 'recepcion.contrato.cooler', 'detalleRecepcion']);
        
        // Aplicar los mismos filtros que en la vista principal
        if ($request->filled('comercializadora_id')) {
            $query->whereHas('recepcion.contrato', function($q) use ($request) {
                $q->where('idcomercializadora', $request->comercializadora_id);
            });
        }
        
        if ($request->filled('fecha')) {
            $query->whereDate('fecha_recepcion', $request->fecha);
        }
        
        // Filtro por rango de fechas
        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('fecha_recepcion', [$request->fecha_inicio, $request->fecha_fin]);
        }
        
        if ($request->filled('mes') && $request->filled('anio')) {
            $query->whereMonth('fecha_recepcion', $request->mes)
                  ->whereYear('fecha_recepcion', $request->anio);
        } elseif ($request->filled('anio')) {
            $query->whereYear('fecha_recepcion', $request->anio);
        }
        
        $cobranzas = $query->orderBy('fecha_recepcion', 'desc')->get();
        
        // Agrupar cobranzas por comercializadora
        $cobranzasAgrupadas = $cobranzas->groupBy(function($cobranza) {
            return $cobranza->recepcion->contrato->comercializadora->id ?? 'sin_comercializadora';
        });
        
        // Obtener información de la comercializadora si está filtrada
        $comercializadora = null;
        if ($request->filled('comercializadora_id')) {
            $comercializadora = Comercializadora::find($request->comercializadora_id);
        }
        
        // Preparar información de filtros aplicados
        $filtrosAplicados = [];
        if ($request->filled('comercializadora_id')) {
            $filtrosAplicados['comercializadora'] = $comercializadora->nombrecomercializadora;
        }
        if ($request->filled('fecha')) {
            $filtrosAplicados['fecha'] = \Carbon\Carbon::parse($request->fecha)->format('d/m/Y');
        }
        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $filtrosAplicados['rango'] = \Carbon\Carbon::parse($request->fecha_inicio)->format('d/m/Y') . ' - ' . \Carbon\Carbon::parse($request->fecha_fin)->format('d/m/Y');
        }
        if ($request->filled('mes')) {
            $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            $filtrosAplicados['mes'] = $meses[$request->mes];
        }
        if ($request->filled('anio')) {
            $filtrosAplicados['anio'] = $request->anio;
        }
        
        // Calcular totales
        $totalPreenfriado = $cobranzas->sum('total_preenfriado');
        $totalConservacion = $cobranzas->sum('total_conservacion');
        $totalGeneral = $totalPreenfriado + $totalConservacion;
        $totalPendiente = $cobranzas->where('estatus', 'PENDIENTE')->sum(function($c) {
            return $c->total_preenfriado + $c->total_conservacion;
        });
        $totalPagado = $cobranzas->where('estatus', 'PAGADA')->sum(function($c) {
            return $c->total_preenfriado + $c->total_conservacion;
        });
        
        return view('cobranza.detalle-consolidado', compact(
            'cobranzas',
            'cobranzasAgrupadas',
            'filtrosAplicados', 
            'totalPreenfriado', 
            'totalConservacion', 
            'totalGeneral',
            'totalPendiente',
            'totalPagado'
        ));
    }

    /**
     * Cambiar estatus de múltiples cobranzas
     */
    public function cambiarEstatusMasivo(Request $request)
    {
        try {
            $cobranzasIds = $request->input('cobranzas', []);
            $nuevoEstatus = $request->input('estatus');
            
            if (empty($cobranzasIds)) {
                return redirect()->back()->with('error', 'No se seleccionaron cobranzas');
            }
            
            $actualizadas = Cobranza::whereIn('id', $cobranzasIds)->update([
                'estatus' => $nuevoEstatus
            ]);
            
            return redirect()->back()->with('success', "Se actualizaron {$actualizadas} cobranzas a estatus {$nuevoEstatus}");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al cambiar el estatus: ' . $e->getMessage());
        }
    }

    /**
     * Generar PDF de cobranzas consolidadas
     */
    public function verPdfConsolidado(Request $request)
    {
        try {
            // Obtener filtros de la solicitud
            $filtros = $request->only(['comercializadora', 'fecha', 'semana', 'fecha_inicio', 'fecha_fin', 'mes', 'anio']);
            
            // Query base
            $query = Cobranza::with([
                'recepcion.contrato.comercializadora',
                'detalleRecepcion.fruta',
                'detalleRecepcion.presentacion',
                'detalleRecepcion.variedad'
            ]);

        // Aplicar filtros
        if ($request->filled('comercializadora')) {
            $query->whereHas('recepcion.contrato', function($q) use ($request) {
                $q->where('idcomercializadora', $request->comercializadora);
            });
        }

        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }

        if ($request->filled('mes') && $request->filled('anio')) {
            $query->whereMonth('fecha', $request->mes)
                  ->whereYear('fecha', $request->anio);
        } elseif ($request->filled('anio')) {
            $query->whereYear('fecha', $request->anio);
        }

        $cobranzas = $query->orderBy('fecha', 'desc')->get();
        
        // Agrupar por comercializadora
        $cobranzasPorComercializadora = $cobranzas->groupBy(function($cobranza) {
            return $cobranza->recepcion->contrato->comercializadora->id;
        });

            $pdf = Pdf::loadView('cobranza.ver_pdf_consolidado', compact('cobranzasPorComercializadora', 'filtros'));
            return $pdf->stream('cobranzas_consolidado.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar PDF: ' . $e->getMessage());
        }
    }

    /**
     * Convertir cobranzas de USD a MXN
     */
    public function convertirMoneda(Request $request)
    {
        try {
            // Validar la tasa de cambio
            $request->validate([
                'tasa_cambio' => 'required|numeric|min:0.01',
            ]);

            $tasaCambio = $request->input('tasa_cambio');
            $filtros = $request->input('filtros', []);

            // Obtener las cobranzas filtradas que estén en USD
            $query = Cobranza::with(['recepcion.contrato.comercializadora', 'recepcion.contrato.cooler']);

            // Aplicar los mismos filtros que en la vista principal
            if (isset($filtros['comercializadora_id']) && $filtros['comercializadora_id']) {
                $query->whereHas('recepcion.contrato', function($q) use ($filtros) {
                    $q->where('idcomercializadora', $filtros['comercializadora_id']);
                });
            }

            if (isset($filtros['fecha']) && $filtros['fecha']) {
                $query->whereDate('fecha_recepcion', $filtros['fecha']);
            }

            if (isset($filtros['fecha_inicio']) && $filtros['fecha_inicio'] && isset($filtros['fecha_fin']) && $filtros['fecha_fin']) {
                $query->whereBetween('fecha_recepcion', [$filtros['fecha_inicio'], $filtros['fecha_fin']]);
            }

            if (isset($filtros['mes']) && $filtros['mes']) {
                $query->whereMonth('fecha_recepcion', $filtros['mes']);
            }

            if (isset($filtros['anio']) && $filtros['anio']) {
                $query->whereYear('fecha_recepcion', $filtros['anio']);
            }

            // Filtrar solo las que están en USD o DOLAR
            $query->where(function($q) {
                $q->where('moneda_contrato', 'USD')
                  ->orWhere('moneda_contrato', 'DOLAR');
            });

            $cobranzas = $query->get();

            if ($cobranzas->isEmpty()) {
                return redirect()->back()->with('error', 'No hay cobranzas en dólares (USD) para convertir con los filtros aplicados.');
            }

            // Convertir cada cobranza
            $cantidadConvertidas = 0;
            foreach ($cobranzas as $cobranza) {
                // Multiplicar todos los montos por la tasa de cambio
                $cobranza->monto_preenfriado = $cobranza->monto_preenfriado * $tasaCambio;
                $cobranza->monto_conservacion = $cobranza->monto_conservacion * $tasaCambio;
                $cobranza->monto_anden = $cobranza->monto_anden * $tasaCambio;
                $cobranza->subtotal_preenfriado = $cobranza->subtotal_preenfriado * $tasaCambio;
                $cobranza->subtotal_conservacion = $cobranza->subtotal_conservacion * $tasaCambio;
                $cobranza->subtotal_anden = $cobranza->subtotal_anden * $tasaCambio;
                $cobranza->iva = $cobranza->iva * $tasaCambio;
                $cobranza->total = $cobranza->total * $tasaCambio;
                
                // Actualizar la moneda a MXN
                $cobranza->moneda = 'MXN';
                $cobranza->moneda_contrato = 'MXN';
                
                // Guardar la tasa de cambio utilizada
                $cobranza->tipo_cambio = $tasaCambio;
                
                $cobranza->save();
                $cantidadConvertidas++;
            }

            return redirect()->route('cobranza')->with('success', 
                "Se convirtieron exitosamente {$cantidadConvertidas} cobranzas de USD a MXN usando la tasa de cambio de \${$tasaCambio} MXN por dólar."
            );

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al convertir moneda: ' . $e->getMessage());
        }
    }
}
