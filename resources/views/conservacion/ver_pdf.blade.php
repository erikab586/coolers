<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Conservación - Tarima {{ $conservacion->tarima->codigo }}</title>
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
            background-color: #f8f9fa;
        }
        .header-label {
            background-color: #e9ecef;
            font-weight: bold;
            text-align: center;
            font-size: 9pt;
        }
        .header-value {
            text-align: center;
            font-size: 9pt;
        }
        .client-info {
            text-align: left;
            font-weight: bold;
            padding: 8px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .details-table th {
            background-color: #343a40;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 9pt;
            border: 1px solid #000;
        }
        .details-table td {
            padding: 6px;
            font-size: 9pt;
            border: 1px solid #ddd;
        }
        .details-table tr:nth-child(even) {
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
        .summary {
            margin-top: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
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
                CONSERVACIÓN
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
            <td>Conservación</td>
            <td>F-BCM-PRO-04</td>
            <td>{{ $conservacion->tarima->tarimaDetarec->first()->detalle->recepcion->fechaemision ? \Carbon\Carbon::parse($conservacion->tarima->tarimaDetarec->first()->detalle->recepcion->fechaemision)->format('Y-m-d') : '—' }}</td>
            <td>{{ $conservacion->tarima->tarimaDetarec->first()->detalle->recepcion->revision ?? '—' }}</td>
        </tr>
        
        <!-- Fila de cliente, folio y fecha -->
        <tr>
            <td colspan="3">
                Tarima: {{ $conservacion->tarima->codigo }}
            </td>
            <td colspan="2">
                Fecha: {{ \Carbon\Carbon::now()->format('Y-m-d') }}
            </td>
        </tr>
    </table>

    <!-- Tabla de detalles -->
    <table class="details-table">
            <thead>
                <tr>
                    <th>Recepción</th>
                    <th>Fruta</th>
                    <th>Variedad</th>
                    <th>Presentación</th>
                    <th>Cantidad</th>
                    <th>Hora Entrada</th>
                    <th>Temp. Entrada</th>
                    <th>Hora Salida</th>
                    <th>Temp. Salida</th>
                    <th>Tiempo Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($conservacion->detallesConservacion as $detalle)
                    @php
                        if ($detalle->hora_entrada && $detalle->hora_salida) {
                            $entrada = \Carbon\Carbon::parse($detalle->hora_entrada);
                            $salida  = \Carbon\Carbon::parse($detalle->hora_salida);

                            if ($salida->lessThan($entrada)) {
                                $salida->addDay();
                            }

                            // SIEMPRE POSITIVO
                            $minTotales = abs($salida->diffInMinutes($entrada, false));

                            $horas = intdiv($minTotales, 60);
                            $minRestantes = $minTotales % 60;
                            $textoTiempo = $horas . 'h ' . $minRestantes . 'm';
                        } else {
                            $textoTiempo = 'Pendiente';
                        }
                @endphp
                <tr>
                    <td>{{ $detalle->detalleRecepcion->recepcion->folio ?? 'N/A' }}</td>
                    <td>{{ $detalle->detalleRecepcion->fruta->nombrefruta ?? 'N/A' }}</td>
                    <td>{{ $detalle->detalleRecepcion->variedad->tipofruta ?? 'N/A' }}</td>
                    <td>{{ $detalle->detalleRecepcion->presentacion->nombrepresentacion ?? 'N/A' }}</td>
                    <td>{{ $detalle->tarimaDetarec->cantidadcarga }}</td>
                    <td>{{ $detalle->hora_entrada ?? 'N/A' }}</td>
                    <td>{{ $detalle->temperatura_entrada ?? 'N/A' }}°C</td>
                    <td>{{ $detalle->hora_salida ?? 'Pendiente' }}</td>
                    <td>{{ $detalle->temperatura_salida ?? 'N/A' }}°C</td>
                    <td>{{ $textoTiempo }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 20px;">
                        No hay productos registrados
                    </td>
                </tr>
                @endforelse
            </tbody>
    </table>

    <!-- Resumen -->
    

    @if($conservacion->firma_responsable1 && $conservacion->firma_responsable2)
    <!-- Sección de Firmas -->
    <div style="margin-top: 30px;">
        <h3 style="background-color: #f0f0f0; padding: 8px; border-left: 4px solid #6c757d;">
            FIRMAS DE RESPONSABLES
        </h3>
        <table style="width: 100%; margin-top: 10px; border-collapse: collapse;">
            <tr>
                <td style="width: 50%; text-align: center; padding: 10px; vertical-align: top;">
                    <div style="border: 1px solid #ddd; padding: 10px;">
                        <img src="{{ $conservacion->firma_responsable1 }}" style="max-width: 200px; height: 80px;">
                        <p style="margin-top: 10px; margin-bottom: 5px;"><strong>{{ $conservacion->nombre_responsable1 }}</strong></p>
                        <p style="margin: 0; font-size: 9pt; color: #666;">Responsable 1</p>
                    </div>
                </td>
                <td style="width: 50%; text-align: center; padding: 10px; vertical-align: top;">
                    <div style="border: 1px solid #ddd; padding: 10px;">
                        <img src="{{ $conservacion->firma_responsable2 }}" style="max-width: 200px; height: 80px;">
                        <p style="margin-top: 10px; margin-bottom: 5px;"><strong>{{ $conservacion->nombre_responsable2 }}</strong></p>
                        <p style="margin: 0; font-size: 9pt; color: #666;">Responsable 2</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    @if($conservacion->nota_firmas)
    <div style="margin-top: 15px; padding: 10px; background-color: #fffacd; border: 1px solid #ddd;">
        <strong>NOTA:</strong> {{ $conservacion->nota_firmas }}
    </div>
    @endif
    @endif

    <div class="footer">
        <p>Documento generado el {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
