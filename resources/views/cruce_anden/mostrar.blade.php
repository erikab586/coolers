@extends('layouts.app')

@section('title', 'Cruce de Andén')

@section('content')
<div class="pagetitle">
  <h1> Cruce de Andén</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('cruce_anden.mostrar') }}">Cruce de Andén</a></li>
      <li class="breadcrumb-item active">Cruce de Andén</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Cruce de Andén</h5>

          @if(session('success'))
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                });
            });
            </script>
            @endif

            @if(session('error'))
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Error',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            });
            </script>
          @endif

          <table class="table table-borderless datatable">
            <thead>
              <tr>
                <th>#</th>
                <th>Id Tarima</th>
                <th>Tarima</th>
                <th>Folio</th>
                <th>Ubicación</th>
                <th>Comercializadora</th>
                <th>Sucursal</th>
                <th>Cámara</th>
                <th>Fecha</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              @forelse($cruceAnden as $ca)
                @php
                  $primerDetalle = $ca->tarima->tarimaDetarec->first();
                  $recepcion = $primerDetalle ? $primerDetalle->detalle->recepcion : null;
                  $comercializadora = $recepcion ? $recepcion->contrato->comercializadora : null;
                  
                  // Verificar si está completado
                  $completado = $ca->detallesCruceAnden->where('hora_salida', '!=', null)->count() > 0;
                @endphp
                <tr>
                  <th scope="row">#{{ $ca->id }}</th>
                  <td>{{ $ca->tarima->id }}</td>
                  <td>{{ $ca->tarima->codigo }}</td>
                  <td>
                    @if($recepcion)
                      <span class="badge bg-success">{{ $recepcion->folio }}</span>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                   <td>
                            <span class="badge bg-primary">
                                @php
                                    $ubicaciones = [
                                        'tarima' => 'TARIMA',
                                        'preenfriado' => 'PREENFRIADO',
                                        'conservacion' => 'CONSERVACIÓN',
                                        'cruce_anden' => 'CRUCE DE ANDÉN',
                                        'embarque' => 'EMBARQUE',
                                        'finalizado'=>'FINALIZADO'
                                    ];
                                    echo $ubicaciones[$ca->tarima->ubicacion] ?? strtoupper($ca->tarima->ubicacion);
                                @endphp
                            </span>
                    </td>
                  <td>
                            @php
                                $tarima = $ca->tarima ?? null;
                                $recepcion = $tarima
                                    ? ($tarima->tarimaDetarec->first()->detalle->recepcion ?? null)
                                    : null;
                                $comercializadora = $recepcion->contrato->comercializadora ?? null;
                            @endphp

                            @if($comercializadora)
                                <img src="{{ asset($comercializadora->imgcomercializadora ?? 'imagenes/comercializadoras/comercializadora.png') }}"
                                    alt="{{ $comercializadora->abreviatura ?? 'Comercializadora' }}"
                                    width="40" height="40" class="rounded-circle me-2">
                                {{ $comercializadora->abreviatura }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                  <td>{{ $ca->camara->cooler->codigoidentificador ?? 'N/A' }}-{{ $ca->camara->cooler->nombrecooler ?? 'N/A' }}</td>
                  <td>{{ $ca->camara->codigo ?? 'N/A' }}</td>
                  
                  <td>{{ $ca->created_at->format('d/m/Y H:i') }}</td>
                  <td>
                    <a href="{{ route('cruce_anden.editar', $ca->id) }}" 
                       class="btn btn-sm btn-success" 
                       title="Editar">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                    <a href="{{ route('cruce_anden.mostrarid', $ca->id) }}" 
                       class="btn btn-sm btn-dark" 
                       title="Ver Detalles">
                      <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('cruce_anden.pdf', $ca->id) }}" 
                       class="btn btn-sm btn-secondary" 
                       title="Ver PDF" 
                       target="_blank">
                      <i class="bi bi-file-pdf"></i>
                    </a>
                   <button type="button" class="btn btn-sm btn-danger" title="Eliminar Cruce de Andén" 
                                data-bs-toggle="modal" data-bs-target="#modalEliminarCruceAnden{{ $ca->id }}">
                          <i class="bi bi-trash"></i>
                    </button>
                    
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center">
                    <div class="alert alert-info">
                      <i class="bi bi-info-circle me-2"></i>
                      No hay tarimas en Cruce de Andén actualmente
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Modales para eliminar cruce de andén --}}
@foreach($cruceAnden as $ca)
  @php
    $completado = $ca->detallesCruceAnden->where('hora_salida', '!=', null)->count() > 0;
  @endphp
  @if(!$completado)
    <div class="modal fade" id="modalEliminarCruceAnden{{ $ca->id }}" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title">
              <i class="bi bi-exclamation-triangle me-2"></i>Eliminar Cruce de Andén
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="{{ route('cruce_anden.eliminar', $ca->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-body">
              <p><strong>¿Está seguro de eliminar este registro de Cruce de Andén?</strong></p>
              <p class="text-muted">Tarima: <strong>{{ $ca->tarima->codigo }}</strong></p>
              
              <div class="mb-3">
                <label for="observacionesCruceAnden{{ $ca->id }}" class="form-label">
                  Observaciones <span class="text-danger">*</span>
                </label>
                <textarea name="observaciones" id="observacionesCruceAnden{{ $ca->id }}" 
                          class="form-control" rows="3" required
                          placeholder="Ingrese el motivo de la eliminación (obligatorio)"></textarea>
                <small class="text-muted">Máximo 500 caracteres</small>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="bi bi-x-circle me-1"></i>Cancelar
              </button>
              <button type="submit" class="btn btn-danger">
                <i class="bi bi-trash me-1"></i>Confirmar Eliminación
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif
@endforeach
@endsection
