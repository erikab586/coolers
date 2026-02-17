<?php

namespace App\Http\Controllers;
use App\Models\Cooler;
use App\Models\Comercializadora;
use App\Models\Contrato;
use App\Models\Recepcion;
use App\Models\Tarima;
use App\Models\Embarcacion;
use App\Models\Preenfriado;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
     // Mostrar vista principal de los coolers
    public function index()
    {
        $user = auth()->user();
        $rolUsuario = $user->rol->nombrerol ?? null;
        
        //Información para las cards de arriba
        $coolers = Cooler::count();
        $comercializadoras = Comercializadora::count();
        $contratos = Contrato::count();
        
        // Filtrar recepciones y tarimas según el rol del usuario
        if (in_array($rolUsuario, ['Supervisor', 'Operativo'])) {
            // Obtener los IDs de los coolers asignados al usuario
            $coolerIds = $user->coolers()->where('usuario_cooler.estatus', 'activo')->pluck('cooler.id');
            
            // Filtrar recepciones por cooler asignado (a través del contrato) Y por usuario que creó la recepción
            $recepciones = Recepcion::where('idusuario', $user->id)
                ->whereHas('contrato', function($query) use ($coolerIds) {
                    $query->whereIn('idcooler', $coolerIds);
                })
                ->orderBy('id', 'desc')
                ->get();
            
            // Filtrar tarimas que pertenecen a recepciones del cooler asignado y del usuario logueado
            $tarimas = Tarima::whereHas('tarimaDetarec.detalle.recepcion', function($query) use ($coolerIds, $user) {
                $query->where('idusuario', $user->id)
                      ->whereHas('contrato', function($q) use ($coolerIds) {
                          $q->whereIn('idcooler', $coolerIds);
                      });
            })->orderBy('id', 'desc')->get();
        } else {
            // Administrador ve todas las recepciones y tarimas
            $recepciones = Recepcion::orderBy('id', 'desc')->get();
            $tarimas = Tarima::orderBy('id', 'desc')->get();
        }
        
        return view('dashboard',compact('coolers', 'comercializadoras','contratos', 'recepciones', 'tarimas'));
    }

    // Mostrar formulario de creación
    public function crear()
    {
        return view('coolers.crear');
    }

    // Guardar nuevo cooler
    public function registrarCooler(Request $request)
    {
        $request->validate([
            'nombrecooler' => 'required|string|max:150',
            'tipocooler' => 'required|in:preenfrio,conservación',
            'capacidad' => 'required|string|max:10',
            'codigoidentificador' => 'required|string|max:10|unique:coolers',
            'ubicacion' => 'required|string|max:150',
        ]);

        $cooler = Cooler::create([
            'nombrecooler' => $request->nombrecooler,
            'tipocooler' => $request->tipocooler,
            'capacidad' => $request->capacidad,
            'codigoidentificador' => $request->codigoidentificador,
            'ubicacion' => $request->ubicacion,
        ]);

        return response()->json(['success' => true, 'cooler' => $cooler]);
    }

    // Mostrar formulario de edición
    public function formularioEditar($id)
    {
        $cooler = Cooler::findOrFail($id);
        return view('coolers.editar', compact('cooler'));
    }

    // Guardar cambios del cooler
    public function editar(Request $request, $id)
    {
        $cooler = Cooler::findOrFail($id);

        $request->validate([
            'nombrecooler' => 'required|string|max:150',
            'tipocooler' => 'required|in:preenfrio,conservación',
            'capacidad' => 'required|string|max:10',
            'codigoidentificador' => 'required|string|max:10|unique:coolers,codigoidentificador,' . $id,
            'ubicacion' => 'required|string|max:150',
        ]);

        $cooler->update($request->all());

        return response()->json(['success' => true, 'cooler' => $cooler]);
    }

    // Exportar lista a Excel
    public function exportar()
    {
        return Excel::download(new CoolersExport, 'coolers.xlsx');
    }

    // Buscar por ID
    public function buscarId($id)
    {
        $cooler = Cooler::findOrFail($id);
        return view('coolers.ver', compact('cooler'));
    }

    // Buscar por nombre (búsqueda AJAX o similar)
    public function buscarNomb(Request $request)
    {
        $nombre = $request->nombre;
        $coolers = Cooler::where('nombrecooler', 'like', '%' . $nombre . '%')->get();
        return response()->json($coolers);
    }

   public function verCobranza(Request $request)
    {
        // Obtener todas las comercializadoras para el filtro
        $comercializadoras = Comercializadora::orderBy('nombrecomercializadora')->get();

        // Query base: solo cobranzas de recepciones FINALIZADAS
        $query = \App\Models\Cobranza::with(['recepcion.contrato.comercializadora', 'detalleRecepcion'])
            ->whereHas('recepcion', function ($q) {
                $q->where('estatus', 'FINALIZADO');
            });

        // ===== FILTROS =====
        if ($request->filled('comercializadora_id')) {
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
        }

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
        $filtros = [
            'comercializadora_id' => $request->comercializadora_id,
            'fecha' => $request->fecha,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'mes' => $request->mes,
            'anio' => $request->anio,
        ];

        return view('cobranza.mostrar', compact('cobranzas', 'comercializadoras', 'filtros'));
    }



    /**
     * Mostrar cobranzas filtradas por comercializadora
     */
    public function verCobranzaPorComercializadora($idComercializadora)
    {
        $comercializadora = \App\Models\Comercializadora::findOrFail($idComercializadora);
        $comercializadoras = Comercializadora::orderBy('nombrecomercializadora')->get();

        // Listar cobranzas sin agrupar (una por detalle) para esa comercializadora
        $cobranzas = \App\Models\Cobranza::with(['recepcion.contrato.comercializadora', 'detalleRecepcion'])
            ->whereHas('recepcion.contrato', function($q) use ($idComercializadora) {
                $q->where('idcomercializadora', $idComercializadora);
            })
            ->orderBy('fecha_recepcion', 'desc')
            ->get();

        $filtros = [
            'comercializadora_id' => $idComercializadora,
            'fecha' => null,
            'mes' => null,
            'anio' => null,
        ];

        return view('cobranza.vercobranza', compact('cobranzas', 'comercializadora', 'comercializadoras', 'filtros'));
    }

    public function verCobranzaPorComercializadoraPdf($idComercializadora)
    {
        try {
            $comercializadora = \App\Models\Comercializadora::findOrFail($idComercializadora);
            
            $cobranzas = \App\Models\Cobranza::with([
                'recepcion.contrato.comercializadora',
                'recepcion.contrato.cooler',
                'detalleRecepcion.fruta',
                'detalleRecepcion.variedad',
                'detalleRecepcion.presentacion'
            ])
                ->whereHas('recepcion.contrato', function($q) use ($idComercializadora) {
                    $q->where('idcomercializadora', $idComercializadora);
                })
                ->orderBy('fecha', 'desc')
                ->get();
            
            // Calcular totales
            $totalPreenfriado = $cobranzas->sum('monto_preenfriado');
            $totalConservacion = $cobranzas->sum('monto_conservacion');
            $totalAnden = $cobranzas->sum('monto_anden');
            $totalGeneral = $cobranzas->sum('monto_total');
            $totalPagadas = $cobranzas->where('estatus', 'PAGADA')->sum('monto_total');
            $totalPendientes = $cobranzas->where('estatus', 'PENDIENTE')->sum('monto_total');
            
            $pdf = Pdf::loadView('cobranza.ver_pdf_comercializadora', compact(
                'cobranzas',
                'comercializadora',
                'totalPreenfriado',
                'totalConservacion',
                'totalAnden',
                'totalGeneral',
                'totalPagadas',
                'totalPendientes'
            ));
            
            return $pdf->stream('cobranzas_' . $comercializadora->nombrecomercializadora . '.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar PDF: ' . $e->getMessage());
        }
    }

}
