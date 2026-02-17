<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleRecepcion extends Model
{
    protected $table = 'detalle_recepcion';

    protected $fillable = [
        'idrecepcion',
        'idfruta',
        'idvariedad',
        'idpresentacion',
        'hora',
        'temperatura',
        'tipo',
        'cantidad',
        'pendientes',
        'folio',
        'estatus', //corresponde al estaus de ubicación
    ];

    // Relaciones
    public function recepcion()
    {
        return $this->belongsTo(Recepcion::class, 'idrecepcion');
    }

    public function fruta()
    {
        return $this->belongsTo(Fruta::class, 'idfruta');
    }

    public function variedad()
    {
        return $this->belongsTo(Variedad::class, 'idvariedad');
    }

    public function presentacion()
    {
        return $this->belongsTo(Presentacion::class, 'idpresentacion');
    }
    // utilidad
    public function getDisponibleAttribute()
    {
    return $this->pendientes; // semántica más clara
    }


    // En app/Models/DetalleRecepcion.php

// Relación con tarima_detarec
public function tarimaDetalle()
{
    return $this->hasMany(TarimaDetarec::class, 'iddetalle', 'id');
}

// Relación con detalle_preenfriado
public function detallePreenfriado()
{
    return $this->hasOne(DetallePreenfriado::class, 'iddetalle');
}

// Relación con detalle_conservacion
public function detalleConservacion()
{
    return $this->hasOne(DetalleConservacion::class, 'iddetalle');
}

// Relación con detalle_cruce_anden
public function detalleCruceAnden()
{
    return $this->hasOne(DetalleCruceAnden::class, 'iddetalle');
}

// Relación con embarcación a través de detalle_conservacion
public function embarcacion()
{
    return $this->hasOneThrough(
        Embarcacion::class,
        DetalleConservacion::class,
        'iddetalle',        // FK en detalle_conservacion
        'idconservacion',   // FK en embarcacion
        'id',               // PK en detalle_recepcion
        'id'                // PK en detalle_conservacion
    );
}

    
}
