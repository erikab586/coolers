<?php

namespace App\Services;

use App\Models\DetalleContrato;
use App\Models\CruceAnden;
use Carbon\Carbon;

class CobranzaService
{
    const IVA_PORCENTAJE = 0.16; // 16%
    const TARIFA_CONSERVACION_EXTRA = 0.12; // $0.12 por caja después de 48 horas
    const LIMITE_CAJAS_REGLA_2 = 3000; // Límite de cajas para aplicar regla 2
    const LIMITE_HORAS_PREENFRIADO = 48; // 48 horas máximo para preenfriado normal
    const LIMITE_HORAS_CONSERVACION_MIN = 48; // 48 horas
    const LIMITE_HORAS_CONSERVACION_MAX = 72; // 72 horas

    /**
     * Obtener tipo de cambio actual (puedes implementar API externa o tabla en BD)
     */
    public function getTipoCambio()
    {
        // Por ahora retornamos un valor fijo, pero puedes implementar:
        // 1. Consulta a API externa (Banxico, etc.)
        // 2. Tabla en base de datos con tipos de cambio históricos
        // 3. Configuración en .env
        return 20.00; // USD a MXN
    }

    /**
     * Convertir monto a MXN si está en USD
     */
    public function convertirAMXN($monto, $moneda, $tipoCambio)
    {
        if (strtoupper($moneda) === 'USD' || strtoupper($moneda) === 'DOLAR') {
            return $monto * $tipoCambio;
        }
        return $monto;
    }

    /**
     * Obtener monto de preenfriado del contrato
     */
    public function getMontoPreenfriado($idContrato, $idFruta, $idVariedad, $idPresentacion)
    {
        $detalle = DetalleContrato::where('idcontrato', $idContrato)
            ->where('idfruta', $idFruta)
            ->where('idvariedad', $idVariedad)
            ->where('idpresentacion', $idPresentacion)
            ->where(function($query) {
                $query->where('tiposervicio', 'PREENFRIADO')
                      ->orWhere('tiposervicio', 'COMPLETO');
            })
            ->first();

        if ($detalle) {
            return [
                'monto' => $detalle->monto,
                'moneda' => $detalle->moneda ?? 'MXN'
            ];
        }

        return ['monto' => 0, 'moneda' => 'MXN'];
    }

    /**
     * Obtener monto de cruce de andén del contrato
     */
    public function getMontoAnden($idContrato, $idFruta, $idVariedad, $idPresentacion)
    {
        $detalle = DetalleContrato::where('idcontrato', $idContrato)
            ->where('idfruta', $idFruta)
            ->where('idvariedad', $idVariedad)
            ->where('idpresentacion', $idPresentacion)
            ->where('tiposervicio', 'CRUCE DE ANDÉN')
            ->first();

        if ($detalle) {
            return [
                'monto' => $detalle->monto,
                'moneda' => $detalle->moneda ?? 'MXN'
            ];
        }

        return ['monto' => 0, 'moneda' => 'MXN'];
    }

    /**
     * Verificar si el contrato tiene servicio de cruce de andén
     */
    public function tieneServicioCruceAnden($idContrato, $idFruta, $idVariedad, $idPresentacion)
    {
        $detalle = DetalleContrato::where('idcontrato', $idContrato)
            ->where('idfruta', $idFruta)
            ->where('idvariedad', $idVariedad)
            ->where('idpresentacion', $idPresentacion)
            ->where('tiposervicio', 'CRUCE DE ANDÉN')
            ->exists();

        return $detalle;
    }

