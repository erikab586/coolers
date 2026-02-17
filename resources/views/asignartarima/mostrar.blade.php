@extends('layouts.app')

@section('title', 'Reporte de Asignaciones')
@section('content')

<div class="pagetitle">
  <h1>📦 Control de Asignaciones a Tarimas</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Asignaciones</li>
      <li class="breadcrumb-item active">Reporte Detallado</li>
      <li class="breadcrumb-item"><a href="{{ route('asignartarima.crear') }}" class="btn btn-sm btn-primary">➕ Nueva Asignación</a></li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">📊 Seguimiento de Recepciones y Tarimas</h5>
          <p class="text-muted">Este reporte muestra cómo se han distribuido las cajas de cada recepción en diferentes tarimas</p>

          @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="bi bi-check-circle"></i> {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          <!-- Tabla de asignaciones -->
          <div class="table-responsive">
            <table class="table table-striped table-hover datatable" id="tablaAsignaciones">
              <thead class="table-dark">
                <tr>
                  <th>Folio</th>
                  <th>Comercializadora</th>
                  <th>Fruta</th>
                  <th>Variedad</th>
                  <th>Tarima</th>
                  <th class="text-center">Cantidad Original</th>
                  <th class="text-center">Total Asignado</th>
                  <th class="text-center">Pendientes</th>
                  <th>Fecha Asignación</th>
                  <th class="text-center">Estado</th>
                </tr>
              </thead>
              <tbody>
                @php
                  // Agrupar por detalle de recepción
                  $detallesAgrupados = [];
                  foreach($asignaciones as $asignacion) {
                    $idDetalle = $asignacion->detalle->id;
                    if (!isset($detallesAgrupados[$idDetalle])) {
                      $detallesAgrupados[$idDetalle] = [
                        'detalle' => $asignacion->detalle,
                        'asignaciones' => []
                      ];
                    }
                    $detallesAgrupados[$idDetalle]['asignaciones'][] = $asignacion;
                  }
                @endphp

                @forelse($detallesAgrupados as $grupo)
                  @php
                    $detalle = $grupo['detalle'];
                    $asignacionesDetalle = $grupo['asignaciones'];
                    $totalAsignado = collect($asignacionesDetalle)->sum('cantidad_asignada');
                    $numFilas = count($asignacionesDetalle);
                  @endphp

                  @foreach($asignacionesDetalle as $index => $asignacion)
                    <tr>
                      @if($index === 0)
                        <!-- Primera fila: muestra info del detalle completo -->
                        <td rowspan="{{ $numFilas }}" class="align-middle">
                          <strong class="text-primary">{{ $detalle->recepcion->folio }}</strong>
                        </td>
                        <td rowspan="{{ $numFilas }}" class="align-middle">
                          <div class="d-flex align-items-center">
                            @if($detalle->recepcion->contrato->comercializadora->imgcomercializadora ?? false)
                              <img src="{{ asset($detalle->recepcion->contrato->comercializadora->imgcomercializadora) }}" 
                                   alt="Logo" width="30" height="30" class="rounded-circle me-2">
                            @endif
                            <span>{{ $detalle->recepcion->contrato->comercializadora->nombrecomercializadora ?? 'N/A' }}</span>
                          </div>
                        </td>
                        <td rowspan="{{ $numFilas }}" class="align-middle">
                          {{ $detalle->fruta->nombrefruta ?? 'N/A' }}
                        </td>
                        <td rowspan="{{ $numFilas }}" class="align-middle">
                          {{ $detalle->variedad->tipofruta ?? 'N/A' }}
                        </td>
                      @endif

                      <!-- Info específica de cada tarima -->
                      <td>
                        <span class="badge bg-dark">🏷️ {{ $asignacion->tarima->codigo }}</span>
                        <br>
                        <small class="text-muted">{{ $asignacion->cantidad_asignada }} cajas</small>
                      </td>

                      @if($index === 0)
                        <td rowspan="{{ $numFilas }}" class="text-center align-middle">
                          <strong class="fs-5">{{ number_format($detalle->cantidad) }}</strong>
                          <br>
                          <small class="text-muted">cajas</small>
                        </td>
                        <td rowspan="{{ $numFilas }}" class="text-center align-middle">
                          <span class="badge bg-primary fs-6">{{ number_format($totalAsignado) }}</span>
                          <br>
                          <small class="text-muted">cajas</small>
                        </td>
                        <td rowspan="{{ $numFilas }}" class="text-center align-middle">
                          <span class="badge {{ $detalle->pendientes > 0 ? 'bg-warning' : 'bg-success' }} fs-6">
                            {{ number_format($detalle->pendientes) }}
                          </span>
                          <br>
                          <small class="text-muted">cajas</small>
                        </td>
                      @endif

                      <td>
                        <small>{{ $asignacion->created_at->format('d/m/Y') }}</small>
                        <br>
                        <small class="text-muted">{{ $asignacion->created_at->format('H:i') }}</small>
                      </td>

                      @if($index === 0)
                        <td rowspan="{{ $numFilas }}" class="text-center align-middle">
                          @if($detalle->pendientes <= 0)
                            <span class="badge bg-success">
                              <i class="bi bi-check-circle-fill"></i> Completado
                            </span>
                          @else
                            <span class="badge bg-warning">
                              <i class="bi bi-clock-fill"></i> Pendiente
                            </span>
                          @endif
                        </td>
                      @endif
                    </tr>
                  @endforeach
                @empty
                  <tr>
                    <td colspan="10" class="text-center text-muted py-4">
                      <i class="bi bi-inbox fs-1"></i>
                      <p class="mt-2">No hay asignaciones registradas</p>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <!-- Resumen estadístico -->
          <div class="row mt-4">
            <div class="col-md-3">
              <div class="card bg-primary text-white">
                <div class="card-body text-center">
                  <h3>{{ $asignaciones->count() }}</h3>
                  <p class="mb-0">Total Asignaciones</p>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card bg-success text-white">
                <div class="card-body text-center">
                  <h3>{{ collect($detallesAgrupados)->where('detalle.pendientes', 0)->count() }}</h3>
                  <p class="mb-0">Recepciones Completadas</p>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card bg-warning text-white">
                <div class="card-body text-center">
                  <h3>{{ collect($detallesAgrupados)->where('detalle.pendientes', '>', 0)->count() }}</h3>
                  <p class="mb-0">Recepciones Pendientes</p>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card bg-dark text-white">
                <div class="card-body text-center">
                  <h3>{{ $asignaciones->pluck('tarima.codigo')->unique()->count() }}</h3>
                  <p class="mb-0">Tarimas Utilizadas</p>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>

@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    $('#tablaAsignaciones').DataTable({
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
      },
      order: [[8, 'desc']], // Ordenar por fecha descendente
      pageLength: 25,
      responsive: true
    });
  });
</script>
@endpush