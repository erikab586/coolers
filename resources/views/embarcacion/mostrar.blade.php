@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
    <div class="pagetitle">
      <h1>Embarcaciones</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active">Embarcación</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    @if(session('success'))
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            timer: 5000,
            timerProgressBar: true,
            showConfirmButton: true,
            confirmButtonText: 'Aceptar'
          });
        });
      </script>
    @endif
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
                  <h5 class="card-title">Embarcación <span>| Listados</span></h5>
                  <table class="table table-borderless datatable">
                   <thead>
                    <tr>
                      <th>#</th>
                      <th>ID Tarima</th>
                      <th>Código Tarima</th>
                      <th>Folio</th>
                      <th>Comercializadora</th>
                      <th>Sucursal</th>
                      <th>Chofer</th>
                      <th>Línea Transporte</th>
                      <th>Fecha</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($embarcacion as $data)
                      <tr @if($loop->first) class="table-success" @endif>
                        <td>{{ $data->id }}</td>
                        <td>
                            @php
                                $tarimas = [];
                                foreach($data->detalles as $detalle) {
                                    if($detalle->conservacion && $detalle->conservacion->tarima) {
                                        $tarimas[] = $detalle->conservacion->tarima;
                                    }
                                }
                                $tarimas = collect($tarimas)->unique('id');
                            @endphp
                            @if($tarimas->count() > 0)
                                @foreach($tarimas as $tarima)
                                    {{ $tarima->id }}
                                @endforeach
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($tarimas->count() > 0)
                                @foreach($tarimas as $tarima)
                                    {{ $tarima->codigo }}
                                @endforeach
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $folios = [];
                                foreach($data->detalles as $detalle) {
                                    if($detalle->conservacion && $detalle->conservacion->tarima && $detalle->conservacion->tarima->tarimaDetarec->isNotEmpty()) {
                                        $tarimaDetarec = $detalle->conservacion->tarima->tarimaDetarec->first();
                                        if($tarimaDetarec->detalle && $tarimaDetarec->detalle->recepcion) {
                                            $folios[] = $tarimaDetarec->detalle->recepcion->folio;
                                        }
                                    }
                                }
                                $folios = array_unique($folios);
                            @endphp
                            @if(count($folios) > 0)
                                @foreach($folios as $folio)
                                    <span class="badge bg-success">{{ $folio }}</span><br>
                                @endforeach
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $comercializadoras = [];
                                foreach($data->detalles as $detalle) {
                                    if($detalle->conservacion && $detalle->conservacion->tarima && $detalle->conservacion->tarima->tarimaDetarec->isNotEmpty()) {
                                        $tarimaDetarec = $detalle->conservacion->tarima->tarimaDetarec->first();
                                        if($tarimaDetarec->detalle && $tarimaDetarec->detalle->recepcion && $tarimaDetarec->detalle->recepcion->contrato && $tarimaDetarec->detalle->recepcion->contrato->comercializadora) {
                                            $comercializadoras[$tarimaDetarec->detalle->recepcion->contrato->comercializadora->id] = $tarimaDetarec->detalle->recepcion->contrato->comercializadora;
                                        }
                                    }
                                }
                            @endphp
                            @if(count($comercializadoras) > 0)
                                @foreach($comercializadoras as $comercializadora)
                                    <img src="{{ asset($comercializadora->imgcomercializadora ?? 'imagenes/comercializadoras/comercializadora.png') }}" 
                                         alt="{{ $comercializadora->nombrecomercializadora }}" 
                                         width="25" height="25" class="rounded-circle me-1">
                                    {{ $comercializadora->abreviatura }}<br>
                                @endforeach
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $coolers = [];
                                foreach($data->detalles as $detalle) {
                                    if($detalle->conservacion && $detalle->conservacion->tarima && $detalle->conservacion->tarima->tarimaDetarec->isNotEmpty()) {
                                        $tarimaDetarec = $detalle->conservacion->tarima->tarimaDetarec->first();
                                        if($tarimaDetarec->detalle && $tarimaDetarec->detalle->recepcion && $tarimaDetarec->detalle->recepcion->contrato && $tarimaDetarec->detalle->recepcion->contrato->cooler) {
                                            $coolers[$tarimaDetarec->detalle->recepcion->contrato->cooler->id] = $tarimaDetarec->detalle->recepcion->contrato->cooler;
                                        }
                                    }
                                }
                            @endphp
                            @if(count($coolers) > 0)
                                @foreach($coolers as $cooler)
                                    {{ $cooler->codigoidentificador }}-{{ $cooler->nombrecooler }}<br>
                                @endforeach
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $data->nombre_responsblechofer }}-{{ $data->apellido_responsablechofer ?? '' }}</td>
                        <td>{{ $data->linea_transporte ?? '-' }}</td>
                        <td>{{ $data->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('embarcacion.editar', $data->id) }}" class="btn btn-sm btn-success" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="{{ route('embarcacion.mostrarid', $data->id) }}" class="btn btn-sm btn-dark" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('embarcacion.pdf', $data->id) }}" class="btn btn-sm btn-secondary" title="Ver PDF" target="_blank">
                                <i class="bi bi-file-pdf"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger" title="Eliminar Embarcación" 
                                        data-bs-toggle="modal" data-bs-target="#modalEliminarEmbarcacion{{ $data->id }}">
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

  {{-- Modales para eliminar embarcaciones --}}
  @foreach($embarcacion as $data)
    <div class="modal fade" id="modalEliminarEmbarcacion{{ $data->id }}" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title">
              <i class="bi bi-exclamation-triangle me-2"></i>Eliminar Embarcación
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="{{ route('embarcacion.eliminar', $data->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-body">
              <p><strong>¿Está seguro de eliminar esta embarcación?</strong></p>
              <p class="text-muted">ID: <strong>{{ $data->id }}</strong></p>
              
              <div class="mb-3">
                <label for="observacionesEmbarcacion{{ $data->id }}" class="form-label">
                  Observaciones <span class="text-danger">*</span>
                </label>
                <textarea name="observaciones" id="observacionesEmbarcacion{{ $data->id }}" 
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