    /**
     * Calcular cobranza según reglas de negocio
     * 
     * @param array $params [
     *   'cantidad' => int,
     *   'tiempo_preenfriado' => float (horas),
     *   'tiempo_conservacion' => float (horas),
     *   'tiempo_anden' => float (horas),
     *   'monto_preenfriado' => float,
     *   'monto_anden' => float,
     *   'moneda_preenfriado' => string,
     *   'moneda_anden' => string,
     *   'tiene_cruce_anden' => bool
     * ]
     */
    public function calcularCobranza($params)
    {
        $cantidad = $params['cantidad'];
        $horasPreenfriado = $params['tiempo_preenfriado']; // en horas
        $horasConservacion = $params['tiempo_conservacion']; // en horas
        $horasAnden = $params['tiempo_anden'] ?? 0; // en horas
        $montoPreenfriado = $params['monto_preenfriado'];
        $montoAnden = $params['monto_anden'] ?? 0;
        $monedaPreenfriado = $params['moneda_preenfriado'] ?? 'MXN';
        $monedaAnden = $params['moneda_anden'] ?? 'MXN';
        $tieneCruceAnden = $params['tiene_cruce_anden'] ?? false;

        $tipoCambio = $this->getTipoCambio();

        // Convertir montos a MXN
        $montoPreenfriado = $this->convertirAMXN($montoPreenfriado, $monedaPreenfriado, $tipoCambio);
        $montoAnden = $this->convertirAMXN($montoAnden, $monedaAnden, $tipoCambio);

        $reglaAplicada = 0;
        $subtotalPreenfriado = 0;
        $subtotalConservacion = 0;
        $subtotalAnden = 0;
        $montoConservacionExtra = 0;

        // REGLA 1: Preenfriado normal (desde entrada hasta 48 horas)
        if ($horasPreenfriado > 0 && $horasPreenfriado <= 48 && $horasConservacion < 48) {
            $reglaAplicada = 1;
            $subtotalPreenfriado = $montoPreenfriado * $cantidad;
        }
        // REGLA 2: Con cruce de andén y más de 3000 cajas
        elseif ($tieneCruceAnden && $cantidad > self::LIMITE_CAJAS_REGLA_2 && $horasConservacion < 48) {
            $reglaAplicada = 2;
            $subtotalPreenfriado = $montoPreenfriado * $cantidad;
            $subtotalAnden = $montoAnden * $cantidad;
        }
        // REGLA 3: Conservación de 48 a 72 horas (sin cruce de andén)
        elseif (!$tieneCruceAnden && $horasConservacion >= 48 && $horasConservacion <= 72) {
            $reglaAplicada = 3;
            $subtotalPreenfriado = $montoPreenfriado * $cantidad;
            $montoConservacionExtra = self::TARIFA_CONSERVACION_EXTRA * $cantidad;
            $subtotalConservacion = $montoConservacionExtra;
        }
        // REGLA 4: Con cruce de andén y más de 48 horas en conservación
        elseif ($tieneCruceAnden && $horasConservacion > 48) {
            $reglaAplicada = 4;
            $subtotalPreenfriado = $montoPreenfriado * $cantidad;
            $montoConservacionExtra = self::TARIFA_CONSERVACION_EXTRA * $cantidad;
            $subtotalConservacion = $montoConservacionExtra;
            $subtotalAnden = $montoAnden * $cantidad;
        }
        // Caso por defecto: solo preenfriado
        else {
            $reglaAplicada = 1;
            $subtotalPreenfriado = $montoPreenfriado * $cantidad;
        }

        // Calcular subtotal
        $subtotal = $subtotalPreenfriado + $subtotalConservacion + $subtotalAnden;

        // Calcular IVA (16%)
        $iva = $subtotal * self::IVA_PORCENTAJE;

        // Calcular total
        $total = $subtotal + $iva;

        return [
            'regla_aplicada' => $reglaAplicada,
            'subtotal_preenfriado' => round($subtotalPreenfriado, 2),
            'subtotal_conservacion' => round($subtotalConservacion, 2),
            'subtotal_anden' => round($subtotalAnden, 2),
            'monto_conservacion_extra' => round($montoConservacionExtra, 2),
            'subtotal' => round($subtotal, 2),
            'iva' => round($iva, 2),
            'total' => round($total, 2),
            'tipo_cambio' => $tipoCambio,
            'moneda_contrato' => $monedaPreenfriado
        ];
    }

    /**
     * Verificar si una tarima pasó por cruce de andén
     */
    public function pasoPorCruceAnden($idTarima)
    {
        return CruceAnden::where('idtarima', $idTarima)->exists();
    }
}
