<?php

namespace App\Http\Controllers;
use App\Models\Cooler;
use App\Models\Camara;
use Illuminate\Http\Request;

class CamaraController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Filtrar según el rol del usuario
        if ($user->rol && $user->rol->nombrerol === 'Administrador') {
            // Administrador ve todas las cámaras y coolers
            $camaras = Camara::with('cooler')->where('estatus', 'activo')
                ->orderBy('id', 'desc')
                ->get()
                ->groupBy('idcooler');

            $coolers = Cooler::with(['camaras' => function ($query) {
                $query->select('id', 'idcooler', 'tipo'); 
            }])
            ->orderBy('id', 'desc')
            ->get();
            
        } elseif ($user->rol && in_array($user->rol->nombrerol, ['Supervisor', 'Operativo'])) {
            // Supervisor y Operativo ven solo las cámaras de sus coolers asignados
            $coolerIds = $user->coolers()->pluck('cooler.id');
            
            if ($coolerIds->isEmpty()) {
                $camaras = collect();
                $coolers = collect();
            } else {
                $camaras = Camara::with('cooler')
                    ->where('estatus', 'activo')
                    ->whereIn('idcooler', $coolerIds)
                    ->orderBy('id', 'desc')
                    ->get()
                    ->groupBy('idcooler');

                $coolers = Cooler::with(['camaras' => function ($query) {
                    $query->select('id', 'idcooler', 'tipo'); 
                }])
                ->whereIn('id', $coolerIds)
                ->orderBy('id', 'desc')
                ->get();
            }
            
        } else {
            // Si no tiene rol o rol no reconocido, no mostrar nada
            $camaras = collect();
            $coolers = collect();
        }

        return view('camaras.mostrar', compact('camaras','coolers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        
        // Filtrar coolers según el rol
        if ($user->rol && $user->rol->nombrerol === 'Administrador') {
            $coolers = Cooler::where('estatus', 'activo')->get();
        } elseif ($user->rol && in_array($user->rol->nombrerol, ['Supervisor', 'Operativo'])) {
            $coolerIds = $user->coolers()->pluck('cooler.id');
            $coolers = Cooler::where('estatus', 'activo')->whereIn('id', $coolerIds)->get();
        } else {
            $coolers = collect();
        }
        
        return view('camaras.crear', compact('coolers'));
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        $request->validate([
            'idcooler' => 'required|exists:cooler,id',
            'codigo'   => 'required|array',
            'tipo'     => 'required|array',
        ]);

        $idcooler = $request->input('idcooler');
        $codigos  = $request->input('codigo');
        $tipos    = $request->input('tipo');

        try {
            for ($i = 0; $i < count($codigos); $i++) {
                // Validar que no exista ya una cámara con el mismo código en el mismo cooler
                $camaraExistente = Camara::where('idcooler', $idcooler)
                    ->where('codigo', $codigos[$i])
                    ->first();
                
                if ($camaraExistente) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', "Ya existe una cámara con el código '{$codigos[$i]}' en este cooler. Por favor, use un código diferente.");
                }
                
                Camara::create([
                    'idcooler'         => $idcooler,
                    'codigo'           => $codigos[$i],        
                    'capacidadminima'  => 4,
                    'capacidadmaxima'  => 14,
                    'tipo'             => $tipos[$i],
                ]);
            }

            return redirect()->route('camara.mostrar')->with('success', 'Cámaras creadas correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear las cámaras: ' . $e->getMessage());
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(Camara $camara)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
   public function edit(Camara $camara)
    {
        $user = auth()->user();
        
        // Filtrar coolers según el rol
        if ($user->rol && $user->rol->nombrerol === 'Administrador') {
            $coolers = Cooler::where('estatus', 'activo')->get();
        } elseif ($user->rol && in_array($user->rol->nombrerol, ['Supervisor', 'Operativo'])) {
            $coolerIds = $user->coolers()->pluck('cooler.id');
            $coolers = Cooler::where('estatus', 'activo')->whereIn('id', $coolerIds)->get();
        } else {
            $coolers = collect();
        }
        
        return view('camaras.editar', compact('camara', 'coolers'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Camara $camara)
    {
        $request->validate([
            'idcooler' => 'required|exists:cooler,id',
            'codigo'   => 'required|string|unique:camara,codigo,' . $camara->id,
            'tipo'     => 'required|in:PRE ENFRIADO,CONSERVACIÓN',
        ], [
            'idcooler.required' => 'No puede modificar el cooler.',
            'codigo.required'   => 'Debe ingresar un número de código.',
            'codigo.unique'     => 'Ya existe otra cámara con ese número, ingrese uno válido.',
            'tipo.required'     => 'Este campo es obligatorio.',
            'tipo.in'           => 'El tipo debe ser PRE ENFRIADO o CONSERVACIÓN.',
        ]);

        $camara->update([
            'idcooler'         => $request->input('idcooler'),
            'codigo'           => $request->input('codigo'),
            'capacidadminima'  => 4,  // o podrías permitir editar
            'capacidadmaxima'  => 14,
            'tipo'             => $request->input('tipo'),
        ]);

        return redirect()->route('camara.mostrar')->with('success', 'Cámara actualizada correctamente.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Camara $camara)
    {
        $camara->estatus = 'inactivo';
        $camara->save();
        return redirect()->route('camara.mostrar')->with('success', 'Camara eliminada.');
    }
}
