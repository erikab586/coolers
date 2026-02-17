@extends('layouts.app')

@section('title', 'Formulario de Pre-Enfriado')

@section('content')
<div class="pagetitle">
  <h1>Horario de Salida de Pre-Enfriado </h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Recepción</li>
      <li class="breadcrumb-item"><a href="{{ route('enfrio.mostrar') }}">Volver a Recepciones</a></li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <form class="row g-3" action="#" method="POST">
            @csrf
            <h5 class="card-title">Información de Pre-Enfriado</h5>
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
                        PREENFRIADO
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
                    <td>Pre-Enfriado</td>
                    <td>F-BCM-PRO-04</td>
                    <td>{{ $preenfriado->tarima->tarimaDetarec->first()->detalle->recepcion->fechaemision ? \Carbon\Carbon::parse($preenfriado->tarima->tarimaDetarec->first()->detalle->recepcion->fechaemision)->format('Y-m-d') : '—' }}</td>
                    <td>{{ $preenfriado->tarima->tarimaDetarec->first()->detalle->recepcion->revision ?? '—' }}</td>
                </tr>
                            
                <!-- Fila de cliente, folio y fecha -->
                <tr>
                    <td colspan="3"style="text-align: left;">
                        Tarima: {{ $preenfriado->tarima->codigo }}
                    </td>
                    <td colspan="2"style="text-align: left;">
                        Fecha: {{ \Carbon\Carbon::now()->format('Y-m-d') }}
                    </td>
                </tr>
            </table>
            <h5 class="card-title mt-4">Detalle de Pre-Enfriado</h5>
            <table class="table table-bordered" id="tabla-espreenfrio">
              <thead>
                <tr>
                  <th colspan="5" class="text-center">Detalle</th>
                  <th colspan="2" class="text-center">Entrada</th>
                  <th colspan="2" class="text-center">Salida</th>
                  <th rowspan="2" class="text-center align-middle">Total</th>
                </tr>
                <tr>
                  <th class="text-center">Recepción</th>
                  <th class="text-center">Fruta</th>
                  <th class="text-center">Presentación</th>
                  <th class="text-center">Variedad</th>
                  <th class="text-center">Cant</th>
                  <th class="text-center">Camara</th>
                  <th class="text-center">Hora</th>
                  <th class="text-center">Temp.</th>
                  <th class="text-center">Hora</th>
                  <th class="text-center">Temp.</th>
                </tr>
              </thead>

              <tbody id="detalles-body">
                @forelse($preenfriado->tarima->tarimaDetarec as $tarimaDet)
                  @php
                      $detalle = $tarimaDet->detalle;
                      $recepcion = $detalle->recepcion ?? null;
                      $detallePre = $preenfriado->detallesPreenfriado
                          ->where('iddetalle', $tarimaDet->iddetalle)
                          ->first();
                  @endphp
                  <tr>
                    <td>{{ $recepcion->folio }}</td>
                    <td>
                      <input type="hidden" class="form-control" name="idpreenfriado[]" value="{{ $preenfriado->id ?? 'N/A' }}">
                      <input type="hidden" name="iddetalle[]" class="form-control" value="{{ $tarimaDet->detalle->id ?? 'No asignada' }}" required>
                      <input type="text" name="fruta[]" class="form-control" value="{{ $tarimaDet->fruta->nombrefruta ?? 'No asignada' }}" required>
                    </td>
                    <td><input type="text" name="presentacion[]" class="form-control" value="{{ $tarimaDet->presentacion->nombrepresentacion ?? 'No asignada' }}" required></td>
                    <td><input type="text" name="variedad[]" class="form-control" value="{{ $tarimaDet->variedad->tipofruta ?? 'No asignada' }}" required></td>
                     <td><input type="text" name="cantidad[]" class="form-control" value="{{ $tarimaDet->cantidadcarga }}" required></td>
                    <td>
                      <input type="text" class="form-control" name="camara" value="{{ $preenfriado->camara->codigo ?? 'No asignada' }}">
                    </td>

                    {{-- HORA ENTRADA --}}
                    <td>
                      <input type="datetime-local" name="hora_entrada[]" 
                            class="form-control hora-entrada"
                            value="{{ $detallePre && $detallePre->hora_entrada ? \Carbon\Carbon::parse($detallePre->hora_entrada)->format('Y-m-d\TH:i') : '' }}"
                            readonly>
                    </td>

                    {{-- TEMP ENTRADA --}}
                    <td>
                      <input type="number" step="0.1" name="temperatura_entrada[]" 
                            value="{{ $detallePre->temperatura_entrada ?? 'Pendiente' }}" 
                            class="form-control" readonly>
                    </td>

                    {{-- HORA SALIDA --}}
                    <td>
                      <input type="datetime-local" name="hora_salida[]" 
                            class="form-control hora-salida"
                            value="{{ $detallePre && $detallePre->hora_salida ? \Carbon\Carbon::parse($detallePre->hora_salida)->format('Y-m-d\TH:i') : '' }}"
                            readonly>
                    </td>

                    {{-- TEMP SALIDA --}}
                    <td>
                      <input type="number" step="0.1" name="temperatura_salida[]" 
                            class="form-control" 
                            value="{{ $detallePre->temperatura_salida ?? 'Pendiente' }}" readonly>
                    </td>

                    {{-- TIEMPO TOTAL (lo llena el JS) --}}
                    <td>
                      <input type="text" name="tiempototal[]" 
                            class="form-control tiempo-total" 
                            value="" readonly>
                    </td>
                  </tr>
                   @empty
                      <tr>
                          <td colspan="10" class="text-center">No hay detalles de tarima asociados</td>
                      </tr>
                  @endforelse
               
              </tbody>

            </table>

          </form>

          <!-- Sección de Firmas -->
          <div class="mt-4">
            <h5 class="card-title">
              <i class="bi bi-pen"></i> Firmas Digitales
              @if($preenfriado->firma_responsable1 && $preenfriado->firma_responsable2)
                <span class="badge bg-success">Completadas</span>
              @else
                <span class="badge bg-warning">Pendientes</span>
              @endif
            </h5>
            
            @if($preenfriado->firma_responsable1 && $preenfriado->firma_responsable2)
              <!-- Mostrar firmas existentes -->
              <div class="row">
                <div class="col-md-6 text-center mb-3">
                  <div class="border rounded p-3">
                    <h6>Responsable 1</h6>
                    <img src="{{ $preenfriado->firma_responsable1 }}" alt="Firma 1" style="max-width: 100%; height: 100px; border: 1px solid #ddd;">
                    <p class="mt-2 mb-0"><strong>{{ $preenfriado->nombre_responsable1 }}</strong></p>
                  </div>
                </div>
                <div class="col-md-6 text-center mb-3">
                  <div class="border rounded p-3">
                    <h6>Responsable 2</h6>
                    <img src="{{ $preenfriado->firma_responsable2 }}" alt="Firma 2" style="max-width: 100%; height: 100px; border: 1px solid #ddd;">
                    <p class="mt-2 mb-0"><strong>{{ $preenfriado->nombre_responsable2 }}</strong></p>
                  </div>
                </div>
              </div>
              
              @if($preenfriado->nota_firmas)
              <div class="alert alert-info">
                <strong><i class="bi bi-info-circle"></i> Nota:</strong> {{ $preenfriado->nota_firmas }}
              </div>
              @endif
              
              <div class="text-center">
                <a href="{{ route('preenfriado.firmas', $preenfriado->id) }}" class="btn btn-warning">
                  <i class="bi bi-pencil"></i> Editar Firmas
                </a>
              </div>
            @else
              <!-- Botón para agregar firmas -->
              <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i> Este preenfriado aún no tiene firmas digitales.
              </div>
              <div class="text-center">
                <a href="{{ route('preenfriado.firmas', $preenfriado->id) }}" class="btn btn-primary">
                  <i class="bi bi-pen"></i> Agregar Firmas
                </a>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const tabla = document.getElementById("detalles-body");

            function calcularTiempo(fila) {
                const horaEntradaInput = fila.querySelector('.hora-entrada');
                const horaSalidaInput = fila.querySelector('.hora-salida');
                const tiempoTotalInput = fila.querySelector('.tiempo-total');

                if (!horaEntradaInput || !horaSalidaInput || !tiempoTotalInput) return;

                const horaEntrada = horaEntradaInput.value;
                const horaSalida = horaSalidaInput.value;

                if (horaEntrada && horaSalida) {
                    const entrada = new Date(horaEntrada);
                    const salida = new Date(horaSalida);

                    const diffMs = salida - entrada;

                    if (diffMs < 0) {
                        tiempoTotalInput.value = 'Error: Salida antes de entrada';
                        return;
                    }

                    const diffHrs = Math.floor(diffMs / (1000 * 60 * 60));
                    const diffMins = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));

                    tiempoTotalInput.value = `${diffHrs}h ${diffMins}m`;
                } else {
                    tiempoTotalInput.value = '';
                }
            }

            // Recalcular al cambiar hora_salida (por si en algún momento permites edición)
            if (tabla) {
                tabla.addEventListener("change", function (e) {
                    if (e.target.classList.contains('hora-salida') || e.target.classList.contains('hora-entrada')) {
                        const fila = e.target.closest("tr");
                        calcularTiempo(fila);
                    }
                });

                // Calcular tiempos existentes al cargar la página
                document.querySelectorAll('#detalles-body tr').forEach(fila => {
                    calcularTiempo(fila);
                });
            }
        });
    </script>
</section>
@endsection
