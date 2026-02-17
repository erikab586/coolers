@extends('layouts.app')

@section('title', 'Información de Tarimas')

@section('content')
<div class="pagetitle">
  <h1>Información de Tarimas</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('tarima.mostrar') }}">Tarimas</a></li>
      <li class="breadcrumb-item active"><a href="{{ route('tarima.mostrar') }}">Ver Tarimas</a></li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Información de Tarimas</h5>
      <table class="table table-bordered mb-4">
        <tr>
            {{-- Logo en el lado izquierdo (puedes ajustar tamaño según imagen real) --}}
            <td rowspan="3" style="width: 100px; text-align: center; vertical-align: middle;">
            <img src="{{ asset('assets/img/logo.jpg') }}" alt="Logo" style="width: 80px;">
            </td>

            {{-- Fila 1 centrada: "Recepción" --}}
            <td colspan="4" style="text-align: center; font-weight: bold; font-size: 20px;">
            Tarima
            </td>
        </tr>
        @php
            $user = Auth::user();
        @endphp
        {{-- Fila 2 con 4 columnas: Área, Clave, Emisión, Revisión --}}
        <tr style="text-align: center; font-weight: bold;">
            <td>Código</td>
            <td>Usuario</td>
            <td>Estatus</td>
            <td>Ubicación</td>
            <td>Fecha</td>
        </tr>
        <tr style="text-align: center;">
            <td>{{ $tarimas->first()?->tarima?->codigo ?? 'No encontrada' }}</td>
            <td>{{ $user->name }} {{ $user->apellidos }}</td>
            <td>{{ ucfirst($tarimas->first()?->tarima?->ubicacion ?? 'No encontrada') }}</td>
            <td>{{ ucfirst($tarimas->first()?->tarima?->estatus ?? 'Sin estatus') }}</td>
            <td>{{ $tarimas->first()?->tarima?->created_at }}</td>
        </tr>
    </table>

      <h5 class="card-title mt-4">Detalle de Tarima</h5>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Folio</th>
            <th>Fruta</th>
            <th>Presentación</th>
            <th>Variedad</th>
            <th>Comercializadora</th>
            <th>Lote</th>
            <th>Cant. Carga</th>
          </tr>
        </thead>
        <tbody>
        @foreach($tarimas as $tarim)
            <tr>
                <td>{{ $tarim->folio ?? $tarim->detalle->recepcion->folio }}</td>
                <td><img src="{{ asset($tarim->fruta->imgfruta ?? 'imagenes/frutas/frutas.png') }}"alt="FrutiMax" width="40" height="40" class="rounded-circle me-2">{{ $tarim->fruta->nombrefruta ?? '—' }}</td>
                <td>{{ $tarim->presentacion->nombrepresentacion ?? $tarim->detalle->presentacion->nombrepresentacion }}</td>
                <td>{{ $tarim->variedad->tipofruta ?? $tarim->detalle->variedad->tipofruta }}</td>
                <td>{{ $tarim->comercializadora->nombrecomercializadora ?? $tarim->detalle->recepcion->contrato->comercializadora->nombrecomercializadora }}</td>
                <td>{{ $tarim->detalle->codigo ?? $tarim->codigo}}</td>
                <td>
                    @if($tarim->cantidadcarga)
                        {{ $tarim->cantidadcarga }}
                    @else
                        Pendiente
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
</section>
@endsection
