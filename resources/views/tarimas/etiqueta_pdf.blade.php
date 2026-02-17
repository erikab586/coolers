<!DOCTYPE html>
<html>
<head>
    <title>Etiqueta de Tarima</title>
    <style>
        @page {
            size: 10cm 5cm;
            margin: 0;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .etiqueta {
            width: 10cm;
            height: 5cm;
            border: 2px solid black;
            box-sizing: border-box;
            position: relative;
            overflow: hidden;
        }
        /* Columna Izquierda: Logo, QR, Usuario, Código, Hora */
        .columna-izq {
            position: absolute;
            left: 0;
            top: 0;
            width: 2.5cm;
            height: 5cm;
            border-right: 2px solid black;
            text-align: center;
            padding: 3mm;
            box-sizing: border-box;
            background-color: #fff;
        }
        .logo {
            width: 100%;
            max-height: 25px;
            margin-bottom: 2mm;
        }
        .qr-code {
            width: 50px;
            height: 50px;
            margin: 2mm auto;
        }
        .qr-code svg {
            width: 100%;
            height: 100%;
        }
        .info-usuario {
            font-size: 8pt;
            font-weight: bold;
            margin: 1mm 0;
            line-height: 1.2;
        }
        .info-codigo {
            font-size: 7pt;
            font-weight: bold;
            margin: 1mm 0;
            line-height: 1.2;
        }
        .info-hora {
            font-size: 6pt;
            margin: 1mm 0;
            line-height: 1.1;
        }
        /* Columna Derecha: Detalles de Recepción */
        .columna-der {
            position: absolute;
            left: 2.5cm;
            top: 0;
            width: 7.5cm;
            height: 5cm;
            box-sizing: border-box;
            overflow: hidden;
        }
        .tabla-detalles {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
        }
        .tabla-detalles td {
            border: 1px solid #333;
            padding: 1mm;
            text-align: center;
            vertical-align: middle;
            font-size: 6pt;
            line-height: 1.1;
        }
        .tabla-detalles img {
            max-width: 15px;
            max-height: 15px;
            display: block;
            margin: 0 auto 0.5mm auto;
        }
        .tabla-detalles .presentacion {
            font-weight: bold;
            font-size: 6.5pt;
            margin-bottom: 0.5mm;
        }
        .tabla-detalles .variedad {
            font-size: 5.5pt;
            margin-bottom: 0.5mm;
        }
        .tabla-detalles .cantidad {
            font-weight: bold;
            font-size: 7pt;
            color: #000;
        }
    </style>
</head>
<body>
    @php
        $user = Auth::user();
        $tarima = $tarimas->first();
        $recepcion = $tarima?->detalle?->recepcion;
        $idRecepcion = $recepcion?->id;
        
        // Generar URL para el QR que apunte a la recepción
        if ($idRecepcion) {
            $urlRecepcion = route('recepcion.show', $idRecepcion);
        } else {
            $urlRecepcion = url('/'); // URL por defecto si no hay recepción
        }
    @endphp

    <div class="etiqueta">
        <!-- Columna Izquierda -->
        <div class="columna-izq">
            <!-- Logo -->
            <img src="{{ public_path('assets/img/logo.jpg') }}" alt="Logo" class="logo">
            
            <!-- Código QR con URL de recepción -->
            <div class="qr-code">
                {!! QrCode::size(50)->generate($urlRecepcion) !!}
            </div>
            
            <!-- Usuario -->
            <div class="info-usuario">
                U-{{ $user->id }}
            </div>
            
            <!-- Código de Tarima -->
            <div class="info-codigo">
                {{ $tarima?->tarima?->codigo ?? 'N/A' }}
            </div>
            
            <!-- Hora -->
            <div class="info-hora">
                {{ now()->format('H:i') }}<br>
                {{ now()->format('d/m/Y') }}
            </div>
        </div>
        
        <!-- Columna Derecha: Detalles -->
        <div class="columna-der">
            <table class="tabla-detalles">
                @php
                    // Agrupar productos en filas de 3
                    $chunks = $tarimas->chunk(3);
                @endphp
                
                @foreach($chunks as $chunk)
                <tr>
                    @foreach($chunk as $item)
                    <td>
                        <!-- Imagen de Fruta -->
                        @if($item->detalle->fruta->imgfruta)
                            <img src="{{ public_path($item->detalle->fruta->imgfruta) }}" alt="Fruta">
                        @endif
                        
                        <!-- Presentación -->
                        <div class="presentacion">
                            {{ $item->detalle->presentacion->nombrepresentacion ?? 'N/A' }}
                        </div>
                        
                        <!-- Variedad -->
                        <div class="variedad">
                            {{ $item->detalle->variedad->tipofruta ?? 'N/A' }}
                        </div>
                        
                        <!-- Cantidad -->
                        <div class="cantidad">
                            {{ $item->cantidad }}
                        </div>
                    </td>
                    @endforeach
                    
                    <!-- Rellenar celdas vacías si hay menos de 3 productos -->
                    @for($i = count($chunk); $i < 3; $i++)
                    <td>&nbsp;</td>
                    @endfor
                </tr>
                @endforeach
                
                <!-- No rellenar filas vacías para aprovechar espacio -->
            </table>
        </div>
    </div>
</body>
</html>
