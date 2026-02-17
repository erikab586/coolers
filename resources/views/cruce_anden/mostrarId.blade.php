@extends('layouts.app')

@section('title', 'Detalle de Cruce de Andén')

@section('content')
<div class="pagetitle">
  <h1>Detalle de Cruce de Andén</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('cruce_anden.mostrar') }}">Cruce de Andén</a></li>
      <li class="breadcrumb-item active">Detalle</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Información de Cruce de Andén</h5>
          <table class="table table-bordered">
                <tr>
                    <!-- Logo -->
                    <td rowspan="3" class="logo-cell"
                        style="width: 90px; text-align:center; vertical-align:middle; padding:4px;">
                        <img src="{{ asset('assets/img/logo.jpg') }}"
                            alt="Logo"
                            style="max-width: 80px; max-height: 80px;">
                    </td>

                    <!-- Título -->
                      <td colspan="4" style="text-align: center; font-weight: bold; font-size: 20px;">
                        Cruce de Andén
                    </td>
                </tr>

                <!-- Fila de etiquetas -->
                <tr style="text-align: center; font-weight: bold;">
                    <td>Área</td>
                    <td>Clave</td>
                    <td>Emisión</td>
                    <td>Revisión</td>
                </tr>

                <!-- Fila de valores -->
                <tr class="header-value">
                    <td>Cruce de Andén</td>
                    <td>F-BCM-PRO-04</td>
                    <td>{{ $cruceAnden->tarima->tarimaDetarec->first()->detalle->recepcion->fechaemision ? \Carbon\Carbon::parse($cruceAnden->tarima->tarimaDetarec->first()->detalle->recepcion->fechaemision)->format('Y-m-d') : '—' }}</td>
                    <td>{{ $cruceAnden->tarima->tarimaDetarec->first()->detalle->recepcion->revision ?? '—' }}</td>
                </tr>
                            
                <!-- Fila de cliente, folio y fecha -->
                <tr>
                    <td colspan="3"style="text-align: left;">
                        Tarima: {{ $cruceAnden->tarima->codigo }}
                    </td>
                    <td colspan="2"style="text-align: left;">
                        Fecha: {{ \Carbon\Carbon::now()->format('Y-m-d') }}
                    </td>
                </tr>
            </table>
            <h5 class="card-title mt-4">Detalle de Cruce de Ánden</h5>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Folio</th>
                <th>Fruta</th>
                <th>Presentación</th>
                <th>Variedad</th>
                <th>Cantidad</th>
                <th>Cámara</th>
                <th>Hora Entrada</th>
                <th>Temp. Entrada</th>
                <th>Hora Salida</th>
                <th>Temp. Salida</th>
                <th>Tiempo Total</th>
              </tr>
            </thead>
            <tbody>
              @foreach($cruceAnden->tarima->tarimaDetarec as $tarimaDetalle)
                @php
                  $detalle = $tarimaDetalle->detalle;
                  $detalleCA = $cruceAnden->detallesCruceAnden->where('iddetalle', $detalle->id)->first();
                @endphp
                <tr>
                  <td>{{ $detalle->recepcion->folio }}</td>
                  <td>{{ $detalle->fruta->nombrefruta ?? 'N/A' }}</td>
                  <td>{{ $detalle->presentacion->nombrepresentacion ?? 'N/A' }}</td>
                  <td>{{ $detalle->variedad->tipofruta ?? 'N/A' }}</td>
                  <td>{{ $tarimaDetalle->cantidadcarga }}</td>
                  <td>{{ $cruceAnden->camara->codigo }}</td>
                  <td>
                    @if($detalleCA && $detalleCA->hora_entrada)
                      {{ $detalleCA->hora_entrada }}
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td>
                    @if($detalleCA && $detalleCA->temperatura_entrada)
                      {{ $detalleCA->temperatura_entrada }}°C
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td>
                    @if($detalleCA && $detalleCA->hora_salida)
                      {{ $detalleCA->hora_salida }}
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td>
                    @if($detalleCA && $detalleCA->temperatura_salida)
                      {{ $detalleCA->temperatura_salida }}°C
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td class="text-center">
                      @if($detalleCA && $detalleCA->tiempototal)
                          @php
                              $mins = $detalleCA->tiempototal;
                              $h = intdiv($mins, 60);
                              $m = $mins % 60;
                          @endphp
                          {{ $h }}h {{ $m }}m
                      @else
                          Pendiente
                      @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>

          <div class="mt-3">
            <a href="{{ route('cruce_anden.mostrar') }}" class="btn btn-secondary">
              <i class="bi bi-arrow-left me-2"></i>Volver al Listado
            </a>
            <a href="{{ route('cruce_anden.editar', $cruceAnden->id) }}" class="btn btn-primary">
              <i class="bi bi-pencil-square me-2"></i>Editar
            </a>
          </div>

          <!-- Sección de Firmas -->
          <div class="mt-4">
            <h5 class="card-title">
              <i class="bi bi-pen"></i> Firmas Digitales
              @if($cruceAnden->firma_responsable1 && $cruceAnden->firma_responsable2)
                <span class="badge bg-success">Completadas</span>
              @else
                <span class="badge bg-warning">Pendientes</span>
              @endif
            </h5>
            
            @if($cruceAnden->firma_responsable1 && $cruceAnden->firma_responsable2)
              <!-- Mostrar firmas existentes -->
              <div class="row">
                <div class="col-md-6 text-center mb-3">
                  <div class="border rounded p-3">
                    <h6>Responsable 1</h6>
                    <img src="{{ $cruceAnden->firma_responsable1 }}" alt="Firma 1" style="max-width: 100%; height: 100px; border: 1px solid #ddd;">
                    <p class="mt-2 mb-0"><strong>{{ $cruceAnden->nombre_responsable1 }}</strong></p>
                  </div>
                </div>
                <div class="col-md-6 text-center mb-3">
                  <div class="border rounded p-3">
                    <h6>Responsable 2</h6>
                    <img src="{{ $cruceAnden->firma_responsable2 }}" alt="Firma 2" style="max-width: 100%; height: 100px; border: 1px solid #ddd;">
                    <p class="mt-2 mb-0"><strong>{{ $cruceAnden->nombre_responsable2 }}</strong></p>
                  </div>
                </div>
              </div>
              
              @if($cruceAnden->nota_firmas)
              <div class="alert alert-info">
                <strong><i class="bi bi-info-circle"></i> Nota:</strong> {{ $cruceAnden->nota_firmas }}
              </div>
              @endif
              
              <div class="text-center">
                <a href="{{ route('cruce_anden.firmas', $cruceAnden->id) }}" class="btn btn-warning">
                  <i class="bi bi-pencil"></i> Editar Firmas
                </a>
              </div>
            @else
              <!-- Botón para agregar firmas -->
              <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i> Este cruce de andén aún no tiene firmas digitales.
              </div>
              <div class="text-center">
                <a href="{{ route('cruce_anden.firmas', $cruceAnden->id) }}" class="btn btn-primary">
                  <i class="bi bi-pen"></i> Agregar Firmas
                </a>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

</section>
@endsection
