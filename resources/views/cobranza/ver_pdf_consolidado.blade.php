<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cobranzas Consolidadas</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10pt; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18pt; color: #333; }
        .filtros { background-color: #f8f9fa; padding: 10px; margin-bottom: 15px; border-radius: 5px; font-size: 9pt; }
        .filtros strong { color: #007bff; }
        .comercializadora-section { margin-bottom: 25px; page-break-inside: avoid; }
        .comercializadora-header { background-color: #007bff; color: white; padding: 8px; font-size: 12pt; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 9pt; }
        th { background-color: #6c757d; color: white; padding: 6px; text-align: left; }
        td { padding: 5px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .totales { background-color: #e9ecef; font-weight: bold; }
        .resumen-general { margin-top: 20px; padding: 15px; background-color: #d4edda; border: 2px solid #28a745; }
        .resumen-general h3 { margin: 0 0 10px 0; color: #155724; }
        .resumen-grid { display: table; width: 100%; }
        .resumen-row { display: table-row; }
        .resumen-label { display: table-cell; padding: 5px; width: 70%; }
        .resumen-value { display: table-cell; padding: 5px; text-align: right; font-weight: bold; }
        .footer { margin-top: 30px; text-align: center; font-size: 8pt; color: #666; border-top: 1px solid #ddd; padding-top: 10px; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 3px; font-size: 8pt; font-weight: bold; }
        .badge-success { background-color: #28a745; color: white; }
        .badge-warning { background-color: #ffc107; color: #000; }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE COBRANZAS CONSOLIDADO</h1>
        <p>{{ now()->format('d/m/Y H:i') }}</p>
    </div>

    @if(!empty($filtros))
    <div class="filtros">
        <strong>Filtros aplicados:</strong>
        @if(isset($filtros['comercializadora']))
            Comercializadora: {{ \App\Models\Comercializadora::find($filtros['comercializadora'])->nombrecomercializadora ?? 'N/A' }} |
        @endif
        @if(isset($filtros['fecha']))
            Fecha: {{ \Carbon\Carbon::parse($filtros['fecha'])->format('d/m/Y') }} |
        @endif
        @if(isset($filtros['mes']) && isset($filtros['anio']))
            Mes/Año: {{ $filtros['mes'] }}/{{ $filtros['anio'] }} |
        @elseif(isset($filtros['anio']))
            Año: {{ $filtros['anio'] }} |
        @endif
        @if(isset($filtros['fecha_inicio']) && isset($filtros['fecha_fin']))
            Rango: {{ \Carbon\Carbon::parse($filtros['fecha_inicio'])->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($filtros['fecha_fin'])->format('d/m/Y') }}
        @endif
    </div>
    @endif

    @php
        $totalGeneral = 0;
        $totalPreenfriado = 0;
        $totalConservacion = 0;
        $totalAnden = 0;
        $totalPagadas = 0;
        $totalPendientes = 0;
    @endphp

    @foreach($cobranzasPorComercializadora as $idComercializadora => $cobranzas)
        @php
            $comercializadora = $cobranzas->first()->recepcion->contrato->comercializadora;
            $subtotalComercializadora = $cobranzas->sum('monto_total');
            $totalGeneral += $subtotalComercializadora;
            $totalPreenfriado += $cobranzas->sum('monto_preenfriado');
            $totalConservacion += $cobranzas->sum('monto_conservacion');
            $totalAnden += $cobranzas->sum('monto_anden');
        @endphp

        <div class="comercializadora-section">
            <div class="comercializadora-header">
                {{ $comercializadora->nombrecomercializadora }}
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Fecha</th>
                        <th>Fruta</th>
                        <th>Cantidad</th>
                        <th>Preenfriado</th>
                        <th>Conservación</th>
                        <th>Andén</th>
                        <th>Total</th>
                        <th>Estatus</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cobranzas as $cobranza)
                    <tr>
                        <td>{{ $cobranza->recepcion->folio }}</td>
                        <td>{{ \Carbon\Carbon::parse($cobranza->fecha)->format('d/m/Y') }}</td>
                        <td>{{ $cobranza->detalleRecepcion->fruta->nombrefruta ?? 'N/A' }}</td>
                        <td>{{ $cobranza->detalleRecepcion->cantidad }}</td>
                        <td>${{ number_format($cobranza->monto_preenfriado, 2) }}</td>
                        <td>${{ number_format($cobranza->monto_conservacion, 2) }}</td>
                        <td>${{ number_format($cobranza->monto_anden, 2) }}</td>
                        <td>${{ number_format($cobranza->monto_total, 2) }}</td>
                        <td>
                            @if($cobranza->estatus == 'PAGADA')
                                <span class="badge badge-success">PAGADA</span>
                                @php $totalPagadas += $cobranza->monto_total; @endphp
                            @else
                                <span class="badge badge-warning">PENDIENTE</span>
                                @php $totalPendientes += $cobranza->monto_total; @endphp
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    <tr class="totales">
                        <td colspan="4">SUBTOTAL {{ strtoupper($comercializadora->nombrecomercializadora) }}</td>
                        <td>${{ number_format($cobranzas->sum('monto_preenfriado'), 2) }}</td>
                        <td>${{ number_format($cobranzas->sum('monto_conservacion'), 2) }}</td>
                        <td>${{ number_format($cobranzas->sum('monto_anden'), 2) }}</td>
                        <td>${{ number_format($subtotalComercializadora, 2) }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endforeach

    <div class="resumen-general">
        <h3>RESUMEN GENERAL</h3>
        <div class="resumen-grid">
            <div class="resumen-row">
                <div class="resumen-label">Total Preenfriado:</div>
                <div class="resumen-value">${{ number_format($totalPreenfriado, 2) }}</div>
            </div>
            <div class="resumen-row">
                <div class="resumen-label">Total Conservación:</div>
                <div class="resumen-value">${{ number_format($totalConservacion, 2) }}</div>
            </div>
            <div class="resumen-row">
                <div class="resumen-label">Total Cruce de Andén:</div>
                <div class="resumen-value">${{ number_format($totalAnden, 2) }}</div>
            </div>
            <div class="resumen-row" style="border-top: 2px solid #28a745;">
                <div class="resumen-label" style="font-size: 12pt;">TOTAL GENERAL:</div>
                <div class="resumen-value" style="font-size: 12pt; color: #155724;">${{ number_format($totalGeneral, 2) }}</div>
            </div>
            <div class="resumen-row" style="margin-top: 10px; border-top: 1px solid #999;">
                <div class="resumen-label">Cobranzas Pagadas:</div>
                <div class="resumen-value" style="color: #28a745;">${{ number_format($totalPagadas, 2) }}</div>
            </div>
            <div class="resumen-row">
                <div class="resumen-label">Cobranzas Pendientes:</div>
                <div class="resumen-value" style="color: #ffc107;">${{ number_format($totalPendientes, 2) }}</div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Documento generado el {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Sistema de Gestión de Coolers</p>
    </div>
</body>
</html>
