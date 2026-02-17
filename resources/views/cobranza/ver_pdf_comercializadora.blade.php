<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Cobranzas - {{ $comercializadora->nombrecomercializadora }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10pt; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #007bff; padding-bottom: 15px; }
        .header h1 { margin: 0; font-size: 20pt; color: #007bff; }
        .header h2 { margin: 5px 0; font-size: 16pt; color: #333; }
        .comercializadora-info { background-color: #f8f9fa; padding: 15px; margin-bottom: 20px; border-radius: 5px; border-left: 5px solid #007bff; }
        .comercializadora-info h3 { margin: 0 0 10px 0; color: #007bff; }
        .info-grid { display: table; width: 100%; }
        .info-row { display: table-row; }
        .info-label { display: table-cell; font-weight: bold; padding: 5px; width: 30%; }
        .info-value { display: table-cell; padding: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 9pt; }
        th { background-color: #007bff; color: white; padding: 8px; text-align: left; font-weight: bold; }
        td { padding: 6px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .totales { background-color: #e7f3ff; font-weight: bold; border-top: 2px solid #007bff; }
        .resumen { margin-top: 25px; padding: 15px; background-color: #d4edda; border: 2px solid #28a745; border-radius: 5px; }
        .resumen h3 { margin: 0 0 15px 0; color: #155724; text-align: center; }
        .resumen-grid { display: table; width: 100%; }
        .resumen-row { display: table-row; }
        .resumen-label { display: table-cell; padding: 8px; width: 70%; font-size: 11pt; }
        .resumen-value { display: table-cell; padding: 8px; text-align: right; font-weight: bold; font-size: 11pt; }
        .resumen-total { border-top: 3px solid #28a745; font-size: 13pt; color: #155724; }
        .footer { margin-top: 30px; text-align: center; font-size: 8pt; color: #666; border-top: 1px solid #ddd; padding-top: 10px; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 3px; font-size: 8pt; font-weight: bold; }
        .badge-success { background-color: #28a745; color: white; }
        .badge-warning { background-color: #ffc107; color: #000; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE COBRANZAS</h1>
        <h2>{{ $comercializadora->nombrecomercializadora }}</h2>
        <p style="margin: 5px 0; color: #666;">Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="comercializadora-info">
        <h3>Información de la Comercializadora</h3>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nombre:</div>
                <div class="info-value">{{ $comercializadora->nombrecomercializadora }}</div>
            </div>
            @if($comercializadora->abreviatura)
            <div class="info-row">
                <div class="info-label">Abreviatura:</div>
                <div class="info-value">{{ $comercializadora->abreviatura }}</div>
            </div>
            @endif
            <div class="info-row">
                <div class="info-label">Total de Cobranzas:</div>
                <div class="info-value">{{ $cobranzas->count() }} registros</div>
            </div>
        </div>
    </div>

    <h3 style="color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 5px;">Detalle de Cobranzas</h3>
    
    <table>
        <thead>
            <tr>
                <th>Folio</th>
                <th>Fecha</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th class="text-right">Preenfriado</th>
                <th class="text-right">Conservación</th>
                <th class="text-right">Andén</th>
                <th class="text-right">Total</th>
                <th class="text-center">Estatus</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cobranzas as $cobranza)
            <tr>
                <td>{{ $cobranza->recepcion->folio ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($cobranza->fecha)->format('d/m/Y') }}</td>
                <td>
                    {{ optional($cobranza->detalleRecepcion->fruta)->nombrefruta ?? 'N/A' }} - 
                    {{ optional($cobranza->detalleRecepcion->variedad)->tipofruta ?? 'N/A' }}
                </td>
                <td>{{ $cobranza->detalleRecepcion->cantidad ?? 0 }} cajas</td>
                <td class="text-right">${{ number_format($cobranza->monto_preenfriado ?? 0, 2) }}</td>
                <td class="text-right">${{ number_format($cobranza->monto_conservacion ?? 0, 2) }}</td>
                <td class="text-right">${{ number_format($cobranza->monto_anden ?? 0, 2) }}</td>
                <td class="text-right">${{ number_format($cobranza->monto_total ?? 0, 2) }}</td>
                <td class="text-center">
                    @if($cobranza->estatus == 'PAGADA')
                        <span class="badge badge-success">PAGADA</span>
                    @else
                        <span class="badge badge-warning">PENDIENTE</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">No hay cobranzas registradas para esta comercializadora</td>
            </tr>
            @endforelse
            
            @if($cobranzas->count() > 0)
            <tr class="totales">
                <td colspan="4" class="text-right"><strong>TOTALES:</strong></td>
                <td class="text-right">${{ number_format($totalPreenfriado, 2) }}</td>
                <td class="text-right">${{ number_format($totalConservacion, 2) }}</td>
                <td class="text-right">${{ number_format($totalAnden, 2) }}</td>
                <td class="text-right">${{ number_format($totalGeneral, 2) }}</td>
                <td></td>
            </tr>
            @endif
        </tbody>
    </table>

    @if($cobranzas->count() > 0)
    <div class="resumen">
        <h3>RESUMEN FINANCIERO</h3>
        <div class="resumen-grid">
            <div class="resumen-row">
                <div class="resumen-label">Total Servicio de Preenfriado:</div>
                <div class="resumen-value">${{ number_format($totalPreenfriado, 2) }}</div>
            </div>
            <div class="resumen-row">
                <div class="resumen-label">Total Servicio de Conservación:</div>
                <div class="resumen-value">${{ number_format($totalConservacion, 2) }}</div>
            </div>
            <div class="resumen-row">
                <div class="resumen-label">Total Servicio de Cruce de Andén:</div>
                <div class="resumen-value">${{ number_format($totalAnden, 2) }}</div>
            </div>
            <div class="resumen-row resumen-total">
                <div class="resumen-label"><strong>TOTAL GENERAL:</strong></div>
                <div class="resumen-value">${{ number_format($totalGeneral, 2) }}</div>
            </div>
        </div>
        
        <div style="margin-top: 20px; padding-top: 15px; border-top: 2px solid #28a745;">
            <div class="resumen-grid">
                <div class="resumen-row">
                    <div class="resumen-label">Cobranzas Pagadas:</div>
                    <div class="resumen-value" style="color: #28a745;">${{ number_format($totalPagadas, 2) }}</div>
                </div>
                <div class="resumen-row">
                    <div class="resumen-label">Cobranzas Pendientes:</div>
                    <div class="resumen-value" style="color: #ffc107;">${{ number_format($totalPendientes, 2) }}</div>
                </div>
                <div class="resumen-row">
                    <div class="resumen-label">Cantidad de Cobranzas Pagadas:</div>
                    <div class="resumen-value">{{ $cobranzas->where('estatus', 'PAGADA')->count() }}</div>
                </div>
                <div class="resumen-row">
                    <div class="resumen-label">Cantidad de Cobranzas Pendientes:</div>
                    <div class="resumen-value">{{ $cobranzas->where('estatus', 'PENDIENTE')->count() }}</div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="footer">
        <p><strong>{{ $comercializadora->nombrecomercializadora }}</strong></p>
        <p>Sistema de Gestión de Coolers - Reporte de Cobranzas</p>
        <p>Documento generado el {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
