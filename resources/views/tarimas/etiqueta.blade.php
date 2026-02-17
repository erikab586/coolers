<!DOCTYPE html>
<html>
<head>
    <title>Etiqueta de Tarima</title>
    <style>
        /* Tamaño real de la hoja para Dompdf */
        @page {
            size: 10cm 5cm;
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            width: 10cm;
            height: 5cm;
            margin: 0;
            padding: 5px;
            border: 2px dashed black;
            box-sizing: border-box;
            display: flex;
            flex-direction: row;
            gap: 3px; /* un poco menos de espacio entre columnas */
        }

        .columna-1 {
            width: 3cm; /* 30% aprox */
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 8pt;
            text-align: center;
            border: 1px solid #ccc;
            padding: 3px;
            box-sizing: border-box;
        }

        .logo {
            max-width: 100%;
            max-height: 40px;
            margin-bottom: 4px;
        }

        .qr-code {
            width: 60px;
            height: 60px;
            background: #fff;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 7pt;
            color: #666;
        }

        .info-text {
            margin-top: 2px;
        }

        .columna-2 {
            width: 7cm; /* 70% aprox */
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr; /* 2x2 = 4 celdas máximo */
            gap: 4px;
            border: 1px solid #ccc;
            padding: 4px;
            box-sizing: border-box;
            font-size: 7.5pt;
            overflow: hidden;
        }

        .grid-cell {
            border: 1px solid #bbb;
            padding: 4px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 7pt;
            overflow-wrap: break-word;
        }

        .grid-cell img {
            max-width: 12px;
            max-height: 12px;
            object-fit: contain;
            margin-bottom: 4px;
        }
    </style>
</head>
<body>

    @php
      $user = Auth::user();
      $tarima = $tarimas->first();
    @endphp

    <div class="columna-1">
        {{-- Logo --}}
        <img src="{{ asset('assets/img/logo.jpg') }}" alt="Logo" class="logo">

        {{-- Código QR --}}
        <div class="qr-code">
            {!! QrCode::size(40)->generate($qrContent) !!}
        </div>

        {{-- Usuario --}}
        <div class="info-text">
            <strong>U-</strong> {{ $user->id }}
        </div>

        {{-- Tarima --}}
        <div class="info-text">
            {{ $tarima?->tarima?->codigo ?? 'Sin tarima' }}
        </div>

        {{-- Hora --}}
        <div class="info-text">
            {{ now()->format('H:i') }}
        </div>

        {{-- Fecha --}}
        <div class="info-text">
            {{ now()->format('d/m/Y') }}
        </div>
    </div>

    <div class="columna-2">
        @foreach($tarimas as $tarim)
            <div class="grid-cell">
                {{-- Folio --}}
                <strong>Folio: {{ $tarim->detalle->recepcion->folio ?? '-' }}</strong><br>

                {{-- Imagen fruta --}}
                @if($tarim->detalle->fruta->imgfruta)
                    <img src="{{ asset($tarim->detalle->fruta->imgfruta) }}" alt="Fruta">
                @endif

                {{-- Variedad --}}
                {{ $tarim->detalle->variedad->tipofruta ?? '' }}<br>

                {{-- Presentación --}}
                {{ $tarim->detalle->presentacion->nombrepresentacion ?? '' }}<br>

                {{-- Cantidad --}}
                Cant: {{ $tarim->cantidad }}
            </div>
        @endforeach
    </div>

</body>
</html>
