@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
    <div class="pagetitle">
      <h1>Conservaciones</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active">Conservación</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                });
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '¡Error!',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Aceptar'
                });
            });
        </script>
    @endif
    @if (session('error_multiple'))
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '¡Error!',
                text: '{{ session('error_multiple') }}',
                icon: 'error',
                confirmButtonColor: '#d33',
                confirmButtonText: 'Aceptar'
            });
        
            // Abrir automáticamente el modal nuevamente
            var modal = new bootstrap.Modal(document.getElementById('modalEmbarcacionMultiple'));
            modal.show();
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
                  <h5 class="card-title">Conservación <span>| Listados</span></h5>
                  
                  <!-- Botones de acción múltiple -->
                  <div class="mb-3">
                    <button type="button" class="btn btn-primary" id="btnCrearEmbarcacionMultiple" disabled
                            data-bs-toggle="modal" data-bs-target="#modalEmbarcacionMultiple">
                      <i class="bi bi-truck me-1"></i>
                      Crear Embarcación con Seleccionadas (<span id="contadorSeleccionadasCons">0</span>)
                    </button>
                    <button type="button" class="btn btn-secondary" id="btnSeleccionarTodasCons">
                      <i class="bi bi-check-all me-1"></i>Seleccionar Todas
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="btnDeseleccionarTodasCons">
                      <i class="bi bi-x-circle me-1"></i>Deseleccionar Todas
                    </button>
                  </div>
                  
                  <table class="table table-borderless datatable" id="tablaConservaciones">
                   <thead>
                    <tr>
                      <th>
                        <input type="checkbox" id="checkboxTodasCons" title="Seleccionar todas">
                      </th>
                      <th>#</th>
                      <th>IdTarima</th>
                      <th>Tarima</th>
                      <th>Folio</th>
                      <th>Ubicación</th>
                      <th>Conservación</th>
                      <th>Sucursal</th>
                      <th>Cámara</th>
                      <th>Fecha</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($conservaciones as $data)
                      <tr @if($loop->first) class="table-success" @endif>
                        @php
                            $comercializadora = $data->tarima?->tarimaDetarec->first()?->detalle?->recepcion?->contrato?->comercializadora;
                        @endphp

                        <td>
                            <input type="checkbox" 
                                  class="checkbox-conservacion" 
                                  data-conservacion-id="{{ $data->id }}"
                                  @if($comercializadora)
                                      data-comercializadora="{{ json_encode($comercializadora) }}"
                                  @endif>
                        </td>
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
                                        'finalizado' => 'FINALIZADO'
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
                        <td>Conservación-{{ $data->camara->codigo }}</td>
                        <td>{{ $data->created_at }}</td>
                        <td>
                          <a href="{{ route('conservacion.editar', $data->tarima->id) }}" class="btn btn-sm btn-success" title="Editar">
                            <i class="bi bi-pencil-square"></i>
                          </a>
                          <a href="{{ route('embarcacion.crear', $data->tarima->id) }}" class="btn btn-sm btn-primary" title="Crear Embarque">
                              <i class="bi bi-truck"></i>
                          </a> 
                          <a href="{{ route('conservacion.mostrarid', $data->tarima->id) }}" class="btn btn-sm btn-dark" title="Ver Conservación">
                              <i class="bi bi-eye"></i>
                          </a>
                          <a href="{{ route('conservacion.pdf', $data->id) }}" class="btn btn-sm btn-secondary" title="Ver PDF" target="_blank">
                              <i class="bi bi-file-pdf"></i>
                          </a>
                          <button type="button" class="btn btn-sm btn-danger" title="Eliminar Conservación" 
                                      data-bs-toggle="modal" data-bs-target="#modalEliminarConservacion{{ $data->id }}">
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

  {{-- Modales para eliminar conservaciones --}}
  @foreach($conservaciones as $data)
    <div class="modal fade" id="modalEliminarConservacion{{ $data->id }}" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title">
              <i class="bi bi-exclamation-triangle me-2"></i>Eliminar Conservación
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="{{ route('conservacion.eliminar', $data->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-body">
              <p><strong>¿Está seguro de eliminar esta conservación?</strong></p>
              <p class="text-muted">Tarima: <strong>{{ $data->tarima->codigo }}</strong></p>
              
              <div class="mb-3">
                <label for="observacionesConservacion{{ $data->id }}" class="form-label">
                  Observaciones <span class="text-danger">*</span>
                </label>
                <textarea name="observaciones" id="observacionesConservacion{{ $data->id }}" 
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

  <!-- Modal para crear embarcación múltiple -->
  <div class="modal fade" id="modalEmbarcacionMultiple" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">
            <i class="bi bi-truck me-2"></i>Crear Embarcación con Múltiples Conservaciones
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
       <form action="{{ route('embarcacion.procesar.multiple') }}" method="POST">

          @csrf
          <div class="modal-body">
            <div class="alert alert-info">
              <i class="bi bi-info-circle me-1"></i>
              <strong>Importante:</strong> Solo se pueden embarcar conservaciones de la misma comercializadora.
            </div>
            
            <p><strong>Conservaciones seleccionadas: <span id="totalSeleccionadasModal">0</span></strong></p>
            <p><strong>Comercializadora: <span id="folioSeleccionado" class="badge bg-success">-</span></strong></p>
            
            <div id="listaConservacionesSeleccionadas" class="mb-3"></div>
            
            <input type="hidden" name="conservaciones_ids" id="conservacionesIdsInput">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="bi bi-x-circle me-1"></i>Cancelar
            </button>
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-truck me-1"></i>Crear Embarcación
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxTodas = document.getElementById('checkboxTodasCons');
        const btnCrearEmbarcacion = document.getElementById('btnCrearEmbarcacionMultiple');
        const contadorSeleccionadas = document.getElementById('contadorSeleccionadasCons');
        const btnSeleccionarTodas = document.getElementById('btnSeleccionarTodasCons');
        const btnDeseleccionarTodas = document.getElementById('btnDeseleccionarTodasCons');
        
        // Función para mostrar alerta de error con SweetAlert
        function mostrarAlertaError(mensaje) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: mensaje,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Entendido'
            });
        }

        // Función para obtener todos los checkboxes
        function getCheckboxes() {
            return document.querySelectorAll('.checkbox-conservacion');
        }
        
        // Función para validar que todas las seleccionadas sean de la misma comercializadora
        function validarMismaComercializadora() {
            const seleccionadas = document.querySelectorAll('.checkbox-conservacion:checked');
            if (seleccionadas.length === 0) return { valido: true, comercializadora: null };
            
            try {
                const primeraComercializadora = JSON.parse(seleccionadas[0].dataset.comercializadora);
                const todasMismaComercializadora = Array.from(seleccionadas).every(cb => {
                    const comercializadora = JSON.parse(cb.dataset.comercializadora || '{}');
                    return comercializadora && comercializadora.id === primeraComercializadora.id;
                });
                
                return { 
                    valido: todasMismaComercializadora, 
                    comercializadora: todasMismaComercializadora ? primeraComercializadora : null 
                };
            } catch (e) {
                console.error('Error validando comercializadoras:', e);
                return { valido: false, comercializadora: null };
            }
        }
        
        // Función para actualizar contador y botón
        function actualizarSeleccion() {
            const seleccionadas = document.querySelectorAll('.checkbox-conservacion:checked');
            const cantidad = seleccionadas.length;
            
            contadorSeleccionadas.textContent = cantidad;
            
            // Validar que todas sean de la misma comercializadora
            const validacion = validarMismaComercializadora();
            btnCrearEmbarcacion.disabled = cantidad === 0 || !validacion.valido;
            
            // Mostrar alerta si hay seleccionadas pero no son de la misma comercializadora
            const alertaExistente = document.getElementById('alertaComercializadoraDiferente');
            if (alertaExistente) {
                alertaExistente.remove();
            }
            
            if (cantidad > 0 && !validacion.valido) {
              // Mostrar SweetAlert2 en lugar de la alerta de Bootstrap
              Swal.fire({
                  title: '¡Atención!',
                  html: `
                      <div class="d-flex align-items-center">
                          <i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i>
                          <span>Solo puedes seleccionar conservaciones de la misma comercializadora.</span>
                      </div>
                  `,
                  icon: 'warning',
                  confirmButtonColor: '#3085d6',
                  confirmButtonText: 'Entendido',
                  customClass: {
                      popup: 'border-warning'
                  }
              });
          }
            
            // Actualizar checkbox de "todas"
            const checkboxes = getCheckboxes();
            const todasMarcadas = checkboxes.length > 0 && seleccionadas.length === checkboxes.length;
            checkboxTodas.checked = todasMarcadas;
            
            // Actualizar modal
            actualizarModal(seleccionadas);
        }
        
        // Función para actualizar el modal
        function actualizarModal(seleccionadas) {
            document.getElementById('totalSeleccionadasModal').textContent = seleccionadas.length;
            
            const lista = document.getElementById('listaConservacionesSeleccionadas');
            lista.innerHTML = '';
            
            if (seleccionadas.length > 0) {
                const ul = document.createElement('ul');
                ul.className = 'list-group';
                
                seleccionadas.forEach(cb => {
                    try {
                        const comercializadora = JSON.parse(cb.dataset.comercializadora || '{}');
                        const li = document.createElement('li');
                        li.className = 'list-group-item d-flex justify-content-between align-items-center';
                        li.innerHTML = `
                            Conservación ID: ${cb.dataset.conservacionId}
                            ${comercializadora.nombrecomercializadora ? 
                                `<span class="badge bg-primary">${comercializadora.nombrecomercializadora}</span>` : 
                                '<span class="badge bg-secondary">Sin comercializadora</span>'
                            }
                        `;
                        ul.appendChild(li);
                    } catch (e) {
                        console.error('Error mostrando conservación:', e);
                    }
                });
                
                lista.appendChild(ul);
            }
            
            // Actualizar input hidden con IDs
            const ids = Array.from(seleccionadas).map(cb => cb.dataset.conservacionId);
            document.getElementById('conservacionesIdsInput').value = ids.join(',');
        }
        
        // Usar delegación de eventos para checkboxes
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('checkbox-conservacion')) {
                actualizarSeleccion();
            }
        });
        
        // Checkbox de "todas"
        checkboxTodas.addEventListener('change', function() {
            const checkboxes = getCheckboxes();
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            actualizarSeleccion();
        });
        
        // Botón seleccionar todas
        btnSeleccionarTodas.addEventListener('click', function() {
            const checkboxes = getCheckboxes();
            checkboxes.forEach(checkbox => checkbox.checked = true);
            actualizarSeleccion();
        });
        
        // Botón deseleccionar todas
        btnDeseleccionarTodas.addEventListener('click', function() {
            const checkboxes = getCheckboxes();
            checkboxes.forEach(checkbox => checkbox.checked = false);
            actualizarSeleccion();
        });
        
        // Validación antes de enviar el formulario
        document.getElementById('formEmbarcacionMultiple').addEventListener('submit', function(e) {
            const validacion = validarMismaComercializadora();
            if (!validacion.valido) {
                e.preventDefault();
                mostrarAlertaError('Solo puedes crear una embarcación con conservaciones de la misma comercializadora.');
                return false;
            }
        });
        const form = document.getElementById('formEmbarcacionMultiple');

    if (!form) {
        console.error("⚠ No se encontró el formulario formEmbarcacionMultiple");
        return;
    }

    form.addEventListener('submit', function(e) {
        const validacion = validarMismaComercializadora();

        if (!validacion.valido) {
            e.preventDefault();
            mostrarAlertaError('Solo puedes crear una embarcación con conservaciones de la misma comercializadora.');
            return false;
        }
    });
    });
</script>
@endsection