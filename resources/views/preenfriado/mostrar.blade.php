@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
    <div class="pagetitle">
      <h1>Pre-Enfriados</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active">Pre-Enfriado</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
   
    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">
            <!-- Recent Sales -->
            <div class="col-12">
              <div class="card recent-sales overflow-auto">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Pre-Enfriado <span>| Listados</span></h5>
                  
                  <table class="table table-borderless datatable">
                   <thead>
                    <tr>
                      <th>#</th>
                      <th>IdTarima</th>
                      <th>Tarima</th>
                      <th>Folio</th>
                      <th>Ubicación</th>
                      <th>Comercializadora</th>
                      <th>Sucursal</th>
                      <th>Cámara</th>
                      <th>Fecha</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($preenfriados as $data)
                      <tr @if($loop->first) class="table-success" @endif>
                        <td>{{ $data->id }}</td>
                        <td>{{ $data->tarima->id }}</td>
                        <td>{{ $data->tarima->codigo }}</td>
                        <td>
                            @if($data->tarima->tarimaDetarec->isNotEmpty() && $data->tarima->tarimaDetarec->first()->detalle && $data->tarima->tarimaDetarec->first()->detalle->recepcion)
                                <span class="badge bg-success">{{ $data->tarima->tarimaDetarec->first()->detalle->recepcion->folio }}</span>
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
                                    echo $ubicaciones[$data->tarima->ubicacion] ?? strtoupper($data->tarima->ubicacion);
                                @endphp
                            </span>
                        </td>
                        <td>
                            @php
                                $tarima = $data->tarima ?? null;
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
                        <td>{{ $data->camara->cooler->codigoidentificador }}-{{ $data->camara->cooler->nombrecooler }}</td>
                        <td>Pre-Enfriado-{{ $data->camara->codigo }}</td>
                        
                        <td>{{ $data->created_at }}</td>
                        <td>
                          <a href="{{ route('enfrio.editar', $data->id) }}" class="btn btn-sm btn-success" title="Editar">
                            <i class="bi bi-pencil-square"></i>
                          </a>
                           <a href="{{ route('enfrio.mostrarid', $data->id) }}" class="btn btn-sm btn-dark" title="Ver Pre-Enfriado">
                              <i class="bi bi-eye"></i>
                          </a>
                          @if(
                              $data->tarima->ubicacion === 'preenfriado' &&
                              $data->detallesPreenfriado
                                  ->whereNotNull('hora_salida')
                                  ->count() > 0
                          )
                              <a href="{{ route('preenfriado.elegir_destino', $data->tarima->id) }}"
                                class="btn btn-sm btn-primary"
                                title="Elegir Destino">
                                  <i class="bi bi-arrow-right-circle"></i>
                              </a>
                          @endif
                          <a href="{{ route('preenfriado.pdf', $data->id) }}" class="btn btn-sm btn-secondary" title="Ver PDF" target="_blank">
                              <i class="bi bi-file-pdf"></i>
                          </a>
                          <button type="button" class="btn btn-sm btn-danger" title="Eliminar Preenfriado" 
                                      data-bs-toggle="modal" data-bs-target="#modalEliminarPreenfriado{{ $data->id }}">
                                  <i class="bi bi-trash"></i>
                          </button>
                        </td>
                      </tr>
                      
                    @endforeach
                  </tbody>


                  </table>

                </div>

              </div>
            </div><!-- End Recent Sales -->

          </div>
        </div><!-- End Left side columns -->
      </div>
  </section>

  {{-- Modales para eliminar preenfriados --}}
  @foreach($preenfriados as $data)
    <div class="modal fade" id="modalEliminarPreenfriado{{ $data->id }}" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title">
              <i class="bi bi-exclamation-triangle me-2"></i>Eliminar Preenfriado
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="{{ route('enfrio.eliminar', $data->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-body">
              <p><strong>¿Está seguro de eliminar este preenfriado?</strong></p>
              <p class="text-muted">Tarima: <strong>{{ $data->tarima->codigo }}</strong></p>
              
              <div class="mb-3">
                <label for="observacionesPreenfriado{{ $data->id }}" class="form-label">
                  Observaciones <span class="text-danger">*</span>
                </label>
                <textarea name="observaciones" id="observacionesPreenfriado{{ $data->id }}" 
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
  @endforeach
@endsection