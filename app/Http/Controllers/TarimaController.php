<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\DetalleRecepcion;
use App\Models\Recepcion;
use App\Models\TarimaDetarec;
use App\Models\Tarima;
use App\Models\Camara;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TarimaController extends Controller
{
   /* public function index()
    {
        $user = auth()->user();
        
        // Filtrar tarimas según el rol del usuario
        $tarimasQuery = Tarima::with('tarimaDetarec.detalle.recepcion.contrato.cooler')
            ->where('estatuseliminar', 'activo');

        if ($user->rol->nombrerol == 'Administrador') {
            // Mostrar todas las tarimas
        } elseif ($user->rol->nombrerol == 'Supervisor' || $user->rol->nombrerol == 'Operativo') {
            // Mostrar solo tarimas de recepciones creadas por el usuario y de coolers asignados
            $coolerIds = $user->coolers()->where('usuario_cooler.estatus', 'activo')->pluck('cooler.id');
            $tarimasQuery->whereHas('tarimaDetarec.detalle.recepcion', function($q) use ($coolerIds, $user) {
                $q->where('idusuario', $user->id)
                  ->whereHas('contrato', function($subQ) use ($coolerIds) {
                      $subQ->whereIn('idcooler', $coolerIds);
                  });
            });
        }

        // Filtrar solo tarimas cuyo estatus sea 'tarima' y ordenar por última modificación
       $tarimas = $tarimasQuery
        ->orderByRaw("CASE WHEN ubicacion = 'tarima' AND estatus = 'completo' THEN 0 ELSE 1 END")
        ->orderBy('updated_at', 'desc')
        ->get();

        // Filtrar cámaras según el rol del usuario
        $camarasQuery = Camara::with('cooler')->where('tipo', 'PRE ENFRIADO');

        if ($user->rol->nombrerol == 'Administrador') {
            $camaras = $camarasQuery->get();
        } elseif ($user->rol->nombrerol == 'Supervisor' || $user->rol->nombrerol == 'Operativo') {
            // Obtener IDs de coolers asignados al usuario
            $coolerIdsUsuario = $user->coolers()->pluck('cooler.id')->toArray();
            
            // Obtener IDs de coolers de los contratos de las recepciones de las tarimas visibles
            $coolerIdsRecepciones = $tarimas->pluck('tarimaDetarec')
                ->flatten()
                ->pluck('detalle.recepcion.contrato.idcooler')
                ->filter()
                ->unique()
                ->toArray();
            
            // Combinar ambos arrays y eliminar duplicados
            $coolerIdsCombinados = array_unique(array_merge($coolerIdsUsuario, $coolerIdsRecepciones));
            
            // Obtener cámaras de ambos grupos de coolers
            $camaras = $camarasQuery->whereIn('idcooler', $coolerIdsCombinados)->get();
        } else {
            $camaras = collect(); // Colección vacía para otros roles
        }

        return view('tarimas.mostrar', compact('tarimas', 'camaras'));
    }*/
    public function index()
    {
        $user = auth()->user();
        
        // Filtrar tarimas según el rol del usuario
        $tarimasQuery = Tarima::with('tarimaDetarec.detalle.recepcion.contrato.cooler')
            ->where('estatuseliminar', 'activo');
    
        if ($user->rol->nombrerol == 'Administrador') {
            // Mostrar todas las tarimas
        } else {
            // Obtener los IDs de los coolers asignados al usuario
            $coolerIds = $user->coolers()
                             ->wherePivot('estatus', 'activo')
                             ->pluck('cooler.id');
            
            // Mostrar tarimas de los coolers asignados, sin filtrar por usuario
            $tarimasQuery->whereHas('tarimaDetarec.detalle.recepcion.contrato', function($q) use ($coolerIds) {
                $q->whereIn('idcooler', $coolerIds);
            });
        }
    
        // Filtrar solo tarimas cuyo estatus sea 'tarima' y ordenar por última modificación
        $tarimas = $tarimasQuery
            ->orderByRaw("CASE WHEN ubicacion = 'tarima' AND estatus = 'completo' THEN 0 ELSE 1 END")
            ->orderBy('updated_at', 'desc')
            ->get();
    
        // Filtrar cámaras según el rol del usuario
        $camarasQuery = Camara::with('cooler')->where('tipo', 'PRE ENFRIADO');
    
        if ($user->rol->nombrerol == 'Administrador') {
            $camaras = $camarasQuery->get();
        } else {
            // Obtener cámaras de los coolers asignados al usuario
            $coolerIds = $user->coolers()
                             ->wherePivot('estatus', 'activo')
                             ->pluck('cooler.id');
            
            $camaras = $camarasQuery->whereIn('idcooler', $coolerIds)->get();
        }
    
        return view('tarimas.mostrar', compact('tarimas', 'camaras'));
    }



    public function create()
    {
        // Obtener el próximo ID estimado
        $lastTarima = Tarima::latest('id')->first();
        $nextId = $lastTarima ? $lastTarima->id + 1 : 1;

        // Fecha y hora actuales
        $fecha = Carbon::now()->format('dmy');    // ddmmaa
        $hora = Carbon::now()->format('H:i');     // hh:mm

        // Formato del código
        $codigo = "T{$nextId}-{$fecha}-{$hora}";

        return view('tarimas.crear', compact('codigo'));
    }

    public function storeAutomatic(Request $request)
    {
        // Número de tarimas a generar
        $numTarimas = (int)$request->cuantos;

        // Valores por defecto
        $cantidad  = 10;
        $capacidad = 240;
        $estatus   = 'disponible';
        $ubicacion = 'tarima';

        // Última tarima registrada (para continuar el consecutivo)
        $lastTarima = Tarima::latest('id')->first();
        $nextId     = $lastTarima ? $lastTarima->id + 1 : 1;

        // Fecha y hora actuales
        $fecha = Carbon::now()->format('dmy'); // ddmmaa
        $hora  = Carbon::now()->format('H:i'); // hh:mm

        // Generar en lote
        for ($i = 0; $i < $numTarimas; $i++) {
            $codigo = "T" . ($nextId + $i) . "-{$fecha}-{$hora}";

            Tarima::create([
                'codigo'    => $codigo,
                'cantidad'  => $cantidad,
                'capacidad' => $capacidad,
                'estatus'   => $estatus,
                'ubicacion' => $ubicacion,
            ]);
        }

        return redirect()->route('asignartarima.crear')->with('success', 'Tarimas creadas correctamente.');
    }


    public function store(Request $request)
    {
       
        // Validar la entrada
        $request->validate([
            'codigo'    => 'required|string|unique:tarimas,codigo',
            'cantidad'  => 'required|integer|min:1',
            'capacidad' => 'nullable|integer',
        ]);

        // Crear la tarima
        $tarima = Tarima::create([
            'codigo'   => $request->codigo,
            'cantidad' => $request->cantidad,
            'capacidad'=> 240,
            'estatus'  => 'disponible',
            'ubicacion'=> 'tarima',
        ]);
        return redirect()->route('tarima.mostrar')->with('success', 'Tarimas creadas correctamente.');
    }
    public function show(Tarima $tarima)
    {
        return view('tarimas.show', compact('tarima'));
    }

    public function edit(Tarima $tarima)
    {
        return view('tarimas.editar', compact('tarima'));
    }

    public function update(Request $request, Tarima $tarima)
    {
        $request->validate([
            'codigo' => 'required|unique:tarimas,codigo,' . $tarima->id,
            'capacidad' => 'required|integer|min:0',
            'estatus' => 'required|in:disponible,completo',
            'ubicacion' => 'required|in:tarima,preenfriado,conservacion,cruce_anden,embarque',
            'observaciones' => 'nullable|string',
        ]);

        $tarima->update([
            'codigo' => $request->codigo,
            'capacidad' => $request->capacidad,
            'estatus' => $request->estatus,
            'ubicacion' => $request->ubicacion,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('tarima.mostrar')->with('success', 'Tarima actualizada correctamente.');
    }

    public function destroy(Request $request, $id)
    {
        // Validar observaciones
        $request->validate([
            'observaciones' => 'required|string|max:500',
        ], [
            'observaciones.required' => 'Debe ingresar una observación para eliminar la tarima.',
        ]);

        $tarima = Tarima::find($id);
        
        if (!$tarima) {
            return redirect()->route('tarima.mostrar')->with('error', 'Tarima no encontrada.');
        }

        // Eliminar lógicamente cambiando el estatus y guardando observación
        $tarima->estatuseliminar = 'inactivo';
        $tarima->observaciones = $request->observaciones;
        $tarima->save();

        return redirect()->route('tarima.mostrar')->with('success', 'Tarima eliminada exitosamente.');
    }



    public function etiqueta($id)
    {
        try {
            $tarimas = TarimaDetarec::with([
                'tarima',
                'detalle.recepcion.contrato.comercializadora',
                'detalle.fruta',
                'detalle.presentacion',
                'detalle.variedad',
                'tipopallet'
            ])
                ->where('idtarima', $id)
                ->get();

            if ($tarimas->isEmpty()) {
                return redirect()->back()->with('error', 'No se encontraron detalles para esta tarima.');
            }

            $tarima = $tarimas->first();
            $qrContent = $tarima?->tarima?->codigo ?? 'Sin código';
            
            $qrImage = QrCode::size(100)->generate($qrContent);

            $pdf = Pdf::loadView('tarimas.etiqueta_pdf', compact('tarimas', 'qrContent', 'qrImage'));
            return $pdf->stream('etiqueta_tarima_' . $id . '.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar etiqueta: ' . $e->getMessage());
        }
    }

    public function verPdf($id)
    {
        try {
            // Traer todos los detalles de esa tarima con sus relaciones
            $tarimas = TarimaDetarec::with([
                'tarima',
                'detalle.recepcion.contrato.comercializadora',
                'detalle.fruta',
                'detalle.presentacion',
                'detalle.variedad',
                'tipopallet'
            ])->where('idtarima', $id)->get();

            $tarima = Tarima::findOrFail($id);

            $pdf = Pdf::loadView('tarimas.ver_pdf', compact('tarimas', 'tarima'));
            return $pdf->stream('tarima_' . $id . '.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar PDF: ' . $e->getMessage());
        }
    }

    
    public function mostrarId($id)
    {
        // Traer todos los detalles de esa tarima con sus relaciones
        $tarimas = TarimaDetarec::with([
            'tarima',
            'detalle.fruta',
            'detalle.presentacion',
            'detalle.variedad',
            'detalle.recepcion.contrato.comercializadora'
        ])
        ->where('idtarima', $id)
        ->get();

        if ($tarimas->isEmpty()) {
            return redirect()->route('tarima.mostrar')->with('error', 'Tarima no encontrada.');
        }
        //return $tarimas;
        return view('tarimas.mostrarId', compact('tarimas'));
    }


}
