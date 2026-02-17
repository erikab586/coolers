<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Embarcacion extends Model
{
    protected $table = 'embarcaciones';

    protected $fillable = [
        'folio',
        'trans_placa',
        'trans_placacaja',
        'trans_temperaturacaja',
        'condtrans_estado',
        'condtrans_higiene',
        'condtrans_plagas',
        'prod_ultimacarga', 
        'condtar_desmontado',
        'condtar_flejado',
        'condtar_distribucion',
        'infcarga_hrallegada',
        'infcarga_hracarga',
        'infcarga_hrasalida',
        'infcarga_nsello',
        'infcarga_nchismografo',
        'id_usuario',
        'firma_usuario',
        'nombre_responsblecliente',
        'apellido_responsablecliente',
        'firma_cliente',
        'nombre_responsblechofer',
        'apellido_responsablechofer',
        'firma_chofer',
        'linea_transporte',
        'total1',
        'total2',
        'total3',
        'total4',
        'total5',
        'total6',
        'observaciones',
    ];

    // Relación: una embarcación tiene muchos detalles
    public function detalles()
    {
        return $this->hasMany(DetalleEmbarcacion::class, 'idembarcacion');
    }

    // Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    // Relación: obtener todas las tarimas de esta embarcación
    public function tarimas()
    {
        return Tarima::query()
            ->join('conservacion', 'conservacion.idtarima', '=', 'tarimas.id')
            ->join('detalle_embarcaciones', 'detalle_embarcaciones.idconservacion', '=', 'conservacion.id')
            ->where('detalle_embarcaciones.idembarcacion', $this->id)
            ->select('tarimas.*');
    }
    

    // Relación: obtener todas las recepciones de esta embarcación
    public function recepciones()
    {
        return $this->hasManyThrough(
            Recepcion::class,
            DetalleEmbarcacion::class,
            'idembarcacion',
            'id',
            'id',
            'idconservacion'
        )->join('conservacion', 'conservacion.id', '=', 'detalle_embarcacion.idconservacion')
         ->join('tarimas', 'tarimas.id', '=', 'conservacion.idtarima')
         ->join('tarima_detarec', 'tarima_detarec.idtarima', '=', 'tarimas.id')
         ->join('detalle_recepcion', 'detalle_recepcion.id', '=', 'tarima_detarec.iddetalle')
         ->join('recepcion', 'recepcion.id', '=', 'detalle_recepcion.idrecepcion')
         ->select('recepcion.*')
         ->distinct();
    }

    /**
     * Obtener información detallada de tarimas y recepciones de esta embarcación
     * Retorna un array con la información organizada
     */
    public function getTarimasyRecepciones()
    {
        $resultado = [];
        
        foreach ($this->detalles as $detalle) {
            if ($detalle->conservacion && $detalle->conservacion->tarima) {
                $tarima = $detalle->conservacion->tarima;
                
                // Obtener todas las recepciones de esta tarima
                foreach ($tarima->tarimaDetarec as $tarimaDetarec) {
                    if ($tarimaDetarec->detalle && $tarimaDetarec->detalle->recepcion) {
                        $recepcion = $tarimaDetarec->detalle->recepcion;
                        
                        $resultado[] = [
                            'tarima_id' => $tarima->id,
                            'tarima_codigo' => $tarima->codigo,
                            'recepcion_id' => $recepcion->id,
                            'recepcion_folio' => $recepcion->folio,
                            'comercializadora' => $recepcion->contrato->comercializadora->nombrecomercializadora ?? 'N/A',
                            'cooler' => $recepcion->contrato->cooler->nombrecooler ?? 'N/A',
                            'fecha_recepcion' => $recepcion->fechaemision,
                        ];
                    }
                }
            }
        }
        
        return collect($resultado)->unique('tarima_id')->values()->all();
    }
}
