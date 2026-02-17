<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Recepción - {{ $recepcion->folio }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            margin: 20px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .header-table td {
            border: 1px solid #000;
            padding: 8px;
        }
        .logo-cell {
            width: 100px;
            text-align: center;
            vertical-align: middle;
        }
        .logo-cell img {
            width: 80px;
            height: auto;
        }
        .title-cell {
            text-align: center;
            font-weight: bold;
            font-size: 20px;
            vertical-align: middle;
        }
        .header-label {
            text-align: center;
            font-weight: bold;
            background-color: #f0f0f0;
        }
        .header-value {
            text-align: center;
        }
        .client-info {
            font-weight: bold;
        }
        
        /* Tabla de detalles */
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 30px;
        }
        .detail-table th {
            background-color: #343a40;
            color: white;
            padding: 10px;
            text-align: center;
            border: 1px solid #000;
            font-size: 10pt;
        }
        .detail-table td {
            padding: 8px;
            border: 1px solid #000;
            text-align: center;
            font-size: 9pt;
        }
        .detail-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        /* Sección de firmas */
        .signatures-section {
            margin-top: 40px;
            page-break-inside: avoid;
        }
        .signatures-title {
            font-weight: bold;
            font-size: 12pt;
            margin-bottom: 20px;
            text-align: center;
        }
        .signatures-container {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .signature-box {
            display: table-cell;
            width: 48%;
            text-align: center;
            vertical-align: top;
            padding: 10px;
        }
        .signature-box:first-child {
            padding-right: 20px;
        }
        .signature-image {
            border: 1px solid #000;
            height: 100px;
            margin-bottom: 10px;
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .signature-image img {
            max-width: 100%;
            max-height: 100%;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 10px;
            padding-top: 5px;
            font-weight: bold;
        }
        .signature-label {
            font-size: 9pt;
            color: #666;
            margin-top: 5px;
        }
        
        /* Nota */
        .note-section {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #000;
            background-color: #fffacd;
            page-break-inside: avoid;
        }
        .note-title {
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 10px;
        }
        .note-content {
            font-size: 9pt;
            line-height: 1.5;
        }
        
        /* Footer */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8pt;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Header con logo y datos -->
    <table class="header-table">
        <tr>
            <!-- Logo -->
            <td rowspan="3" class="logo-cell">
                @php
                    $logoPath = public_path('assets/img/logo.jpg');
                    $logoExists = file_exists($logoPath);
                @endphp
                @if($logoExists)
                    <img src="{{ $logoPath }}" alt="Logo">
                @else
                    <div style="width: 80px; height: 80px; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                        LOGO
                    </div>
                @endif
            </td>
            
            <!-- Título -->
            <td colspan="4" class="title-cell">
                RECEPCIÓN
            </td>
        </tr>
        
        <!-- Fila de etiquetas -->
        <tr class="header-label">
            <td>Área</td>
            <td>Clave</td>
            <td>Emisión</td>
            <td>Revisión</td>
        </tr>
        
        <!-- Fila de valores -->
        <tr class="header-value">
            <td>{{ $recepcion->area ?? '—' }}</td>
            <td>{{ $recepcion->datosclave ?? '—' }}</td>
            <td>{{ $recepcion->fechaemision ? \Carbon\Carbon::parse($recepcion->fechaemision)->format('Y-m-d') : '—' }}</td>
            <td>{{ $recepcion->revision ?? '—' }}</td>
        </tr>
        
        <!-- Fila de cliente, folio y fecha -->
        <tr>
            <td colspan="3" class="client-info">
                Cliente: {{ $recepcion->contrato->comercializadora->nombrecomercializadora ?? '—' }}
            </td>
            <td style="text-align: left;">
                Folio: {{ $recepcion->folio }}
            </td>
            <td style="text-align: left;">
                Fecha: {{ \Carbon\Carbon::now()->format('Y-m-d') }}
            </td>
        </tr>
    </table>

    <!-- Tabla de detalles -->
    <h3 style="margin-top: 20px; margin-bottom: 10px;">Detalle de Recepción</h3>
    <table class="detail-table">
        <thead>
            <tr>
                <th>Hora</th>
                <th>Fruta</th>
                <th>Variedad</th>
                <th>Temperatura</th>
                <th>Unidad</th>
                <th>Presentación</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recepcion->detalles as $detalle)
            <tr>
                <td>{{ $detalle->hora ?? '—' }}</td>
                <td><img src="{{ asset($detalle->fruta->imgfruta ?? 'imagenes/frutas/frutas.png') }}"alt="FrutiMax" width="40" height="40" class="rounded-circle me-2">{{ $detalle->fruta->nombrefruta ?? '—' }}</td>
                <td>{{ $detalle->variedad->tipofruta ?? '—' }}</td>
                <td>{{ $detalle->temperatura ?? '—' }}</td>
                <td>{{ $detalle->tipo ?? '—' }}</td>
                <td>{{ $detalle->presentacion->nombrepresentacion ?? '—' }}</td>
                <td>{{ $detalle->cantidad ?? '—' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 20px;">
                    No hay productos registrados
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Sección de firmas -->
    <div class="signatures-section">
        <div class="signatures-title">FIRMAS DE RESPONSABLES</div>
        
        <div class="signatures-container">
            <!-- Firma Responsable 1 -->
            <div class="signature-box">
                <div class="signature-image">
                    @if($recepcion->firma_responsable1)
                        <img src="{{ $recepcion->firma_responsable1 }}" alt="RESPONSABLE DE RECEPCIÓN">
                    @else
                        <span style="color: #ccc;">Sin firma</span>
                    @endif
                </div>
                <div class="signature-line">RESPONSABLE DE RECEPCIÓN</div>
                <div class="signature-label">BONUM COOLERS DE MÉXICO</div>
            </div>
            
            <!-- Firma Responsable 2 -->
            <div class="signature-box">
                <div class="signature-image">
                    @if($recepcion->firma_responsable2)
                        <img src="{{ $recepcion->firma_responsable2 }}" alt="FIRMA DE CONFORMIDAD ">
                    @else
                        <span style="color: #ccc;">Sin firma</span>
                    @endif
                </div>
                <div class="signature-line">FIRMA DE CONFORMIDAD</div>
                <div class="signature-label">CLIENTE</div>
            </div>
        </div>
    </div>

    <!-- Nota -->
    @if($recepcion->nota_firmas)
    <div class="note-section">
        <div class="note-title">NOTA:</div>
        <div class="note-content">
            {{ $recepcion->nota_firmas }}
        </div>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Documento generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
