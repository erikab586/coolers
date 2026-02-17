<?php

namespace App\Http\Controllers;
use App\Models\Conservacion;
use App\Models\DetalleEmbarcacion;
use App\Models\DetalleRecepcion;
use App\Models\Embarcacion;
use App\Models\Tarima;
use App\Models\TarimaDetarec;
use App\Models\Cobranza;
use App\Models\Preenfriado;
use App\Models\CruceAnden;
use App\Services\CobranzaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class EmbarcacionController extends Controller
{
   public function index()
    {
        $user = auth()->user();
        
        // Filtrar embarcaciones según el rol del usuario
        $embarcacionQuery = Embarcacion::with('detalles.conservacion.tarima.tarimaDetarec.detalle.recepcion.contrato.cooler')
                            ->orderBy('created_at', 'desc');

        if ($user->rol->nombrerol == 'Administrador') {
            // Mostrar todas las embarcaciones
        } else {
            // Obtener los IDs de los coolers asignados al usuario
            $coolerIds = $user->coolers()
                            ->wherePivot('estatus', 'activo')
                            ->pluck('cooler.id');
            
            // Mostrar embarcaciones de los coolers asignados, sin filtrar por usuario
            $embarcacionQuery->whereHas('detalles.conservacion.tarima.tarimaDetarec.detalle.recepcion.contrato', function($q) use ($coolerIds) {
                $q->whereIn('idcooler', $coolerIds);
            });
        }

        $embarcacion = $embarcacionQuery->get();
        return view('embarcacion.mostrar', compact('embarcacion'));
    }

    public function create($id)
    {
        //El id que entra corresponde a idtarima para buscar la conservacion
        $conservaciones = Conservacion::with([
            'detallesConservacion.detalleRecepcion',
            'tarima' // Asegurarse de cargar la tarima
        ])
        ->where('idtarima', $id)
        ->firstOrFail();
        
        // Verificar si la tarima ya está marcada como finalizada
        if ($conservaciones->tarima && $conservaciones->tarima->ubicacion === 'finalizado') {
            return redirect()->back()->with('error', 'Esta conservación ya tiene embarcación creada.');
        }
        
        // Sugerir hora de llegada = última hora_salida registrada en conservación
        $ultimaSalidaConservacion = $conservaciones->detallesConservacion
            ->max('hora_salida');

        $horaLlegadaSugerida = null;
        if ($ultimaSalidaConservacion) {
            $horaLlegadaSugerida = Carbon::parse($ultimaSalidaConservacion)
                ->format('Y-m-d\\TH:i');
        }

        return view('embarcacion.crear', compact('conservaciones', 'horaLlegadaSugerida'));
    }

    public function store(Request $request)
    {
        // VALIDAR SI YA EXISTE UNA EMBARCACIÓN PARA ESTA CONSERVACIÓN
        if ($request->has('idconservacion')) {
            $idconservacion = $request->idconservacion[0] ?? null;

            if ($idconservacion) {
                $existe = DetalleEmbarcacion::where('idconservacion', $idconservacion)->exists();

                if ($existe) {
                    return redirect()->back()
                        ->with('error', '⚠️ Esta tarima ya se encuentra embarcada.')
                        ->withInput();
                }
            }
        }

        // Validar datos de embarcación antes de crear el registro
        $validated = $request->validate([
            'trans_placa'               => 'required|string|max:50',
            'trans_placacaja'           => 'required|string|max:50',
            'trans_temperaturacaja'     => 'nullable|numeric',
            'condtrans_estado'          => 'nullable|boolean',
            'condtrans_higiene'         => 'nullable|boolean',
            'condtrans_plagas'          => 'nullable|boolean',
            'prod_ultimacarga'          => 'nullable|string|max:150',
            'condtar_desmontado'        => 'nullable|boolean',
            'condtar_flejado'           => 'nullable|boolean',
            'condtar_distribucion'      => 'nullable|boolean',
            // datetime-local -> se envía como string con fecha y hora
            'infcarga_hrallegada'       => 'required|string',
            'infcarga_hracarga'         => 'required|string',
            'infcarga_hrasalida'        => 'required|string',
            'infcarga_nsello'           => 'required|string|max:50',
            'infcarga_nchismografo'     => 'nullable|string|max:50',
            'id_usuario'                => 'required|exists:users,id',
            'firma_usuario'             => 'nullable|string',
            'nombre_responsblecliente'  => 'required|string|max:150',
            'apellido_responsablecliente' => 'nullable|string|max:150',
            'firma_cliente'             => 'nullable|string',
            'nombre_responsblechofer'   => 'required|string|max:150',
            'apellido_responsablechofer'=> 'nullable|string|max:150',
            'firma_chofer'              => 'nullable|string',
            'linea_transporte'          => 'required|string|max:150',
            'total1'                    => 'nullable|string',
            'total2'                    => 'nullable|string',
            'total3'                    => 'nullable|string',
            'total4'                    => 'nullable|string',
            'total5'                    => 'nullable|string',
            'total6'                    => 'nullable|string',
        ]);

        // Evitar valores NULL en columnas NOT NULL de la BD
        $validated['apellido_responsablecliente'] = $validated['apellido_responsablecliente'] ?? 'N/A';
        $validated['apellido_responsablechofer']  = $validated['apellido_responsablechofer']  ?? 'N/A';

        // 1. Crear la embarcación con datos validados y folio consecutivo
        $embarcacion = Embarcacion::create([
            'folio'                     => $this->generarFolioEmbarcacion(),
            'trans_placa'               => $validated['trans_placa'],
            'trans_placacaja'           => $validated['trans_placacaja'],
            'trans_temperaturacaja'     => $validated['trans_temperaturacaja'] ?? 0,
            'condtrans_estado'          => $validated['condtrans_estado'] ?? 0,
            'condtrans_higiene'         => $validated['condtrans_higiene'] ?? 0,
            'condtrans_plagas'          => $validated['condtrans_plagas'] ?? 0,
            'prod_ultimacarga'          => $validated['prod_ultimacarga'] ?? null,
            'condtar_desmontado'        => $validated['condtar_desmontado'] ?? 0,
            'condtar_flejado'           => $validated['condtar_flejado'] ?? 0,
            'condtar_distribucion'      => $validated['condtar_distribucion'] ?? 0,
            'infcarga_hrallegada'       => $validated['infcarga_hrallegada'],
            'infcarga_hracarga'         => $validated['infcarga_hracarga'],
            'infcarga_hrasalida'        => $validated['infcarga_hrasalida'],
            'infcarga_nsello'           => $validated['infcarga_nsello'],
            'infcarga_nchismografo'     => $validated['infcarga_nchismografo'] ?? null,
            'id_usuario'                => $validated['id_usuario'],
            'firma_usuario'             => $validated['firma_usuario'] ?? null,
            'nombre_responsblecliente'  => $validated['nombre_responsblecliente'],
            'apellido_responsablecliente' => $validated['apellido_responsablecliente'],
            'firma_cliente'             => $validated['firma_cliente'] ?? null,
            'nombre_responsblechofer'   => $validated['nombre_responsblechofer'],
            'apellido_responsablechofer'=> $validated['apellido_responsablechofer'],
            'firma_chofer'              => $validated['firma_chofer'] ?? null,
            'linea_transporte'          => $validated['linea_transporte'],
            'total1'                    => $validated['total1'] ?? '0',
            'total2'                    => $validated['total2'] ?? '0',
            'total3'                    => $validated['total3'] ?? '0',
            'total4'                    => $validated['total4'] ?? '0',
            'total5'                    => $validated['total5'] ?? '0',
            'total6'                    => $validated['total6'] ?? '0',
        ]);

        // 2. Crear los detalles (relación con Conservación y detalletarima)
        if ($request->has('idconservacion') && $request->has('iddetalletarima')) {
            // Usamos solo la primera conservación (esta pantalla trabaja con una)
            $idconservacion = $request->idconservacion[0] ?? null;

            if ($idconservacion) {
                // Verificar si la tarima ya está marcada como finalizada (doble verificación)
                $conservacion = Conservacion::with('tarima')->find($idconservacion);
               /* foreach ($request->iddetalletarima as $iddt) {
                    // Registrar detalle embarcación
                    DetalleEmbarcacion::create([
                        'idembarcacion'   => $embarcacion->id,
                        'idconservacion'  => $idconservacion,
                        'iddetalletarima' => $iddt,
                    ]);

                    // Cambiar estatus de la recepción correspondiente a esa tarima
                    $tarima = TarimaDetarec::with('detalle.recepcion')->find($iddt);

                    if ($tarima && $tarima->detalle && $tarima->detalle->recepcion) {
                        $tarima->detalle->recepcion->estatus = 'FINALIZADO';
                        $tarima->detalle->recepcion->save();
                    }
                   /* $tarima = Tarima::find($detalleTarima->idtarima);

                    // 2️⃣ Actualizar ubicación en tabla TARIMAS
                   
                    

                }*/
                foreach ($request->iddetalletarima as $iddt) {

                    DetalleEmbarcacion::create([
                        'idembarcacion'   => $embarcacion->id,
                        'idconservacion'  => $idconservacion,
                        'iddetalletarima' => $iddt,
                    ]);

                    // Obtener la tarima con sus relaciones
                    $tarima = TarimaDetarec::with('detalle.recepcion')->find($iddt);

                    if ($tarima && $tarima->detalle && $tarima->detalle->recepcion) {

                        // ✔ Cambiar el estatus de la recepción
                        $tarima->detalle->recepcion->estatus = 'FINALIZADO';
                        $tarima->detalle->recepcion->save();
                    }

                    // ✔ Cambiar ubicación de la TARIMA (corrección real)
                    Tarima::where('id', $tarima->idtarima)->update([
                        'ubicacion' => 'finalizado'
                    ]);
                }


            }
        }
        return redirect()->route('embarcacion.mostrar')->with('success', 'Embarcación registrada con éxito');
    }
    /**
     * Crear embarcación con múltiples conservaciones del mismo folio
     */
    
    /**
     * Mostrar formulario para crear embarcación con múltiples conservaciones
     */
   public function processMultiple(Request $request)
{
    if (!$request->has('conservaciones_ids') || empty($request->conservaciones_ids)) {
        return redirect()->back()->with('error', 'Debe seleccionar al menos una conservación para embarcar');
    }

    $ids = explode(',', $request->conservaciones_ids);

    // IMPORTANTE: cargar bien las relaciones REALES
    $conservaciones = Conservacion::with([
        'tarima.tarimaDetarec.detalle.recepcion.contrato.comercializadora'
    ])
    ->whereIn('id', $ids)
    ->get();

    if ($conservaciones->isEmpty()) {
        return redirect()->back()->with('error', 'No se encontraron conservaciones válidas.');
    }

    // Validar comercializadora
    $comercializadoraId = null;

    foreach ($conservaciones as $cons) {

        // Cada tarima puede tener varios tarimaDetarec, buscamos el primero válido
        $detarec = $cons->tarima?->tarimaDetarec->first();

        if (!$detarec || !$detarec->detalle || !$detarec->detalle->recepcion || 
            !$detarec->detalle->recepcion->contrato || !$detarec->detalle->recepcion->contrato->comercializadora) 
        {
            continue;
        }

        $com = $detarec->detalle->recepcion->contrato->comercializadora;

        if ($comercializadoraId === null) {
            $comercializadoraId = $com->id;
        } elseif ($com->id !== $comercializadoraId) {
            return redirect()->back()
                ->with('error', "Las conservaciones pertenecen a diferentes comercializadoras.");
        }
    }

    // Si todo está bien → enviar a createMultiple
    return redirect()->route('embarcacion.crear.multiple.form', [
        'ids' => implode(',', $ids)
    ]);
}




    public function createMultiple(Request $request)
    {
        $ids = explode(',', $request->ids);
        $conservaciones = Conservacion::with([
            'tarima',
            'detallesConservacion.detalleRecepcion.fruta',
            'detallesConservacion.detalleRecepcion.presentacion',
            'detallesConservacion.detalleRecepcion.variedad',
            'detallesConservacion.detalleRecepcion.recepcion.contrato',
            'detallesConservacion' // Asegurarnos de cargar los detalles
        ])->findMany($ids);

        if ($conservaciones->isEmpty()) {
            return redirect()->back()->with('error', 'No se encontraron las conservaciones seleccionadas.');
        }

        // Verificar que ninguna tarima esté marcada como finalizada
        foreach ($conservaciones as $conservacion) {
            if ($conservacion->tarima && $conservacion->tarima->ubicacion === 'finalizado') {
                return redirect()->back()->with('error', "Está embarcacion ya tiene embarcación creada.");
            }
        }

        // Verificar que todas las conservaciones sean de la misma comercializadora
        $comercializadoraId = $conservaciones->first()->tarima->tarimaDetarec->first()->detalle->recepcion->idcomercializadora ?? null;
        foreach ($conservaciones as $conservacion) {
            $currentComercializadoraId = $conservacion->tarima->tarimaDetarec->first()->detalle->recepcion->idcomercializadora ?? null;
            if ($currentComercializadoraId !== $comercializadoraId) {
                return redirect()->back()->with('error', 'Todas las conservaciones deben ser de la misma comercializadora.');
            }
        }

        // Calcular la hora de llegada sugerida (última hora de salida de conservación)
        $horaLlegadaSugerida = null;
        $ultimaSalida = null;

        foreach ($conservaciones as $conservacion) {
            foreach ($conservacion->detallesConservacion as $detalle) {
                if ($detalle->hora_salida && (!$ultimaSalida || $detalle->hora_salida > $ultimaSalida)) {
                    $ultimaSalida = $detalle->hora_salida;
                }
            }
        }

        if ($ultimaSalida) {
            $horaLlegadaSugerida = Carbon::parse($ultimaSalida)->format('Y-m-d\TH:i');
        }

        return view('embarcacion.crear_multiple', compact('conservaciones', 'horaLlegadaSugerida'));
    }
   public function storeMultiple(Request $request)
    {
         // ----------------------------------------------------
        // 🔍 VALIDAR SI ALGUNA CONSERVACIÓN YA TIENE EMBARQUE
        // ----------------------------------------------------
         $ids = explode(',', $request->conservaciones_ids);
        foreach ($ids as $idcons) {
            $existe = DetalleEmbarcacion::where('idconservacion', $idcons)->exists();
            if ($existe) {
                return redirect()->back()
                    ->with('error', '⚠️ Una o más conservaciones ya tienen embarcación registrada.')
                    ->withInput();
            }
        }
        $request->validate([
            'conservaciones_ids' => 'required|string',
            'trans_placa'               => 'required|string|max:50',
            'trans_placacaja'           => 'required|string|max:50',
            'trans_temperaturacaja'     => 'nullable|numeric',
            'condtrans_estado'          => 'nullable|boolean',
            'condtrans_higiene'         => 'nullable|boolean',
            'condtrans_plagas'          => 'nullable|boolean',
            'prod_ultimacarga'          => 'nullable|string|max:150',
            'condtar_desmontado'        => 'nullable|boolean',
            'condtar_flejado'           => 'nullable|boolean',
            'condtar_distribucion'      => 'nullable|boolean',
            'infcarga_hrallegada'       => 'required|string',
            'infcarga_hracarga'         => 'required|string',
            'infcarga_hrasalida'        => 'required|string',
            'infcarga_nsello'           => 'required|string|max:50',
            'infcarga_nchismografo'     => 'nullable|string|max:50',
            'id_usuario'                => 'required|exists:users,id',
            'firma_usuario'             => 'nullable|string',
            'nombre_responsblecliente'  => 'required|string|max:150',
            'apellido_responsablecliente' => 'nullable|string|max:150',
            'firma_cliente'             => 'nullable|string',
            'nombre_responsblechofer'   => 'required|string|max:150',
            'apellido_responsablechofer'=> 'nullable|string|max:150',
            'firma_chofer'              => 'nullable|string',
            'linea_transporte'          => 'required|string|max:150',
        ]);

       

       

        // ----------------------------------------------------
        // ✔️ SI TODO ESTÁ LIMPIO, CREAR UNA SOLA EMBARCACIÓN
        // ----------------------------------------------------
        try {
            DB::beginTransaction();

            // Crear embarcación igual que en store
            $embarcacion = Embarcacion::create([
                'folio'                     => $this->generarFolioEmbarcacion(),
                'trans_placa'               => $request->trans_placa,
                'trans_placacaja'           => $request->trans_placacaja,
                'trans_temperaturacaja'     => $request->trans_temperaturacaja ?? 0,
                'condtrans_estado'          => $request->condtrans_estado ?? 0,
                'condtrans_higiene'         => $request->condtrans_higiene ?? 0,
                'condtrans_plagas'          => $request->condtrans_plagas ?? 0,
                'prod_ultimacarga'          => $request->prod_ultimacarga ?? null,
                'condtar_desmontado'        => $request->condtar_desmontado ?? 0,
                'condtar_flejado'           => $request->condtar_flejado ?? 0,
                'condtar_distribucion'      => $request->condtar_distribucion ?? 0,
                'infcarga_hrallegada'       => $request->infcarga_hrallegada,
                'infcarga_hracarga'         => $request->infcarga_hracarga,
                'infcarga_hrasalida'        => $request->infcarga_hrasalida,
                'infcarga_nsello'           => $request->infcarga_nsello,
                'infcarga_nchismografo'     => $request->infcarga_nchismografo,
                'id_usuario'                => $request->id_usuario,
                'firma_usuario'             => $request->firma_usuario,
                'nombre_responsblecliente'  => $request->nombre_responsblecliente,
                'apellido_responsablecliente' => $request->apellido_responsablecliente ?? 'N/A',
                'firma_cliente'             => $request->firma_cliente,
                'nombre_responsblechofer'   => $request->nombre_responsblechofer,
                'apellido_responsablechofer'=> $request->apellido_responsablechofer ?? 'N/A',
                'firma_chofer'              => $request->firma_chofer,
                'linea_transporte'          => $request->linea_transporte,
                'total1'                    => $request->total1 ?? '0',
                'total2'                    => $request->total2 ?? '0',
                'total3'                    => $request->total3 ?? '0',
                'total4'                    => $request->total4 ?? '0',
                'total5'                    => $request->total5 ?? '0',
                'total6'                    => $request->total6 ?? '0',
            ]);

            // ----------------------------------------------------
            // 🔗 Registrar múltiples DetalleEmbarcación
            // ----------------------------------------------------
           /* foreach ($ids as $idcons) {
                $cons = Conservacion::with('tarima.tarimaDetarec.detalle.recepcion')->find($idcons);

                DetalleEmbarcacion::create([
                    'idembarcacion'   => $embarcacion->id,
                    'idconservacion'  => $idcons,
                    'iddetalletarima' => $cons->tarima->detalle->id ?? null, 
                ]);

                // Actualizar recepción → FINALIZADO (igual que en store)
                if ($cons->tarima->detalle->recepcion ?? false) {
                    $cons->tarima->detalle->recepcion->estatus = 'FINALIZADO';
                    $cons->tarima->detalle->recepcion->save();
                }
                
            }*/
            foreach ($ids as $idcons) {

                $cons = Conservacion::with('tarima.tarimaDetarec.detalle.recepcion')->find($idcons);

                DetalleEmbarcacion::create([
                    'idembarcacion'   => $embarcacion->id,
                    'idconservacion'  => $idcons,
                    'iddetalletarima' => $cons->tarima->detalle->id ?? null, 
                ]);


                // ---------------------------------------------------------
                // ✔️ ACTUALIZAR ESTATUS DE RECEPCIÓN A "FINALIZADO"
                // ---------------------------------------------------------
                if ($cons->tarima->detalle->recepcion ?? false) {
                    $cons->tarima->detalle->recepcion->estatus = 'FINALIZADO';
                    $cons->tarima->detalle->recepcion->save();
                }


                // ---------------------------------------------------------
                // 🟦 NUEVO: ACTUALIZAR TARIMA Y RECEPCIÓN (como en Conservación)
                // ---------------------------------------------------------
                $tarima = $cons->tarima ?? null;

                if ($tarima) {

                    // Cambiar ubicación de la tarima
                    $tarima->ubicacion = 'finalizado';     // 🔵 Nuevo valor
                    $tarima->save();

                    // Actualizar la recepción relacionada
                    $recepcion = $tarima->recepcion ?? null;

                    if ($recepcion) {
                        $recepcion->estatus = 'FINALIZADO';
                        $recepcion->save();
                    }
                }
            }


            DB::commit();

            return redirect()->route('embarcacion.mostrar')
                ->with('success', 'Embarcación múltiple registrada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear embarcación múltiple: ' . $e->getMessage());
        }
    }

   
    // En el método store o processMultiple del EmbarcacionController
  /*  public function processMultiple(Request $request)
    {
        // Validar que se hayan seleccionado conservaciones
        if (!$request->has('conservaciones') || empty($request->conservaciones)) {
            return redirect()->back()
                ->with('error', 'Debe seleccionar al menos una conservación para embarcar');
        }

        // Obtener las conservaciones seleccionadas con sus relaciones
        $conservaciones = Conservacion::with(['tarima.detalle.recepcion.comercializadora'])
            ->whereIn('id', $request->conservaciones)
            ->get();

        // Verificar que todas las conservaciones pertenezcan a la misma comercializadora
        $comercializadoraId = null;
        $comercializadoraNombre = null;
        $errorComercializadora = false;

        foreach ($conservaciones as $conservacion) {
            if (!$conservacion->tarima || !$conservacion->tarima->detalle || 
                !$conservacion->tarima->detalle->recepcion || 
                !$conservacion->tarima->detalle->recepcion->comercializadora) {
                continue;
            }

            $currentComercializadora = $conservacion->tarima->detalle->recepcion->comercializadora;
            
            if ($comercializadoraId === null) {
                // Primera iteración, establecer la comercializadora de referencia
                $comercializadoraId = $currentComercializadora->id;
                $comercializadoraNombre = $currentComercializadora->nombre;
            } elseif ($currentComercializadora->id !== $comercializadoraId) {
                // Se encontró una conservación con diferente comercializadora
                $errorComercializadora = true;
                $otraComercializadora = $currentComercializadora->nombre;
                break;
            }
        }

        if ($errorComercializadora) {
            return redirect()->back()
                ->with('error', "No se pueden embarcar conservaciones de diferentes comercializadoras. 
                    Se encontró una conservación de $otraComercializadora, pero ya se había seleccionado una de $comercializadoraNombre.");
        }

        // Si llegamos aquí, todas las conservaciones son de la misma comercializadora
        // Continuar con el proceso de creación de la embarcación
        // ... resto del código para crear la embarcación ...
    }*/






    public function edit($id)
    {
        $embarcacion = Embarcacion::with('detalles.conservacion.tarima')->findOrFail($id);
        
        // Verificar si ya fue completado (tiene las tres firmas)
        $yaEditado = $embarcacion->firma_usuario && $embarcacion->firma_cliente && $embarcacion->firma_chofer;
        
        return view('embarcacion.editar', compact('embarcacion', 'yaEditado'));
    }

    public function update(Request $request, $id)
    {
        $embarcacion = Embarcacion::findOrFail($id);
        
        // Validar que la embarcación no haya sido completada previamente
        // Si ya tiene firma_usuario, significa que ya fue completada
        if ($embarcacion->firma_usuario && $embarcacion->firma_cliente && $embarcacion->firma_chofer) {
            return redirect()->back()->with('error', 'Esta Embarcación ya fue completada anteriormente. Solo se puede editar una vez.');
        }
        
        $embarcacion->update([
            'trans_placa' => $request->trans_placa,
            'trans_placacaja' => $request->trans_placacaja,
            'trans_temperaturacaja' => $request->trans_temperaturacaja,
            'condtrans_estado' => $request->has('condtrans_estado') ? 1 : 0,
            'condtrans_higiene' => $request->has('condtrans_higiene') ? 1 : 0,
            'condtrans_plagas' => $request->has('condtrans_plagas') ? 1 : 0,
            'prod_ultimacarga' => $request->prod_ultimacarga,
            'condtar_desmontado' => $request->has('condtar_desmontado') ? 1 : 0,
            'condtar_flejado' => $request->has('condtar_flejado') ? 1 : 0,
            'condtar_distribucion' => $request->has('condtar_distribucion') ? 1 : 0,
            'infcarga_hrallegada' => $request->infcarga_hrallegada,
            'infcarga_hracarga' => $request->infcarga_hracarga,
            'infcarga_hrasalida' => $request->infcarga_hrasalida,
            'infcarga_nsello' => $request->infcarga_nsello,
            'infcarga_nchismografo' => $request->infcarga_nchismografo,
            'firma_usuario' => $request->firma_usuario,
            'nombre_responsblecliente' => $request->nombre_responsblecliente,
            'apellido_responsablecliente' => $request->apellido_responsablecliente,
            'firma_cliente' => $request->firma_cliente,
            'nombre_responsblechofer' => $request->nombre_responsblechofer,
            'apellido_responsablechofer' => $request->apellido_responsablechofer,
            'firma_chofer' => $request->firma_chofer,
            'linea_transporte' => $request->linea_transporte,
            'total1' => $request->total1,
            'total2' => $request->total2,
            'total3' => $request->total3,
            'total4' => $request->total4,
            'total5' => $request->total5,
            'total6' => $request->total6,
        ]);
        return redirect()->route('embarcacion.mostrar')->with('success', 'Embarcación actualizada exitosamente.');
    }

    public function show($id)
    {
        $embarcacion = Embarcacion::with([
            'detalles.conservacion.tarima.tarimaDetarec.detalle.recepcion.contrato.comercializadora',
            'detalles.conservacion.detallesConservacion.detalleRecepcion',
            'usuario'
        ])->findOrFail($id);
        
        return view('embarcacion.mostrarId', compact('embarcacion'));
    }

    public function destroy(Request $request, $id)
    {
        // Validar observaciones obligatorias
        $request->validate([
            'observaciones' => 'required|string|max:500',
        ], [
            'observaciones.required' => 'Las observaciones son obligatorias para eliminar una embarcación.',
            'observaciones.max' => 'Las observaciones no pueden exceder los 500 caracteres.',
        ]);

        $embarcacion = Embarcacion::with('detalles.conservacion.tarima.tarimaDetarec.detalle')->find($id);
        
        if (!$embarcacion) {
            return redirect()->route('embarcacion.mostrar')->with('error', 'Embarcación no encontrada.');
        }

        try {
            // 1. Eliminar las cobranzas relacionadas con los detalles de recepción de esta embarcación
            foreach ($embarcacion->detalles as $detalle) {
                if ($detalle->conservacion && $detalle->conservacion->tarima) {
                    // Revertir el estado de la tarima a 'conservacion' cuando se elimina la embarcación
                    $detalle->conservacion->tarima->update(['ubicacion' => 'conservacion']);
                    
                    $tarimas = TarimaDetarec::where('idtarima', $detalle->conservacion->idtarima)->get();
                    
                    foreach ($tarimas as $tarima) {
                        if ($tarima->detalle) {
                            // Eliminar cobranzas relacionadas
                            Cobranza::where('iddetallerecepcion', $tarima->detalle->id)->delete();
                            
                            // Revertir estatus de la recepción
                            if ($tarima->detalle->recepcion) {
                                $tarima->detalle->recepcion->estatus = 'EN CONSERVACIÓN';
                                $tarima->detalle->recepcion->save();
                            }
                        }
                    }
                }
            }

            // 2. Eliminar los detalles de embarcación
            DetalleEmbarcacion::where('idembarcacion', $id)->delete();

            // 3. Guardar observaciones antes de eliminar
            $embarcacion->observaciones = $request->observaciones;
            $embarcacion->save();
            
            // 4. Eliminar la embarcación
            $embarcacion->delete();

            return redirect()->route('embarcacion.mostrar')->with('success', 'Embarcación, cobranzas y detalles eliminados exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('embarcacion.mostrar')->with('error', 'Error al eliminar la embarcación: ' . $e->getMessage());
        }
    }

    /**
     * Generar cobranzas a partir de una conservación y sus detalles
     */
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
                $cantidad = $detalleRecepcion->cantidad;
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
                    $reglaAplicada = 2;

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
                    $reglaAplicada = 3;
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
                    $reglaAplicada = 4;

                    // Para el criterio de 3000 cajas usamos la cantidad total del folio
                    if ($cantidadTotalFolio > 3000) {
                        // cantidad > 3000
                        $tieneCruce = 1;
                        $subtotalAnden = $montoAnden * $cantidad;
                        $subtotalConservacion = $montoConservacionContratada * $cantidad;
                        $subtotalPreenfriado = $montoPreenfriado * $cantidad;
                    } else {
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

    /**
     * Calcular tiempo de preenfriado en horas (por tarima)
     * Usa la diferencia entre la primera hora_entrada y la última hora_salida
     * de los registros de DetallePreenfriado asociados a la tarima.
     */
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

    public function verPdf($id)
    {
        try {
            $embarcacion = Embarcacion::with([
                'detalles.conservacion.tarima.tarimaDetarec.detalle.recepcion.contrato.comercializadora',
                'detalles.conservacion.detallesConservacion.detalleRecepcion.recepcion',
                'detalles.conservacion.detallesConservacion.detalleRecepcion.fruta',
                'detalles.conservacion.detallesConservacion.detalleRecepcion.variedad',
                'detalles.conservacion.detallesConservacion.detalleRecepcion.presentacion',
                'usuario'
            ])->findOrFail($id);

            $pdf = Pdf::loadView('embarcacion.ver_pdf', compact('embarcacion'));
            return $pdf->stream('embarcacion_' . $id . '.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar PDF: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar vista para agregar firmas
     */
    public function firmas($id)
    {
        $embarcacion = Embarcacion::findOrFail($id);
        return view('embarcacion.firmas', compact('embarcacion'));
    }

    /**
     * Guardar firmas digitales
     */
    public function guardarFirmas(Request $request, $id)
    {
        $request->validate([
            'firma_usuario' => 'required|string',
            'firma_cliente' => 'required|string',
            'firma_chofer' => 'required|string',
        ], [
            'firma_usuario.required' => 'La firma del Usuario es obligatoria.',
            'firma_cliente.required' => 'La firma del Cliente es obligatoria.',
            'firma_chofer.required' => 'La firma del Chofer es obligatoria.',
        ]);

        try {
            $embarcacion = Embarcacion::findOrFail($id);
            
            $embarcacion->update([
                'firma_usuario' => $request->firma_usuario,
                'firma_cliente' => $request->firma_cliente,
                'firma_chofer' => $request->firma_chofer,
            ]);

            return redirect()->route('embarcacion.mostrarid', $embarcacion)
                ->with('success', 'Firmas guardadas correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al guardar las firmas: ' . $e->getMessage());
        }
    }
        /**
     * Generar folio consecutivo para embarcaciones con formato E00001, E00002, etc.
     */
    private function generarFolioEmbarcacion(): string
    {
        // Tomar el último folio creado
        $ultimoFolio = \App\Models\Embarcacion::orderBy('id', 'desc')->value('folio');

        // Si no hay folio o no cumple el formato, iniciar en E00001
        if (!$ultimoFolio || !preg_match('/^E(\d{5})$/', $ultimoFolio)) {
            return 'E00001';
        }

        // Extraer número, incrementarlo y volver a armar el folio
        $numero = (int) substr($ultimoFolio, 1);
        $numero++;

        return 'E' . str_pad((string) $numero, 5, '0', STR_PAD_LEFT);
    }

}
