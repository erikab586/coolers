@extends('layouts.app')

@section('title', 'Detalle de Recepción')

@section('content')
<div class="pagetitle">
  <h1>Información de Recepción</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('recepcion.mostrar') }}">Recepciones</a></li>
      <li class="breadcrumb-item active"><a href="{{ route('recepcion.mostrar') }}">Ver Recepciones</a></li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Información de Recepción</h5>
      <table class="table table-bordered mb-4">
        <tr>
            {{-- Logo en el lado izquierdo (puedes ajustar tamaño según imagen real) --}}
            <td rowspan="3" style="width: 100px; text-align: center; vertical-align: middle;">
            <img src="{{ asset('assets/img/logo.jpg') }}" alt="Logo" style="width: 80px;">
            </td>

            {{-- Fila 1 centrada: "Recepción" --}}
            <td colspan="4" style="text-align: center; font-weight: bold; font-size: 20px;">
            RECEPCIÓN
            </td>
        </tr>

        {{-- Fila 2 con 4 columnas: Área, Clave, Emisión, Revisión --}}
        <tr style="text-align: center; font-weight: bold;">
            <td>Área</td>
            <td>Clave</td>
            <td>Emisión</td>
            <td>Revisión</td>
        </tr>
        <tr style="text-align: center;">
            <td>{{ $recepcion->area }}</td>
            <td>{{ $recepcion->datosclave }}</td>
            <td>{{ \Carbon\Carbon::parse($recepcion->fechaemision)->format('Y-m-d') }}</td>
            <td>{{ $recepcion->revision }}</td>
        </tr>

        {{-- Fila 4: 70% Cliente, 30% Fecha --}}
        <tr>
            <td colspan="3" style="font-weight: bold;">
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

      <h5 class="card-title mt-4">Detalle de Recepción</h5>
      <table class="table table-bordered">
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
          @foreach($recepcion->detalles as $detalle)
            <tr>
              <td>{{ $detalle->hora }}</td>
              <td><img src="{{ asset($detalle->fruta->imgfruta ?? 'imagenes/frutas/frutas.png') }}"alt="FrutiMax" width="40" height="40" class="rounded-circle me-2">{{ $detalle->fruta->nombrefruta ?? '—' }}</td>
              <td>{{ $detalle->variedad->tipofruta }}</td>
              <td>{{ $detalle->temperatura }}</td>
              <td>{{ $detalle->tipo}}</td>
              <td>{{ $detalle->presentacion->nombrepresentacion }}</td>
              <td>{{ $detalle->cantidad }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <!-- Sección de Firmas -->
      <div class="mt-4">
        <h5 class="card-title">
          <i class="bi bi-pen"></i> Firmas Digitales
          @if($recepcion->firma_responsable1 && $recepcion->firma_responsable2)
            <span class="badge bg-success">Completadas</span>
          @else
            <span class="badge bg-warning">Pendientes</span>
          @endif
        </h5>
        
        @if($recepcion->firma_responsable1 && $recepcion->firma_responsable2)
          <!-- Mostrar firmas existentes -->
          <div class="row">
            <div class="col-md-6 text-center mb-3">
              <div class="border rounded p-3">
                <h6>Responsable 1</h6>
                <img src="{{ $recepcion->firma_responsable1 }}" alt="Firma 1" style="max-width: 100%; height: 100px; border: 1px solid #ddd;">
                <p class="mt-2 mb-0"><strong>{{ $recepcion->nombre_responsable1 }}</strong></p>
              </div>
            </div>
            <div class="col-md-6 text-center mb-3">
              <div class="border rounded p-3">
                <h6>Responsable 2</h6>
                <img src="{{ $recepcion->firma_responsable2 }}" alt="Firma 2" style="max-width: 100%; height: 100px; border: 1px solid #ddd;">
                <p class="mt-2 mb-0"><strong>{{ $recepcion->nombre_responsable2 }}</strong></p>
              </div>
            </div>
          </div>
          
          @if($recepcion->nota_firmas)
          <div class="alert alert-info">
            <strong><i class="bi bi-info-circle"></i> Nota:</strong> {{ $recepcion->nota_firmas }}
          </div>
          @endif
          
          <div class="text-center">
            <a href="{{ route('recepcion.firmas', $recepcion->id) }}" class="btn btn-warning">
              <i class="bi bi-pencil"></i> Editar Firmas
            </a>
          </div>
        @else
          <!-- Botón para agregar firmas -->
          <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i> Esta recepción aún no tiene firmas digitales.
          </div>
          <div class="text-center">
            <a href="{{ route('recepcion.firmas', $recepcion->id) }}" class="btn btn-primary">
              <i class="bi bi-pen"></i> Agregar Firmas
            </a>
          </div>
        @endif
      </div>
    </div>
  </div>
</section>
@endsection
