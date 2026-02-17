@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
    <div class="pagetitle">
      <h1>Recepciones</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('recepcion.mostrar') }}">Recepciones</a></li>
          @if(isset($comercializadora))
            <li class="breadcrumb-item active">{{ $comercializadora->nombrecomercializadora }}</li>
          @else
            <li class="breadcrumb-item active">Todas</li>
          @endif
          @if(hasPermission('crear_recepciones'))
            <li class="breadcrumb-item"><a href="{{ route('contrato.recepcionar') }}">Crear Recepción</a></li>
          @endif
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
                  <h5 class="card-title">Recepciones 
                    @if(isset($comercializadora))
                      <span>| {{ $comercializadora->nombrecomercializadora }}</span>
                      <a href="{{ route('recepcion.mostrar') }}" class="btn btn-sm btn-outline-secondary ms-2">
                        <i class="bi bi-x-circle"></i> Ver todas
                      </a>
                    @else
                      <span>| Todas</span>
                    @endif
                  </h5>

                  <table class="table table-borderless datatable">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Folio</th>
                        <th scope="col">Datos clave</th>
                        <th scope="col">Comercializadora</th>
                        <th scope="col">Sucursal</th>
                        <th scope="col">Área</th>
                        <th scope="col">Estatus</th>
                        <th scope="col">Fecha</th>
                        @if(hasAnyPermission(['editar_recepciones', 'eliminar_recepciones', 'ver_recepciones']))
                          <th scope="col" style="text-align:center">Acción</th>
                        @endif
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($recepciones as $recepcion)
                      <tr @if($loop->first) class="table-success" @endif>
                          <th scope="row">#{{ $recepcion->id }}</th>
                          <td><a href="{{ route('recepcion.show', $recepcion->id) }}" class="text-primary"><span class="badge bg-success">{{ $recepcion->folio }}</span></a></td>
                          <td>{{ $recepcion->datosclave }}</td>
                          <td>
                            <img src="{{ asset($recepcion->contrato->comercializadora->imgcomercializadora ?? 'imagenes/comercializadoras/comercializadora.png') }}" 
                                alt="FrutiMax" width="40" height="40" class="rounded-circle me-2">
                            {{ $recepcion->contrato->comercializadora->abreviatura }}
                          </td>
                          <td>
                            {{ $recepcion->contrato->cooler->nombrecooler }}
                          </td>
                          <td>{{ $recepcion->area }}</td>
                          <td>
                            @php
                                switch ($recepcion->estatus) {
                                    case 'CON DETALLE':
                                        $badge = 'primary';
                                        $texto = 'Con Detalle';
                                        break;
                                    case 'TARIMA':
                                        $badge = 'info';
                                        $texto = 'En Tarima';
                                        break;
                                    case 'EN PREENFRIADO':
                                        $badge = 'warning';
                                        $texto = 'En Preenfriado';
                                        break;
                                    case 'EN CONSERVACIÓN':
                                        $badge = 'secondary';
                                        $texto = 'En Conservación';
                                        break;
                                    case 'EN CRUCE DE ANDÉN':
                                        $badge = 'warning';
                                        $texto = 'En Cruce de Andén';
                                        break;
                                    case 'EN EMBARQUE':
                                        $badge = 'dark';
                                        $texto = 'En Embarque';
                                        break;
                                    case 'FINALIZADO':
                                        $badge = 'success';
                                        $texto = 'Finalizado';
                                        break;
                                    case 'CANCELADA':
                                        $badge = 'danger';
                                        $texto = 'Cancelada';
                                        break;
                                    default:
                                        $badge = 'light';
                                        $texto = $recepcion->estatus;
                                        break;
                                }
                            @endphp

                            <span class="badge bg-{{ $badge }}">{{ $texto }}</span>
                          </td>
                          <td>{{ $recepcion->created_at }}</td>
                          @if(hasAnyPermission(['editar_recepciones', 'eliminar_recepciones', 'ver_recepciones']))
                            <td>
                              @if(hasPermission('editar_recepciones'))
                                <a href="{{ route('asignartarima.crear', ['idcontrato' => $recepcion->idcontrato]) }}"
                                  class="btn btn-sm btn-primary"
                                  title="Cargar Tarima">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="{{ route('recepcion.editar', $recepcion->id) }}" class="btn btn-sm btn-success" title="Editar">
                                  <i class="bi bi-pencil-square"></i>
                                </a>
                              @endif
                              
                              @if(hasPermission('eliminar_recepciones'))
                                <button type="button" class="btn btn-sm btn-danger" title="Cancelar Recepción" 
                                        data-bs-toggle="modal" data-bs-target="#modalEliminarRecepcion{{ $recepcion->id }}">
                                  <i class="bi bi-trash"></i>
                                </button>
                              @endif
                              
                              @if(hasPermission('ver_recepciones'))
                                <a href="{{ route('recepcion.show', $recepcion->id) }}" class="btn btn-sm btn-dark" title="Ver Recepción">
                                  <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('recepcion.pdf', $recepcion->id) }}" class="btn btn-sm btn-secondary" title="Ver PDF" target="_blank">
                                  <i class="bi bi-file-pdf"></i>
                                </a>
                              @endif
                            </td>
                          @endif
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

    {{-- Modales para eliminar recepciones --}}
    @foreach($recepciones as $recepcion)
      <div class="modal fade" id="modalEliminarRecepcion{{ $recepcion->id }}" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header bg-danger text-white">
              <h5 class="modal-title">
                <i class="bi bi-exclamation-triangle me-2"></i>Cancelar Recepción
              </h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('recepcion.eliminar', $recepcion->id) }}" method="POST">
              @csrf
              @method('DELETE')
              <div class="modal-body">
                <p><strong>¿Está seguro de cancelar esta recepción?</strong></p>
                <p class="text-muted">Folio: <strong>{{ $recepcion->folio }}</strong></p>
                
                <div class="mb-3">
                  <label for="observaciones{{ $recepcion->id }}" class="form-label">
                    Observaciones <span class="text-danger">*</span>
                  </label>
                  <textarea name="observaciones" id="observaciones{{ $recepcion->id }}" 
                            class="form-control" rows="3" required
                            placeholder="Ingrese el motivo de la cancelación (obligatorio)"></textarea>
                  <small class="text-muted">Máximo 500 caracteres</small>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                  <i class="bi bi-x-circle me-1"></i>Cancelar
                </button>
                <button type="submit" class="btn btn-danger">
                  <i class="bi bi-trash me-1"></i>Confirmar Cancelación
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    @endforeach
@endsection