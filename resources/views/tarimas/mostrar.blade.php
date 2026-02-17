@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
    <div class="pagetitle">
      <h1>Tarimas</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active">Tarimas</li>
          <!--li class="breadcrumb-item"><a href="{{ route('tarima.crear') }}">Crear Tarima</a></li-->
          <li class="breadcrumb-item"><a href="{{ route('asignartarima.crear') }}">Cargar Tarima</a></li>
          <li class="breadcrumb-item"><a href="{{ route('recepcion.mostrar') }}">Mostrar Recepcion</a></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
      @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Éxito',
                        text: '{{ session('success') }}',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Aquí rediriges a otra ventana/página
                            window.location.href = "{{ route('enfrio.mostrar') }}";
                        }
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
                  <h5 class="card-title">Tarimas <span>| Listado</span></h5>
                  
                  <!-- Botones de acción múltiple -->
                  <div class="mb-3">
                    <button type="button" class="btn btn-success" id="btnEnviarMultiple" disabled
                            data-bs-toggle="modal" data-bs-target="#modalEnviarMultiple">
                      <i class="bi bi-thermometer-high me-1"></i>
                      Enviar Seleccionadas a Pre-Enfriado (<span id="contadorSeleccionadas">0</span>)
                    </button>
                    <button type="button" class="btn btn-secondary" id="btnSeleccionarTodas">
                      <i class="bi bi-check-all me-1"></i>Seleccionar Todas
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="btnDeseleccionarTodas">
                      <i class="bi bi-x-circle me-1"></i>Deseleccionar Todas
                    </button>
                  </div>
                  
                  <table class="table table-borderless datatable">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" id="checkboxTodas" title="Seleccionar todas">
                        </th>
                        <th scope="col">#</th>
                        <th scope="col">Tarima</th>
                        <th scope="col">Folio</th>
                        <th scope="col">Ubicación</th>
                        <th scope="col">Comercializadora</th>
                        <th scope="col">Sucursal</th>
                        <th scope="col">Cantidad Disponible</th>
                        <th scope="col">Cantidad Usada</th>
                        <th scope="col">Estatus</th>
                        <th scope="col">Acción</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($tarimas as $tarima)
                      @php
                          $cantidadUsada = $tarima->tarimaDetarec->sum('cantidadcarga');
                          $capacidadTotal = $tarima->capacidad ?? 0;
                          $cantidadDisponible = max($capacidadTotal - $cantidadUsada, 0);
                      @endphp
                      <tr @if($loop->first) class="table-success" @endif>
                          <td>
                            @if($tarima->estatus == 'completo' && $tarima->ubicacion == 'tarima')
                              <input type="checkbox" class="checkbox-tarima" 
                                     data-tarima-id="{{ $tarima->id }}"
                                     data-folio="{{ $tarima->tarimaDetarec->first()->detalle->recepcion->folio ?? '' }}">
                            @endif
                          </td>
                          
                          <th scope="row"><a href="#">#{{ $tarima->id }}</a></th>
                          <td>{{ $tarima->codigo }}</td>
                          <td>
                              @if($tarima->tarimaDetarec->isNotEmpty() && $tarima->tarimaDetarec->first()->detalle && $tarima->tarimaDetarec->first()->detalle->recepcion)
                                  <span class="badge bg-success">{{ $tarima->tarimaDetarec->first()->detalle->recepcion->folio }}</span>
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
                                      echo $ubicaciones[$tarima->ubicacion] ?? strtoupper($tarima->ubicacion);
                                  @endphp
                              </span>
                          </td>
                          <td>
                            @php
                              $recepcion = $tarima->tarimaDetarec->first()->detalle->recepcion ?? null;
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
                          <td>
                              @if($tarima->tarimaDetarec->isNotEmpty() && $tarima->tarimaDetarec->first()->detalle && $tarima->tarimaDetarec->first()->detalle->recepcion && $tarima->tarimaDetarec->first()->detalle->recepcion->contrato && $tarima->tarimaDetarec->first()->detalle->recepcion->contrato->cooler)
                                  {{ $tarima->tarimaDetarec->first()->detalle->recepcion->contrato->cooler->codigoidentificador }}-{{ $tarima->tarimaDetarec->first()->detalle->recepcion->contrato->cooler->nombrecooler }}
                              @else
                                  <span class="text-muted">-</span>
                              @endif
                          </td>
                          <td>{{ $cantidadDisponible }}</td>
                          <td>{{ $cantidadUsada }}</td>
                         
                          <td>
                              @if($tarima->estatus == 'disponible')
                                  <span class="badge bg-success">Disponible</span>
                              @else
                                  <span class="badge bg-danger">Completo</span>
                              @endif
                          </td>
                          <td>
                              @if($tarima->estatus == 'completo')
                                  <a href="{{ route('tarima.etiqueta',$tarima->id) }}" class="btn btn-sm btn-primary" title="Generar Etiqueta">
                                      <i class="bi bi-file-richtext-fill"></i>
                                  </a>
                                  <button class="btn btn-sm btn-success" data-bs-toggle="modal" 
                                      data-bs-target="#cargarTarimaModal{{ $tarima->id }}" title="Enviar PreEnfriado">
                                      <i class="bi bi-thermometer-high"></i>
                                  </button>
                                  <a href="{{ route('tarima.mostrarid', $tarima->id) }}" class="btn btn-sm btn-warning" title="Enviar Conservación">
                                      <i class="bi bi-thermometer-snow"></i>
                                  </a>
                                  <a href="{{ route('tarima.mostrarid', $tarima->id) }}" class="btn btn-sm btn-dark" title="Ver Tarima">
                                      <i class="bi bi-eye"></i>
                                  </a>
                              @else
                                  <a href="{{ route('tarima.mostrarid', $tarima->id) }}" class="btn btn-sm btn-dark" title="Ver Tarima">
                                      <i class="bi bi-eye"></i>
                                  </a>
                              @endif
                              <!--a href="{{ route('tarima.pdf', $tarima->id) }}" class="btn btn-sm btn-secondary" title="Ver PDF" target="_blank">
                                  <i class="bi bi-file-pdf"></i>
                              </a-->
                              <a href="{{ route('tarima.editar', $tarima->id) }}" class="btn btn-sm btn-info" title="Editar Tarima">
                                  <i class="bi bi-pencil-square"></i>
                              </a>
                              <button type="button" class="btn btn-sm btn-danger" title="Eliminar Tarima" 
                                      data-bs-toggle="modal" data-bs-target="#modalEliminarTarima{{ $tarima->id }}">
                                  <i class="bi bi-trash"></i>
                              </button>
                          </td>
                      </tr>

                      <!-- Modal único para esta tarima -->
                      <div class="modal fade" id="cargarTarimaModal{{ $tarima->id }}" tabindex="-1" aria-labelledby="cargarTarimaLabel{{ $tarima->id }}" aria-hidden="true">
                          <div class="modal-dialog">
                              <form action="{{ route('enfrio.guardar', $tarima->id) }}" method="POST">
                                  @csrf
                                  <div class="modal-content">
                                      <div class="modal-header">
                                          <h5 class="modal-title" id="cargarTarimaLabel{{ $tarima->id }}">¿Quieres enviar la tarima a Pre-Enfriado?</h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                      </div>
                                      <div class="modal-body">
                                           <div class="mb-3">
                                              <label for="respuesta{{ $tarima->id }}" class="form-label">Selecciona una opción</label>
                                              <input type="text" name="respuesta" id="respuesta{{ $tarima->id }}" class="form-control" value="Si" readonly required>
                                          </div>
                                          <div class="mb-3">
                                              <label class="form-label">Cámaras de Pre-Enfriado <span class="text-danger">*</span></label>
                                              <select name="idcamara" class="form-select" required>
                                                  <option value="">Seleccione una cámara</option>
                                                  @foreach($camaras as $camara)
                                                      @if(strtoupper($camara->tipo) == 'PREENFRIADO' || strtoupper($camara->tipo) == 'PRE ENFRIADO')
                                                          <option value="{{ $camara->id }}">
                                                              {{ $camara->cooler->nombrecooler }} - {{ $camara->codigo }}
                                                          </option>
                                                      @endif
                                                  @endforeach
                                              </select>
                                          </div>
                                      </div>
                                      <div class="modal-footer">
                                          <button type="submit" class="btn btn-success">Guardar</button>
                                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                      </div>
                                  </div>
                              </form>
                          </div>
                      </div>

                      <!-- Modal para eliminar tarima -->
                      <div class="modal fade" id="modalEliminarTarima{{ $tarima->id }}" tabindex="-1">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                              <h5 class="modal-title">
                                <i class="bi bi-exclamation-triangle me-2"></i>Eliminar Tarima
                              </h5>
                              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('tarima.eliminar', $tarima->id) }}" method="POST">
                              @csrf
                              @method('DELETE')
                              <div class="modal-body">
                                <p><strong>¿Está seguro de eliminar esta tarima?</strong></p>
                                <p class="text-muted">Código: <strong>{{ $tarima->codigo }}</strong></p>
                                
                                <div class="mb-3">
                                  <label for="observacionesTarima{{ $tarima->id }}" class="form-label">
                                    Observaciones <span class="text-danger">*</span>
                                  </label>
                                  <textarea name="observaciones" id="observacionesTarima{{ $tarima->id }}" 
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
                    </tbody>

                  </table>

                </div>

              </div>
            </div><!-- End Recent Sales -->

          </div>
        </div><!-- End Left side columns -->
      </div>
      
    </section>

    <!-- Modal para envío múltiple a Pre-Enfriado -->
    <div class="modal fade" id="modalEnviarMultiple" tabindex="-1">
      <div class="modal-dialog">
        <form action="{{ route('enfrio.guardar.multiple') }}" method="POST" id="formEnviarMultiple">
          @csrf
          <div class="modal-content">
            <div class="modal-header bg-success text-white">
              <h5 class="modal-title">
                <i class="bi bi-thermometer-high me-2"></i>Enviar Tarimas a Pre-Enfriado
              </h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <p><strong>Tarimas seleccionadas: <span id="totalSeleccionadas">0</span></strong></p>
              <div id="listaTarimassSeleccionadas" class="mb-3"></div>
              
              <div class="mb-3">
                <label class="form-label">Cámara de Pre-Enfriado <span class="text-danger">*</span></label>
                <select name="idcamara" class="form-select" required>
                  <option value="">Seleccione una cámara</option>
                  @foreach($camaras as $camara)
                    @if(strtoupper($camara->tipo) == 'PREENFRIADO' || strtoupper($camara->tipo) == 'PRE ENFRIADO')
                      <option value="{{ $camara->id }}">
                        {{ $camara->cooler->nombrecooler }} - {{ $camara->codigo }}
                      </option>
                    @endif
                  @endforeach
                </select>
              </div>
              
              <input type="hidden" name="tarimas_ids" id="tarimasIdsInput">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="bi bi-x-circle me-1"></i>Cancelar
              </button>
              <button type="submit" class="btn btn-success">
                <i class="bi bi-check-circle me-1"></i>Enviar a Pre-Enfriado
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.checkbox-tarima');
        const checkboxTodas = document.getElementById('checkboxTodas');
        const btnEnviarMultiple = document.getElementById('btnEnviarMultiple');
        const contadorSeleccionadas = document.getElementById('contadorSeleccionadas');
        const btnSeleccionarTodas = document.getElementById('btnSeleccionarTodas');
        const btnDeseleccionarTodas = document.getElementById('btnDeseleccionarTodas');
        
        // Función para actualizar contador y botón
        function actualizarSeleccion() {
          const seleccionadas = document.querySelectorAll('.checkbox-tarima:checked');
          const cantidad = seleccionadas.length;
          
          contadorSeleccionadas.textContent = cantidad;
          btnEnviarMultiple.disabled = cantidad === 0;
          
          // Actualizar checkbox de "todas"
          const todasMarcadas = checkboxes.length > 0 && 
                                 seleccionadas.length === checkboxes.length;
          checkboxTodas.checked = todasMarcadas;
        }
        
        // Event listener para cada checkbox individual
        checkboxes.forEach(checkbox => {
          checkbox.addEventListener('change', actualizarSeleccion);
        });
        
        // Checkbox de "todas"
        checkboxTodas.addEventListener('change', function() {
          checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
          });
          actualizarSeleccion();
        });
        
        // Botón seleccionar todas
        btnSeleccionarTodas.addEventListener('click', function() {
          checkboxes.forEach(checkbox => checkbox.checked = true);
          actualizarSeleccion();
        });
        
        // Botón deseleccionar todas
        btnDeseleccionarTodas.addEventListener('click', function() {
          checkboxes.forEach(checkbox => checkbox.checked = false);
          actualizarSeleccion();
        });
        
        // Al abrir el modal, actualizar la lista de tarimas
        document.getElementById('modalEnviarMultiple').addEventListener('show.bs.modal', function() {
          const seleccionadas = document.querySelectorAll('.checkbox-tarima:checked');
          const listaTarimas = document.getElementById('listaTarimassSeleccionadas');
          const totalSeleccionadas = document.getElementById('totalSeleccionadas');
          const tarimasIdsInput = document.getElementById('tarimasIdsInput');
          
          let ids = [];
          let html = '<ul class="list-group">';
          
          seleccionadas.forEach(checkbox => {
            const tarimaId = checkbox.dataset.tarimaId;
            const folio = checkbox.dataset.folio;
            const row = checkbox.closest('tr');
            const codigo = row.querySelector('td:nth-child(3)').textContent.trim();
            
            ids.push(tarimaId);
            html += `<li class="list-group-item">
              <strong>${codigo}</strong> - Folio: ${folio}
            </li>`;
          });
          
          html += '</ul>';
          listaTarimas.innerHTML = html;
          totalSeleccionadas.textContent = ids.length;
          tarimasIdsInput.value = ids.join(',');
        });
      });
    </script>
@endsection