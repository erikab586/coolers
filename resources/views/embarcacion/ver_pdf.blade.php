<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Embarcación #{{ $embarcacion->id }}</title>
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
        .info-row {
            text-align: left;
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
        .firma-section {
            margin-top: 40px;
            display: table;
            width: 100%;
        }
        .firma-box {
            display: table-cell;
            width: 33%;
            text-align: center;
            padding: 10px;
        }
        .firma-line {
            border-top: 2px solid #000;
            margin-top: 50px;
            padding-top: 5px;
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
                EMBARCACIÓN
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
            <td>{{ optional($embarcacion->detalles->first()->conservacion->detallesConservacion->first()->detalleRecepcion->recepcion)->area ?? '—' }}</td>
            <td>F-BCM-PRO-06</td>
            <td>{{ optional($embarcacion->detalles->first()->conservacion->detallesConservacion->first()->detalleRecepcion->recepcion)->fechaemision ? \Carbon\Carbon::parse($embarcacion->detalles->first()->conservacion->detallesConservacion->first()->detalleRecepcion->recepcion->fechaemision)->format('Y-m-d') : '—' }}</td>
            <td>02</td>
        </tr>
        
        <tr>
            <td colspan="1">Folio:{{$embarcacion->folio}}</td>
            <td colspan="3" class="client-info">
                Cliente:{{ optional($embarcacion->detalles->first()->conservacion->detallesConservacion->first()->detalleRecepcion->recepcion->contrato->comercializadora)->nombrecomercializadora ?? '—' }}
            </td>
            <td colspan="1" style="text-align: left;">
                Fecha: {{ \Carbon\Carbon::now()->format('Y-m-d') }}
            </td>
        </tr>
    </table>

    <!-- Tabla de detalles (usando TarimaDetarec / iddetalletarima) -->
    <table class="details-table">
            <thead>
                <tr>
                    <th>Comercializadora</th>
                    <th>Fruta</th>
                    <th>Variedad</th>
                    <th>Presentación</th>
                    <th>Cantidad</th>
                    <th>Tarima</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $tieneProductos = false;
                @endphp
                @foreach($embarcacion->detalles as $detalle)
                    @php
                        $tarimaDet = $detalle->tarimaDetarec ?? null;
                        $detRecep  = $tarimaDet && $tarimaDet->detalle ? $tarimaDet->detalle : null;
                        $recepcion = $detRecep && $detRecep->recepcion ? $detRecep->recepcion : null;
                    @endphp
                    @if($tarimaDet && $detRecep)
                        @php $tieneProductos = true; @endphp
                        <tr>
                            <td>{{ optional($tarimaDet->comercializadora)->nombrecomercializadora ?? (optional($recepcion->contrato->comercializadora)->nombrecomercializadora ?? 'N/A') }}</td>
                            <td>{{ $tarimaDet->fruta->nombrefruta ?? ($detRecep->fruta->nombrefruta ?? 'N/A') }}</td>
                            <td>{{ $tarimaDet->variedad->tipofruta ?? ($detRecep->variedad->tipofruta ?? 'N/A') }}</td>
                            <td>{{ $tarimaDet->presentacion->nombrepresentacion ?? ($detRecep->presentacion->nombrepresentacion ?? 'N/A') }}</td>
                            <td>{{ $tarimaDet->cantidadcarga ?? 0 }}</td>
                            <td>{{ $tarimaDet->tarima->codigo ?? 'N/A' }}</td>
                        </tr>
                    @endif
                @endforeach

                @if(!$tieneProductos)
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px;">
                            No hay productos embarcados
                        </td>
                    </tr>
                @endif
            </tbody>
    </table>

    <!-- Tabla de Totales -->
    <table style="width: 100%; border-collapse: collapse; margin-top: 15px; border: 1px solid #000;">
        <thead>
            <tr>
                <th style="background-color: #343a40; color: white; padding: 6px; text-align: center; font-size: 9pt; border: 1px solid #000;">
                    Fruta
                </th>
                <th style="background-color: #343a40; color: white; padding: 6px; text-align: center; font-size: 9pt; border: 1px solid #000;">
                    Variedad
                </th>
                <th style="background-color: #343a40; color: white; padding: 6px; text-align: center; font-size: 9pt; border: 1px solid #000;">
                    Presentación
                </th>
                <th style="background-color: #343a40; color: white; padding: 6px; text-align: center; font-size: 9pt; border: 1px solid #000;">
                    Total
                </th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalesPorProducto = [];
                foreach($embarcacion->detalles as $detalle) {
                    $tarimaDet = $detalle->tarimaDetarec ?? null;
                    $detRecep  = $tarimaDet && $tarimaDet->detalle ? $tarimaDet->detalle : null;
                    if ($tarimaDet && $detRecep) {
                        $fruta        = $tarimaDet->fruta->nombrefruta ?? ($detRecep->fruta->nombrefruta ?? 'N/A');
                        $variedad     = $tarimaDet->variedad->tipofruta ?? ($detRecep->variedad->tipofruta ?? 'N/A');
                        $presentacion = $tarimaDet->presentacion->nombrepresentacion ?? ($detRecep->presentacion->nombrepresentacion ?? 'N/A');
                        $cantidad     = $tarimaDet->cantidadcarga ?? 0;

                        $key = $fruta . '|' . $variedad . '|' . $presentacion;

                        if (!isset($totalesPorProducto[$key])) {
                            $totalesPorProducto[$key] = [
                                'fruta' => $fruta,
                                'variedad' => $variedad,
                                'presentacion' => $presentacion,
                                'total' => 0
                            ];
                        }
                        $totalesPorProducto[$key]['total'] += $cantidad;
                    }
                }
            @endphp
            
            @foreach($totalesPorProducto as $producto)
            <tr>
                <td style="border: 1px solid #ddd; padding: 5px; font-size: 9pt; text-align: center;">
                    {{ $producto['fruta'] }}
                </td>
                <td style="border: 1px solid #ddd; padding: 5px; font-size: 9pt; text-align: center;">
                    {{ $producto['variedad'] }}
                </td>
                <td style="border: 1px solid #ddd; padding: 5px; font-size: 9pt; text-align: center;">
                    {{ $producto['presentacion'] }}
                </td>
                <td style="border: 1px solid #ddd; padding: 5px; font-size: 9pt; text-align: center; font-weight: bold;">
                    {{ $producto['total'] }}
                </td>
            </tr>
            @endforeach
            
            <tr style="background-color: #f8f9fa;">
                <td colspan="3" style="border: 1px solid #000; padding: 6px; font-size: 9pt; text-align: right; font-weight: bold;">
                    TOTAL GENERAL:
                </td>
                <td style="border: 1px solid #000; padding: 6px; font-size: 9pt; text-align: center; font-weight: bold;">
                    {{ array_sum(array_column($totalesPorProducto, 'total')) }}
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Sección de 4 tablas pequeñas -->
    <div style="margin-top: 20px; display: table; width: 100%;">
        <!-- Fila 1: Información del Transporte y Condición de Transporte -->
        <div style="display: table-row;">
            <!-- Tabla 1: Información del Transporte -->
            <div style="display: table-cell; width: 48%; padding-right: 10px; vertical-align: top;">
                <table style="width: 100%; border-collapse: collapse; border: 1px solid #000;">
                    <tr>
                        <th colspan="2" style="background-color: #343a40; color: white; padding: 6px; text-align: center; font-size: 9pt; border: 1px solid #000;">
                            INFORMACIÓN DEL TRANSPORTE
                        </th>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt; width: 40%; background-color: #f8f9fa;">
                            <strong>Placa del Tracto:</strong>
                        </td>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt;">
                            {{ $embarcacion->trans_placa ?? 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt; background-color: #f8f9fa;">
                            <strong>Placa de la Caja:</strong>
                        </td>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt;">
                            {{ $embarcacion->trans_placacaja ?? 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt; background-color: #f8f9fa;">
                            <strong>Temperatura de la Caja (°C/°F):</strong>
                        </td>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt;">
                            {{ $embarcacion->trans_temperaturacaja ?? 'N/A' }}
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Tabla 2: Condición de Transporte -->
            <div style="display: table-cell; width: 48%; padding-left: 10px; vertical-align: top;">
                <table style="width: 100%; border-collapse: collapse; border: 1px solid #000;">
                    <tr>
                        <th colspan="2" style="background-color: #343a40; color: white; padding: 6px; text-align: center; font-size: 9pt; border: 1px solid #000;">
                            CONDICIÓN DE TRANSPORTE
                        </th>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt; width: 50%; background-color: #f8f9fa;">
                            <strong>En buen estado</strong>
                        </td>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt;">
                            @if($embarcacion->condtrans_estado == 1)
                                 Sí 
                            @else
                                 No
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt; background-color: #f8f9fa;">
                            <strong>Limpio y libre de malos olores</strong>
                        </td>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt;">
                             @if($embarcacion->condtrans_higiene == 1)
                                Sí 
                            @else
                                No
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt; background-color: #f8f9fa;">
                            <strong>Sin presencia de plagas</strong>
                        </td>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt;">
                            @if($embarcacion->condtrans_plagas == 1)
                                Sí 
                            @else
                                No
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt; background-color: #f8f9fa;">
                            <strong>Producto de última carga:</strong>
                        </td>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt;">
                           {{ $embarcacion->prod_ultimacarga }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Fila 2: Condición de Tarimas e Información de la Carga -->
        <div style="display: table-row;">
            <!-- Tabla 3: Condición de Tarimas -->
            <div style="display: table-cell; width: 48%; padding-right: 10px; padding-top: 15px; vertical-align: top;">
                <table style="width: 100%; border-collapse: collapse; border: 1px solid #000;">
                    <tr>
                        <th colspan="2" style="background-color: #343a40; color: white; padding: 6px; text-align: center; font-size: 9pt; border: 1px solid #000;">
                            CONDICIÓN DE TARIMAS
                        </th>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt; width: 50%; background-color: #f8f9fa;">
                            <strong>Remontado Correcto:</strong>
                        </td>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt;">
                            @if($embarcacion->condtar_desmontado == 1)
                                Sí 
                            @else
                                No
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt; background-color: #f8f9fa;">
                            <strong>Flejado Correcto:</strong>
                        </td>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt;">
                            @if($embarcacion->condtar_flejado == 1)
                                Sí 
                            @else
                                No
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt; background-color: #f8f9fa;">
                            <strong>Distribución del embarque:</strong>
                        </td>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt;">
                            @if($embarcacion->condtar_distribucion == 1)
                                Sí 
                            @else
                                No
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Tabla 4: Información de la Carga -->
            <div style="display: table-cell; width: 48%; padding-left: 10px; padding-top: 15px; vertical-align: top;">
                <table style="width: 100%; border-collapse: collapse; border: 1px solid #000;">
                    <tr>
                        <th colspan="2" style="background-color: #343a40; color: white; padding: 6px; text-align: center; font-size: 9pt; border: 1px solid #000;">
                            INFORMACIÓN DE LA CARGA
                        </th>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt; width: 50%; background-color: #f8f9fa;">
                            <strong>Hora de llegada:</strong>
                        </td>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt;">
                            {{ $embarcacion->infcarga_hrallegada }}
                        </td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt; background-color: #f8f9fa;">
                            <strong>Hora de Carga:</strong>
                        </td>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt;">
                            {{ $embarcacion->infcarga_hracarga  }}
                        </td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt; background-color: #f8f9fa;">
                            <strong>Hora de Salida:</strong>
                        </td>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt;">
                            {{ $embarcacion->infcarga_hrasalida  }}
                        </td>
                    </tr>
                     <tr>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt; background-color: #f8f9fa;">
                            <strong>N° Sello:</strong>
                        </td>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt;">
                            {{ $embarcacion->infcarga_nsello  }}
                        </td>
                    </tr>
                     <tr>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt; background-color: #f8f9fa;">
                            <strong>Chismografo:</strong>
                        </td>
                        <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt;">
                            {{ $embarcacion->infcarga_nchismografo  }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Sección de Responsables y Línea de Transporte -->
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px; border: 1px solid #000;">
        <thead>
            <tr>
                <th colspan="4" style="background-color: #343a40; color: white; padding: 6px; text-align: center; font-size: 9pt; border: 1px solid #000;">
                    RESPONSABLES Y LÍNEA DE TRANSPORTE
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt; width: 25%; background-color: #f8f9fa; font-weight: bold;">
                    Responsable Cooler:
                </td>
                <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt; width: 25%;">
                    {{ $embarcacion->usuario->name ?? 'N/A' }}
                </td>
                <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt; width: 25%; background-color: #f8f9fa; font-weight: bold;">
                    Responsable Cliente:
                </td>
                <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt; width: 25%;">
                    {{ $embarcacion->nombre_responsblecliente ?? 'N/A' }} {{ $embarcacion->apellido_responsablecliente ?? '' }}
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt; background-color: #f8f9fa; font-weight: bold;">
                    Responsable Chofer:
                </td>
                <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt;">
                    {{ $embarcacion->nombre_responsblechofer ?? 'N/A' }} {{ $embarcacion->apellido_responsablechofer ?? '' }}
                </td>
                <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt; background-color: #f8f9fa; font-weight: bold;">
                    Línea de Transporte:
                </td>
                <td style="border: 1px solid #ddd; padding: 5px; font-size: 8pt;">
                    {{ $embarcacion->linea_transporte ?? 'N/A' }}
                </td>
            </tr>
        </tbody>
    </table>

    @if($embarcacion->firma_usuario && $embarcacion->firma_cliente && $embarcacion->firma_chofer)
    <!-- Sección de Firmas Digitales -->
    <div style="margin-top: 30px;">
        <table style="width: 100%; margin-top: 10px; border-collapse: collapse;">
            <tr>
                <td style="width: 33.33%; text-align: center; padding: 10px; vertical-align: top;">
                    <div style="border: 1px solid #ddd; padding: 10px;">
                        <img src="{{ $embarcacion->firma_usuario }}" style="max-width: 150px; height: 70px;">
                        <p style="margin-top: 10px; margin-bottom: 5px;"><strong>{{ $embarcacion->usuario->name ?? 'Usuario' }}</strong></p>
                        <p style="margin: 0; font-size: 9pt; color: #666;">Firma Usuario</p>
                    </div>
                </td>
                <td style="width: 33.33%; text-align: center; padding: 10px; vertical-align: top;">
                    <div style="border: 1px solid #ddd; padding: 10px;">
                        <img src="{{ $embarcacion->firma_cliente }}" style="max-width: 150px; height: 70px;">
                        <p style="margin-top: 10px; margin-bottom: 5px;"><strong>{{ $embarcacion->nombre_responsblecliente }} {{ $embarcacion->apellido_responsablecliente }}</strong></p>
                        <p style="margin: 0; font-size: 9pt; color: #666;">Firma Cliente</p>
                    </div>
                </td>
                <td style="width: 33.33%; text-align: center; padding: 10px; vertical-align: top;">
                    <div style="border: 1px solid #ddd; padding: 10px;">
                        <img src="{{ $embarcacion->firma_chofer }}" style="max-width: 150px; height: 70px;">
                        <p style="margin-top: 10px; margin-bottom: 5px;"><strong>{{ $embarcacion->nombre_responsblechofer }} {{ $embarcacion->apellido_responsablechofer }}</strong></p>
                        <p style="margin: 0; font-size: 9pt; color: #666;">Firma Chofer</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>Documento generado el {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
