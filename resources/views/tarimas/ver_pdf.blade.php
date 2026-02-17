<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Detalle de Tarima - {{ $tarima->codigo }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18pt;
            color: #333;
        }
        .info-section {
            margin-bottom: 15px;
        }
        .info-section h3 {
            background-color: #f0f0f0;
            padding: 8px;
            margin: 10px 0 5px 0;
            font-size: 12pt;
            border-left: 4px solid #007bff;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 5px;
            width: 30%;
            border-bottom: 1px solid #ddd;
        }
        .info-value {
            display: table-cell;
            padding: 5px;
            border-bottom: 1px solid #ddd;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #007bff;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 10pt;
        }
        td {
            padding: 6px;
            border-bottom: 1px solid #ddd;
            font-size: 9pt;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9pt;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DETALLE DE TARIMA</h1>
        <p><strong>Código:</strong> {{ $tarima->codigo }}</p>
    </div>

    <div class="info-section">
        <h3>Información General</h3>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Código de Tarima:</div>
                <div class="info-value">{{ $tarima->codigo }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Capacidad:</div>
                <div class="info-value">{{ $tarima->capacidad }} cajas</div>
            </div>
            <div class="info-row">
                <div class="info-label">Ubicación:</div>
                <div class="info-value">{{ strtoupper($tarima->ubicacion) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Estatus:</div>
                <div class="info-value">{{ strtoupper($tarima->estatus) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Fecha de Creación:</div>
                <div class="info-value">{{ $tarima->created_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    </div>

    <div class="info-section">
        <h3>Productos en la Tarima</h3>
        <table>
            <thead>
                <tr>
                    <th>Comercializadora</th>
                    <th>Fruta</th>
                    <th>Variedad</th>
                    <th>Presentación</th>
                    <th>Cantidad</th>
                    <th>Tipo Pallet</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tarimas as $item)
                <tr>
                    <td>{{ $item->detalle->recepcion->contrato->comercializadora->nombrecomercializadora ?? 'N/A' }}</td>
                    <td><img src="{{ asset($item->fruta->imgfruta ?? 'imagenes/frutas/frutas.png') }}"alt="FrutiMax" width="40" height="40" class="rounded-circle me-2">{{ $item->fruta->nombrefruta ?? '—' }}</td>
                    <td>{{ $item->detalle->variedad->tipofruta ?? 'N/A' }}</td>
                    <td>{{ $item->detalle->presentacion->nombrepresentacion ?? 'N/A' }}</td>
                    <td>{{ $item->cantidad }}</td>
                    <td>{{ $item->tipopallet->tipopallet ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="margin-top: 10px;"><strong>Total de productos:</strong> {{ $tarimas->count() }}</p>
        <p><strong>Total de cajas:</strong> {{ $tarimas->sum('cantidad') }}</p>
    </div>

    <div class="footer">
        <p>Documento generado el {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
