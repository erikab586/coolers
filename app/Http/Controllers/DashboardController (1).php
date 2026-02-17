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

class DashboardController extends Controller
{
     // Mostrar vista principal de los coolers
    public function index()
    {
        
        //Información para las cards de arriba
        $coolers = Cooler::count();
        $comercializadoras = Comercializadora::count();
        $contratos = Contrato::count();
        $recepciones= Recepcion::all();
        $tarimas= Tarima::all();
        // Recepciones de hoy
      /* $recepciones = Recepcion::whereDate('created_at', Carbon::today())->get();
        if ($recepciones->isEmpty()) {
            $recepciones = Recepcion::whereDate('created_at', Carbon::yesterday())->get();
            $fechaRecepcion = Carbon::yesterday()->format('d/m/Y');
        } else {
            $fechaRecepcion = Carbon::today()->format('d/m/Y');
        }

        //Tarimas 
        $tarimas = Tarima::whereDate('created_at', Carbon::today())->get();*/
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

    public function show()
    {
        $embarcaciones = Embarcacion::with(
            'detalles.conservacion.detallesconservacion.detalleRecepcion.recepcion.contrato.comercializadora'
        )->get();
       
        $reporte = [];

        foreach ($embarcaciones as $embarcacion) {
            foreach ($embarcacion->detalles as $detalle) {
                foreach ($detalle->conservacion->detallesconservacion as $detConservacion) {
                    $detalleRecepcion = $detConservacion->detalleRecepcion;
                    $recepcion        = $detalleRecepcion->recepcion;
                    $contrato         = $recepcion->contrato;

                    $comercializadora = $contrato->comercializadora->nombrecomercializadora ?? '';
                    $nombreFruta      = $detalleRecepcion->fruta->nombrefruta ?? '';
                    $nombrePresentacion = $detalleRecepcion->presentacion->nombrepresentacion ?? '';
                    $tipoFruta        = $detalleRecepcion->variedad->tipofruta ?? '';
                    $cantidad         = $detalleRecepcion->cantidad ?? 0;

                     // Filtrar los montos por servicio
                    $detallePreenfriado = $contrato->detalleContrato
                        ->where('idfruta', $detalleRecepcion->idfruta)
                        ->where('idvariedad', $detalleRecepcion->idvariedad)
                        ->where('idpresentacion', $detalleRecepcion->idpresentacion)
                        ->where('tiposervicio', 'preenfrio')
                        ->first();

                    $detalleConservacion = $contrato->detalleContrato
                        ->where('idfruta', $detalleRecepcion->idfruta)
                        ->where('idvariedad', $detalleRecepcion->idvariedad)
                        ->where('idpresentacion', $detalleRecepcion->idpresentacion)
                        ->where('tiposervicio', 'conservacion')
                        ->first();

                    $montoPreenfriado = $detallePreenfriado ? $cantidad * $detallePreenfriado->monto : 0;
                    $montoConservacion = $detalleConservacion ? $cantidad * $detalleConservacion->monto : 0;
                    $montoTotal = $montoPreenfriado + $montoConservacion;

                    // Horas
                    $horaRecepcion = \Carbon\Carbon::parse($detalleRecepcion->hora);
                    $horaEntrada   = \Carbon\Carbon::parse($detConservacion->hora_entrada);
                    $horaSalida    = \Carbon\Carbon::parse($detConservacion->hora_salida);

                    $preenfriado  = $horaRecepcion->diffInMinutes($horaEntrada);
                    $conservacion = $horaEntrada->diffInMinutes($horaSalida);

                    $reporte[] = [
                        'comercializadora'   => $comercializadora,
                        'folio'              => $detalleRecepcion->folio,
                        'preenfriado_min'    => $preenfriado,
                        'conservacion_min'   => $conservacion,
                        'nombrefruta'        => $nombreFruta,
                        'nombrepresentacion' => $nombrePresentacion,
                        'tipofruta'          => $tipoFruta,
                        'cantidad'           => $cantidad,
                        'montopreenfriado'  =>  $detallePreenfriado?->monto ?? 0 ,
                        'montoconservacion' =>  $detalleConservacion?->monto ?? 0,
                        'totalpreenfriado'  =>  $montoPreenfriado,
                        'totalconservacion' =>  $montoConservacion,
                        'moneda' => $contrato->tipomoneda,
                        'fechaconservacion'  => $detalle->created_at,
                    ];
                }
            }
        }

        // Eliminar duplicados por folio, comercializadora y fruta
        $reporte = collect($reporte)
            ->unique(function ($item) {
                return $item['folio'].'-'.$item['comercializadora'].'-'.$item['nombrefruta'].'-'.$item['nombrepresentacion'].'-'.$item['cantidad'];
            })
            ->values();

        return view('cobranza.mostrar', compact('reporte'));
    }


}
