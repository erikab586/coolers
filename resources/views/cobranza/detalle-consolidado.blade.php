@extends('layouts.app')

@section('title', 'Detalle Consolidado de Cobranzas')

@section('content')
    <div class="pagetitle">
      <h1>Detalle Consolidado de Cobranzas</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('cobranza') }}">Cobranzas</a></li>
          <li class="breadcrumb-item active">Detalle Consolidado</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          
          <!-- Filtros Aplicados -->
          @if(!empty($filtrosAplicados))
            <div class="card mb-3">
              <div class="card-body bg-light">
                <h5 class="card-title"><i class="bi bi-funnel"></i> Filtros Aplicados</h5>
                <div class="row">
                  @if(isset($filtrosAplicados['comercializadora']))
                    <div class="col-md-3">
                      <strong>Comercializadora:</strong><br>
                      <span class="badge bg-primary">{{ $filtrosAplicados['comercializadora'] }}</span>
                    </div>
                  @endif
                  @if(isset($filtrosAplicados['fecha']))
                    <div class="col-md-3">
                      <strong>Fecha:</strong><br>
                      <span class="badge bg-info">{{ $filtrosAplicados['fecha'] }}</span>
                    </div>
                  @endif
                  @if(isset($filtrosAplicados['rango']))
                    <div class="col-md-4">
                      <strong>Rango de Fechas:</strong><br>
                      <span class="badge bg-info">{{ $filtrosAplicados['rango'] }}</span>
                    </div>
                  @endif
                  @if(isset($filtrosAplicados['mes']))
                    <div class="col-md-3">
                      <strong>Mes:</strong><br>
                      <span class="badge bg-info">{{ $filtrosAplicados['mes'] }}</span>
                    </div>
                  @endif
                  @if(isset($filtrosAplicados['anio']))
                    <div class="col-md-3">
                      <strong>Año:</strong><br>
                      <span class="badge bg-info">{{ $filtrosAplicados['anio'] }}</span>
                    </div>
                  @endif
                </div>
              </div>
            </div>
          @endif

          <!-- Resumen de Totales -->
          <div class="row mb-4">
            <div class="col-lg-3 col-md-6">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title">Total General</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="ps-3">
                      <h6>${{ number_format($totalGeneral, 2) }}</h6>
                      <span class="text-muted small pt-2">{{ $cobranzas->count() }} cobranzas</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-3 col-md-6">
              <div class="card info-card revenue-card">
                <div class="card-body">
                  <h5 class="card-title">Total Preenfriado</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-snow"></i>
                    </div>
                    <div class="ps-3">
                      <h6>${{ number_format($totalPreenfriado, 2) }}</h6>
                      <span class="text-muted small pt-2">{{ $cobranzas->sum('tiempo_preenfriado') }} hras</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-3 col-md-6">
              <div class="card info-card customers-card">
                <div class="card-body">
                  <h5 class="card-title">Total Conservación</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-thermometer-snow"></i>
                    </div>
                    <div class="ps-3">
                      <h6>${{ number_format($totalConservacion, 2) }}</h6>
                      <span class="text-muted small pt-2">{{ $cobranzas->sum('tiempo_conservacion') }} hras</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-3 col-md-6">
              <div class="card info-card">
                <div class="card-body">
                  <h5 class="card-title">Estado de Pagos</h5>
                  <div class="ps-3">
                    <p class="mb-1"><span class="badge bg-success">Pagadas:</span> ${{ number_format($totalPagado, 2) }}</p>
                    <p class="mb-0"><span class="badge bg-warning text-dark">Pendientes:</span> ${{ number_format($totalPendiente, 2) }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Listado Detallado de Cobranzas -->
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Detalle de Cobranzas <span>| {{ $cobranzas->count() }} registros</span></h5>

              <!-- Formulario para cambio masivo de estatus -->
              <form id="formCambioMasivo" action="{{ route('cobranza.cambiarEstatusMasivo') }}" method="POST">
                @csrf
                <div class="row mb-3">
                  <div class="col-md-8">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="seleccionarTodas()">
                      <i class="bi bi-check-square"></i> Seleccionar Todas
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deseleccionarTodas()">
                      <i class="bi bi-square"></i> Deseleccionar Todas
                    </button>
                  </div>
                  <div class="col-md-4 text-end">
                    <select name="estatus" class="form-select form-select-sm d-inline-block w-auto" required>
                      <option value="">Cambiar a...</option>
                      <option value="PAGADA">PAGADA</option>
                      <option value="PENDIENTE">PENDIENTE</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-success">
                      <i class="bi bi-arrow-repeat"></i> Aplicar
                    </button>
                  </div>
                </div>

              @if($cobranzas->count() > 0)
                <!-- Cobranzas agrupadas por comercializadora -->
                @foreach($cobranzasAgrupadas as $comercializadoraId => $cobranzasGrupo)
                  @php
                    $primeraCobranza = $cobranzasGrupo->first();
                    $comercializadora = $primeraCobranza->recepcion->contrato->comercializadora ?? null;
                    $totalGrupo = $cobranzasGrupo->sum(function($c) { return $c->total_preenfriado + $c->total_conservacion; });
                  @endphp

                  <div class="card mb-4 border-primary">
                    <div class="card-header bg-primary text-white">
                      <div class="row align-items-center">
                        <div class="col-md-6">
                          <h5 class="mb-0">
                            @if($comercializadora)
                              <img src="{{ asset($comercializadora->imgcomercializadora ?? 'imagenes/comercializadoras/comercializadora.png') }}" 
                                   alt="{{ $comercializadora->nombrecomercializadora }}" 
                                   width="40" height="40" class="rounded-circle me-2">
                              {{ $comercializadora->nombrecomercializadora }}
                            @else
                              Sin Comercializadora
                            @endif
                          </h5>
                        </div>
                        <div class="col-md-6 text-end">
                          <span class="badge bg-light text-dark">{{ $cobranzasGrupo->count() }} cobranzas</span>
                          <span class="badge bg-warning text-dark ms-2">Total: ${{ number_format($totalGrupo, 2) }}</span>
                        </div>
                      </div>
                    </div>
                    <div class="card-body p-0">
                      <div class="table-responsive">
                        <table class="table table-hover mb-0">
                          <thead class="table-light">
                            <tr>
                              <th width="50">
                                <input type="checkbox" class="form-check-input select-group" data-group="{{ $comercializadoraId }}">
                              </th>
                              <th>Folio</th>
                              <th>Producto</th>
                              <th>Cantidad</th>
                              <th>Fecha</th>
                              <th>Preenfriado</th>
                              <th>Conservación</th>
                              <th>Total</th>
                              <th>Estatus</th>
                              <th>Acciones</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($cobranzasGrupo as $cobranza)
                              <tr>
                                <td>
                                  <input type="checkbox" name="cobranzas[]" value="{{ $cobranza->id }}" class="form-check-input cobranza-checkbox group-{{ $comercializadoraId }}">
                                </td>
                                <td><span class="badge bg-success">{{ $cobranza->folio }}</span></td>
                                <td>
                                  <strong>{{ $cobranza->fruta }}</strong><br>
                                  <small class="text-muted">{{ $cobranza->variedad }} - {{ $cobranza->presentacion }}</small>
                                </td>
                                <td><span class="badge bg-primary">{{ $cobranza->cantidad }}</span></td>
                                <td>{{ $cobranza->fecha_recepcion ? $cobranza->fecha_recepcion->format('d/m/Y') : 'N/A' }}</td>
                                <td>${{ number_format($cobranza->total_preenfriado, 2) }}</td>
                                <td>${{ number_format($cobranza->total_conservacion, 2) }}</td>
                                <td><strong>${{ number_format($cobranza->total_preenfriado + $cobranza->total_conservacion, 2) }}</strong></td>
                                <td>
                                  @if($cobranza->estatus == 'PAGADA')
                                    <span class="badge bg-success">PAGADA</span>
                                  @else
                                    <span class="badge bg-warning text-dark">PENDIENTE</span>
                                  @endif
                                </td>
                                <td>
                                  <a href="{{ route('cobranza.verdetalle', $cobranza->id) }}" class="btn btn-sm btn-outline-primary" title="Ver detalle">
                                    <i class="bi bi-eye"></i>
                                  </a>
                                </td>
                              </tr>
                            @endforeach
                          </tbody>
                          <tfoot class="table-light">
                            <tr>
                              <th colspan="7" class="text-end">Subtotal {{ $comercializadora->nombrecomercializadora ?? 'Sin Comercializadora' }}:</th>
                              <th colspan="3"><strong>${{ number_format($totalGrupo, 2) }}</strong></th>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                    </div>
                  </div>
                @endforeach

                <!-- Resumen Final -->
                <div class="card bg-success text-white">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-8">
                        <h5 class="text-white"><i class="bi bi-calculator"></i> Resumen Total</h5>
                        <p class="mb-0">Total de {{ $cobranzas->count() }} cobranzas</p>
                      </div>
                      <div class="col-md-4 text-end">
                        <h3 class="text-white mb-0">${{ number_format($totalGeneral, 2) }}</h3>
                        <small>Total General</small>
                      </div>
                    </div>
                  </div>
                </div>

              @else
                <div class="alert alert-info">
                  <i class="bi bi-info-circle me-1"></i>
                  No se encontraron cobranzas con los filtros aplicados.
                </div>
              @endif

              </form>
              <!-- Fin formulario cambio masivo -->

              <!-- Botones de Acción -->
              <div class="row mt-4">
                <div class="col-md-12">
                  <a href="{{ route('cobranza') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver a Cobranzas
                  </a>
                  <!--button onclick="window.print()" class="btn btn-primary">
                    <i class="bi bi-printer"></i> Imprimir
                  </button-->
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </section>

    <style>
      @media print {
        .btn, .breadcrumb, .pagetitle nav, .form-check-input {
          display: none !important;
        }
      }
    </style>

    <script>
      // Seleccionar todas las cobranzas
      function seleccionarTodas() {
        document.querySelectorAll('.cobranza-checkbox').forEach(checkbox => {
          checkbox.checked = true;
        });
        document.querySelectorAll('.select-group').forEach(checkbox => {
          checkbox.checked = true;
        });
      }

      // Deseleccionar todas las cobranzas
      function deseleccionarTodas() {
        document.querySelectorAll('.cobranza-checkbox').forEach(checkbox => {
          checkbox.checked = false;
        });
        document.querySelectorAll('.select-group').forEach(checkbox => {
          checkbox.checked = false;
        });
      }

      // Manejar selección por grupo (comercializadora)
      document.addEventListener('DOMContentLoaded', function() {
        // Checkbox de grupo selecciona/deselecciona todas las cobranzas del grupo
        document.querySelectorAll('.select-group').forEach(groupCheckbox => {
          groupCheckbox.addEventListener('change', function() {
            const groupId = this.getAttribute('data-group');
            const isChecked = this.checked;
            document.querySelectorAll('.group-' + groupId).forEach(checkbox => {
              checkbox.checked = isChecked;
            });
          });
        });

        // Actualizar checkbox de grupo si todas las cobranzas están seleccionadas
        document.querySelectorAll('.cobranza-checkbox').forEach(checkbox => {
          checkbox.addEventListener('change', function() {
            const groupClass = Array.from(this.classList).find(c => c.startsWith('group-'));
            if (groupClass) {
              const groupId = groupClass.replace('group-', '');
              const groupCheckboxes = document.querySelectorAll('.' + groupClass);
              const allChecked = Array.from(groupCheckboxes).every(cb => cb.checked);
              const groupCheckbox = document.querySelector('.select-group[data-group="' + groupId + '"]');
              if (groupCheckbox) {
                groupCheckbox.checked = allChecked;
              }
            }
          });
        });

        // Validar que se haya seleccionado al menos una cobranza antes de enviar
        document.getElementById('formCambioMasivo').addEventListener('submit', function(e) {
          const checkedBoxes = document.querySelectorAll('.cobranza-checkbox:checked');
          if (checkedBoxes.length === 0) {
            e.preventDefault();
            alert('Por favor, seleccione al menos una cobranza para cambiar el estatus.');
            return false;
          }
          
          const estatus = this.querySelector('select[name="estatus"]').value;
          if (!estatus) {
            e.preventDefault();
            alert('Por favor, seleccione el nuevo estatus.');
            return false;
          }

          return confirm(`¿Está seguro de cambiar el estatus de ${checkedBoxes.length} cobranza(s) a ${estatus}?`);
        });
      });
    </script>
@endsection
