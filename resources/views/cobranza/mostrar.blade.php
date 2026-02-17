@extends('layouts.app')

@section('title', 'Cobranzas')

@section('content')
    <div class="pagetitle">
      <h1>Cobranzas</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('cobranza') }}">Cobranzas</a></li>
          @if(isset($comercializadora))
            <li class="breadcrumb-item active">{{ $comercializadora->nombrecomercializadora }}</li>
          @else
            <li class="breadcrumb-item active">Todas</li>
          @endif
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    @if(session('success'))
      <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-1"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <script>
        setTimeout(function () {
          const alertBox = document.getElementById('success-alert');
          if (alertBox) {
            alertBox.style.transition = "opacity 0.5s ease";
            alertBox.style.opacity = 0;
            setTimeout(() => alertBox.remove(), 500);
          }
        }, 5000);
      </script>
    @endif

    @if(session('error'))
      <div id="error-alert" class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-1"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <script>
        setTimeout(function () {
          const alertBox = document.getElementById('error-alert');
          if (alertBox) {
            alertBox.style.transition = "opacity 0.5s ease";
            alertBox.style.opacity = 0;
            setTimeout(() => alertBox.remove(), 500);
          }
        }, 5000);
      </script>
    @endif

    <section class="section dashboard">
      <div class="row">
        <div class="col-lg-12">
          <div class="row">
            <div class="col-12">
              <div class="card recent-sales overflow-auto">
                <div class="card-body">
                  <h5 class="card-title">Cobranzas 
                    @if(isset($comercializadora))
                      <span>| {{ $comercializadora->nombrecomercializadora }}</span>
                      <a href="{{ route('cobranza') }}" class="btn btn-sm btn-outline-secondary ms-2">
                        <i class="bi bi-x-circle"></i> Ver todas
                      </a>
                    @else
                      <span>| Todas</span>
                    @endif
                  </h5>

                  <!-- Filtros -->
                  <div class="card mb-3 bg-light">
                    <div class="card-body">
                      <form action="{{ route('cobranza') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                          <label for="comercializadora_id" class="form-label"><i class="bi bi-building"></i> Comercializadora</label>
                          <select name="comercializadora_id" id="comercializadora_id" class="form-select">
                            <option value="">Todas</option>
                            @foreach($comercializadoras as $com)
                              <option value="{{ $com->id }}" {{ (isset($filtros['comercializadora_id']) && $filtros['comercializadora_id'] == $com->id) ? 'selected' : '' }}>
                                {{ $com->nombrecomercializadora }}
                              </option>
                            @endforeach
                          </select>
                        </div>

                        <div class="col-md-3">
                          <label for="fecha" class="form-label"><i class="bi bi-calendar-date"></i> Fecha Específica</label>
                          <input type="date" name="fecha" id="fecha" class="form-control" value="{{ $filtros['fecha'] ?? '' }}">
                        </div>

                        <div class="col-md-3">
                          <label for="fecha_inicio" class="form-label"><i class="bi bi-calendar-range"></i> Fecha Inicio</label>
                          <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="{{ $filtros['fecha_inicio'] ?? '' }}">
                        </div>

                        <div class="col-md-3">
                          <label for="fecha_fin" class="form-label"><i class="bi bi-calendar-range"></i> Fecha Fin</label>
                          <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="{{ $filtros['fecha_fin'] ?? '' }}">
                        </div>

                        <div class="col-md-2">
                          <label for="mes" class="form-label"><i class="bi bi-calendar-month"></i> Mes</label>
                          <select name="mes" id="mes" class="form-select">
                            <option value="">Todos</option>
                            <option value="1" {{ (isset($filtros['mes']) && $filtros['mes'] == 1) ? 'selected' : '' }}>Enero</option>
                            <option value="2" {{ (isset($filtros['mes']) && $filtros['mes'] == 2) ? 'selected' : '' }}>Febrero</option>
                            <option value="3" {{ (isset($filtros['mes']) && $filtros['mes'] == 3) ? 'selected' : '' }}>Marzo</option>
                            <option value="4" {{ (isset($filtros['mes']) && $filtros['mes'] == 4) ? 'selected' : '' }}>Abril</option>
                            <option value="5" {{ (isset($filtros['mes']) && $filtros['mes'] == 5) ? 'selected' : '' }}>Mayo</option>
                            <option value="6" {{ (isset($filtros['mes']) && $filtros['mes'] == 6) ? 'selected' : '' }}>Junio</option>
                            <option value="7" {{ (isset($filtros['mes']) && $filtros['mes'] == 7) ? 'selected' : '' }}>Julio</option>
                            <option value="8" {{ (isset($filtros['mes']) && $filtros['mes'] == 8) ? 'selected' : '' }}>Agosto</option>
                            <option value="9" {{ (isset($filtros['mes']) && $filtros['mes'] == 9) ? 'selected' : '' }}>Septiembre</option>
                            <option value="10" {{ (isset($filtros['mes']) && $filtros['mes'] == 10) ? 'selected' : '' }}>Octubre</option>
                            <option value="11" {{ (isset($filtros['mes']) && $filtros['mes'] == 11) ? 'selected' : '' }}>Noviembre</option>
                            <option value="12" {{ (isset($filtros['mes']) && $filtros['mes'] == 12) ? 'selected' : '' }}>Diciembre</option>
                          </select>
                        </div>

                        <div class="col-md-2">
                          <label for="anio" class="form-label"><i class="bi bi-calendar"></i> Año</label>
                          <select name="anio" id="anio" class="form-select">
                            <option value="">Todos</option>
                            @for($year = date('Y'); $year >= 2020; $year--)
                              <option value="{{ $year }}" {{ (isset($filtros['anio']) && $filtros['anio'] == $year) ? 'selected' : '' }}>
                                {{ $year }}
                              </option>
                            @endfor
                          </select>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                          <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel"></i> Filtrar
                          </button>
                        </div>

                        @if(isset($filtros) && (array_filter($filtros)))
                          <div class="col-md-12">
                            <a href="{{ route('cobranza') }}" class="btn btn-sm btn-outline-secondary">
                              <i class="bi bi-x-circle"></i> Limpiar filtros
                            </a>
                            <a href="{{ route('cobranza.detalleconsolidado', request()->all()) }}" class="btn btn-sm btn-success ms-2">
                              <i class="bi bi-file-earmark-text"></i> Ver Detalle Consolidado
                            </a>
                            <a href="{{ route('cobranza.pdf.consolidado', request()->all()) }}" class="btn btn-sm btn-danger ms-2" target="_blank">
                              <i class="bi bi-file-pdf"></i> Descargar PDF
                            </a>
                          </div>
                        @endif
                      </form>
                    </div>
                  </div>
                  <!-- Fin Filtros -->

                  <!-- Botón de Conversión de Moneda (siempre visible) -->
                  @if(isset($cobranzas) && count($cobranzas) > 0)
                    <div class="mb-3">
                      <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalConversionMoneda">
                        <i class="bi bi-currency-exchange"></i> Convertir Moneda USD → MXN
                      </button>
                      <small class="text-muted ms-2">
                        <i class="bi bi-info-circle"></i> Convierte las cobranzas en dólares (USD) a pesos mexicanos (MXN)
                      </small>
                    </div>
                  @endif

                  <!-- Indicadores de filtros activos -->
                  @if(isset($filtros) && array_filter($filtros))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                      <i class="bi bi-info-circle me-2"></i>
                      <strong>Filtros activos:</strong>
                      @if(isset($filtros['comercializadora_id']) && $filtros['comercializadora_id'])
                        <span class="badge bg-primary ms-2">
                          Comercializadora: {{ $comercializadoras->find($filtros['comercializadora_id'])->nombrecomercializadora }}
                        </span>
                      @endif
                      @if(isset($filtros['fecha']) && $filtros['fecha'])
                        <span class="badge bg-primary ms-2">
                          Fecha: {{ \Carbon\Carbon::parse($filtros['fecha'])->format('d/m/Y') }}
                        </span>
                      @endif
                      @if(isset($filtros['fecha_inicio']) && $filtros['fecha_inicio'] && isset($filtros['fecha_fin']) && $filtros['fecha_fin'])
                        <span class="badge bg-primary ms-2">
                          Rango: {{ \Carbon\Carbon::parse($filtros['fecha_inicio'])->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($filtros['fecha_fin'])->format('d/m/Y') }}
                        </span>
                      @endif
                      @if(isset($filtros['mes']) && $filtros['mes'])
                        <span class="badge bg-primary ms-2">
                          Mes: {{ DateTime::createFromFormat('!m', $filtros['mes'])->format('F') }}
                        </span>
                      @endif
                      @if(isset($filtros['anio']) && $filtros['anio'])
                        <span class="badge bg-primary ms-2">
                          Año: {{ $filtros['anio'] }}
                        </span>
                      @endif
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                  @endif

                  @if(isset($cobranzas) && count($cobranzas) > 0)
                    <table class="table table-borderless datatable">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th>Folio</th>
                          <th>Comercializadora</th>
                          <th>Cantidad</th>
                          <th>Fecha</th>
                          <th>Moneda</th>
                          <th>Preenfriado</th>
                          <th>Conservación</th>
                          <th>Andén</th>
                          <th>Subtotal</th>
                          <th>IVA (16%)</th>
                          <th>Total</th>
                          <th>Regla</th>
                          <th>Estatus</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($cobranzas as $index => $cobranza)
                          <tr @if($loop->first) class="table-success" @endif>
                            <th scope="row">{{ $index + 1 }}</th>
                            <td><span class="badge bg-success">{{ $cobranza->folio }}</span></td>
                            <td>
                              @if($cobranza->recepcion && $cobranza->recepcion->contrato && $cobranza->recepcion->contrato->comercializadora)
                                <a href="{{ route('cobranza.porcomercializadora', $cobranza->recepcion->contrato->comercializadora->id) }}" class="text-primary">
                                  <img src="{{ asset($cobranza->recepcion->contrato->comercializadora->imgcomercializadora ?? 'imagenes/comercializadoras/comercializadora.png') }}" 
                                       alt="{{ $cobranza->recepcion->contrato->comercializadora->nombrecomercializadora }}" 
                                       width="30" height="30" class="rounded-circle me-2">
                                  {{ $cobranza->recepcion->contrato->comercializadora->nombrecomercializadora }}
                                </a>
                              @else
                                N/A
                              @endif
                            </td>
                            <td><strong>{{ $cobranza->cantidad }}</strong> cajas</td>
                            <td>{{ $cobranza->fecha_recepcion ? $cobranza->fecha_recepcion->format('d/m/Y') : 'N/A' }}</td>
                            <td>
                              @if($cobranza->moneda_contrato == 'USD' || $cobranza->moneda_contrato == 'DOLAR')
                                <span class="badge bg-success" title="Dólares Americanos">USD</span>
                              @else
                                <span class="badge bg-info" title="Pesos Mexicanos">MXN</span>
                              @endif
                            </td>
                            <td>
                              @if($cobranza->subtotal_preenfriado > 0)
                                <span class="text-success">${{ number_format($cobranza->subtotal_preenfriado, 2) }}</span>
                              @else
                                <span class="text-muted">-</span>
                              @endif
                            </td>
                            <td>
                              @if($cobranza->subtotal_conservacion > 0)
                                <span class="text-info">${{ number_format($cobranza->subtotal_conservacion, 2) }}</span>
                              @else
                                <span class="text-muted">-</span>
                              @endif
                            </td>
                            <td>
                              @if($cobranza->subtotal_anden > 0)
                                <span class="text-warning">${{ number_format($cobranza->subtotal_anden, 2) }}</span>
                              @else
                                <span class="text-muted">-</span>
                              @endif
                            </td>
                            <td><strong>${{ number_format($cobranza->subtotal_preenfriado + $cobranza->subtotal_conservacion + $cobranza->subtotal_anden, 2) }}</strong></td>
                            <td><span class="text-danger">${{ number_format($cobranza->iva, 2) }}</span></td>
                            <td><strong class="text-primary">${{ number_format($cobranza->total, 2) }}</strong></td>
                            <td>
                              @if($cobranza->regla_aplicada)
                                <span class="badge bg-info" title="Regla {{ $cobranza->regla_aplicada }} aplicada">R{{ $cobranza->regla_aplicada }}</span>
                              @else
                                <span class="badge bg-secondary">-</span>
                              @endif
                            </td>
                            <td>
                              @if($cobranza->estatus == 'PAGADA')
                                <span class="badge bg-success">PAGADA</span>
                              @else
                                <span class="badge bg-warning text-dark">PENDIENTE</span>
                              @endif
                            </td>
                            <td>
                              <a href="{{ route('cobranza.verdetalle', $cobranza->folio) }}" class="btn btn-dark btn-sm" title="Ver detalle"><i class="bi bi-eye"></i></a>
                              <a href="{{ route('cobranza.cambiarestatus', $cobranza->id) }}" class="btn btn-danger btn-sm" title="Cambiar estatus"><i class="bi bi-arrow-repeat"></i></a>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                      <tfoot>
                        <tr class="table-light">
                          <th colspan="6" class="text-end">Subtotales:</th>
                          <th class="text-success">${{ number_format($cobranzas->sum('subtotal_preenfriado'), 2) }}</th>
                          <th class="text-info">${{ number_format($cobranzas->sum('subtotal_conservacion'), 2) }}</th>
                          <th class="text-warning">${{ number_format($cobranzas->sum('subtotal_anden'), 2) }}</th>
                          <th><strong>${{ number_format($cobranzas->sum('subtotal_preenfriado') + $cobranzas->sum('subtotal_conservacion') + $cobranzas->sum('subtotal_anden'), 2) }}</strong></th>
                          <th class="text-danger">${{ number_format($cobranzas->sum('iva'), 2) }}</th>
                          <th colspan="4"></th>
                        </tr>
                        <tr class="table-info">
                          <th colspan="11" class="text-end">Gran Total (con IVA):</th>
                          <th><strong class="text-primary">${{ number_format($cobranzas->sum('total'), 2) }}</strong></th>
                          <th colspan="3"></th>
                        </tr>
                        <tr class="table-warning">
                          <th colspan="11" class="text-end">Pendientes de Pago:</th>
                          <th>
                            <strong>${{ number_format($cobranzas->where('estatus', 'PENDIENTE')->sum('total'), 2) }}</strong>
                          </th>
                          <th colspan="3"></th>
                        </tr>
                        <tr class="table-success">
                          <th colspan="11" class="text-end">Pagadas:</th>
                          <th>
                            <strong>${{ number_format($cobranzas->where('estatus', 'PAGADA')->sum('total'), 2) }}</strong>
                          </th>
                          <th colspan="3"></th>
                        </tr>
                      </tfoot>
                    </table>

                    <!-- Botón adicional para ver detalle consolidado -->
                    @if(isset($filtros) && array_filter($filtros))
                      <div class="text-center mt-3">
                        <a href="{{ route('cobranza.detalleconsolidado', request()->all()) }}" class="btn btn-sm btn-success">
                          <i class="bi bi-file-earmark-text"></i> Ver Detalle Consolidado de Todas las Cobranzas
                        </a>
                        <a href="{{ route('cobranza.pdf.consolidado', request()->all()) }}" class="btn btn-sm btn-danger ms-2" target="_blank">
                          <i class="bi bi-file-pdf"></i> Descargar PDF
                        </a>
                        <button type="button" class="btn btn-sm btn-warning ms-2" data-bs-toggle="modal" data-bs-target="#modalConversionMoneda">
                          <i class="bi bi-currency-exchange"></i> Convertir Moneda USD → MXN
                        </button>
                      </div>
                    @endif

                  @else
                    <div class="alert alert-info">
                      <i class="bi bi-info-circle me-1"></i>
                      No hay registros de cobranza. Las cobranzas se generan automáticamente al crear una embarcación.
                    </div>
                  @endif

                </div>
              </div>
            </div><!-- End Recent Sales -->
          </div>
        </div><!-- End Left side columns -->
      </div>
    </section>

    <!-- Modal de Conversión de Moneda -->
    <div class="modal fade" id="modalConversionMoneda" tabindex="-1" aria-labelledby="modalConversionMonedaLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-warning">
            <h5 class="modal-title" id="modalConversionMonedaLabel">
              <i class="bi bi-currency-exchange"></i> Convertir Cobranzas de USD a MXN
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="{{ route('cobranza.convertir') }}" method="POST" id="formConversionMoneda">
            @csrf
            <div class="modal-body">
              <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Información:</strong> Esta conversión aplicará a todas las cobranzas filtradas que estén en dólares (USD). 
                Los montos se convertirán a pesos mexicanos (MXN) usando la tasa de cambio que especifique.
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="tasa_cambio" class="form-label">
                    <i class="bi bi-calculator"></i> Tasa de Cambio (1 USD = ? MXN) <span class="text-danger">*</span>
                  </label>
                  <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" step="0.01" min="0.01" class="form-control" id="tasa_cambio" name="tasa_cambio" 
                           placeholder="Ej: 20.50" required>
                    <span class="input-group-text">MXN</span>
                  </div>
                  <small class="text-muted">Ingrese la tasa de cambio actual del dólar</small>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">
                    <i class="bi bi-calendar-date"></i> Fecha de Conversión
                  </label>
                  <input type="text" class="form-control" value="{{ date('d/m/Y H:i') }}" readonly>
                  <small class="text-muted">Fecha y hora actual</small>
                </div>
              </div>

              <div class="card bg-light mb-3">
                <div class="card-body">
                  <h6 class="card-title"><i class="bi bi-list-check"></i> Resumen de Conversión</h6>
                  <div class="row">
                    <div class="col-md-6">
                      <p class="mb-1"><strong>Cobranzas en USD:</strong> <span id="cantidadUSD" class="text-primary">Calculando...</span></p>
                      <p class="mb-1"><strong>Total en USD:</strong> $<span id="totalUSD" class="text-success">0.00</span></p>
                    </div>
                    <div class="col-md-6">
                      <p class="mb-1"><strong>Total convertido (MXN):</strong> $<span id="totalMXN" class="text-danger">0.00</span></p>
                      <p class="mb-0"><small class="text-muted">El cálculo se actualizará al ingresar la tasa</small></p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Advertencia:</strong> Esta acción actualizará permanentemente las cobranzas seleccionadas. 
                Se guardará la tasa de cambio utilizada y la fecha de conversión.
              </div>

              <!-- Campos ocultos para pasar los filtros -->
              @if(isset($filtros))
                @foreach($filtros as $key => $value)
                  <input type="hidden" name="filtros[{{ $key }}]" value="{{ $value }}">
                @endforeach
              @endif
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="bi bi-x-circle"></i> Cancelar
              </button>
              <button type="submit" class="btn btn-warning" id="btnConfirmarConversion">
                <i class="bi bi-check-circle"></i> Confirmar Conversión
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <script>
      // Mejorar la experiencia de usuario con los filtros de fecha
      document.addEventListener('DOMContentLoaded', function() {
        const fechaInput = document.getElementById('fecha');
        const fechaInicioInput = document.getElementById('fecha_inicio');
        const fechaFinInput = document.getElementById('fecha_fin');
        const mesSelect = document.getElementById('mes');
        const anioSelect = document.getElementById('anio');

        // Si se selecciona una fecha específica, limpiar otros filtros de fecha
        fechaInput.addEventListener('change', function() {
          if (this.value) {
            fechaInicioInput.value = '';
            fechaFinInput.value = '';
            mesSelect.value = '';
            anioSelect.value = '';
          }
        });

        // Si se selecciona rango de fechas, limpiar otros filtros
        [fechaInicioInput, fechaFinInput].forEach(element => {
          element.addEventListener('change', function() {
            if (fechaInicioInput.value || fechaFinInput.value) {
              fechaInput.value = '';
              mesSelect.value = '';
              anioSelect.value = '';
            }
          });
        });

        // Si se selecciona mes o año, limpiar otros filtros de fecha
        [mesSelect, anioSelect].forEach(element => {
          element.addEventListener('change', function() {
            if (this.value) {
              fechaInput.value = '';
              fechaInicioInput.value = '';
              fechaFinInput.value = '';
            }
          });
        });

        // Validar que si se usa rango, ambas fechas estén completas
        document.querySelector('form').addEventListener('submit', function(e) {
          const inicio = fechaInicioInput.value;
          const fin = fechaFinInput.value;
          
          if ((inicio && !fin) || (!inicio && fin)) {
            e.preventDefault();
            alert('Por favor, complete ambas fechas (Inicio y Fin) para filtrar por rango.');
            return false;
          }
          
          if (inicio && fin && inicio > fin) {
            e.preventDefault();
            alert('La fecha de inicio no puede ser mayor que la fecha fin.');
            return false;
          }
        });

        // ========== CONVERSIÓN DE MONEDA ==========
        @if(isset($cobranzas) && count($cobranzas) > 0)
          // Calcular totales en USD de las cobranzas disponibles
          const cobranzasUSD = @json($cobranzas->where('moneda_contrato', 'USD')->values());
          const cobranzasUSD2 = @json($cobranzas->where('moneda_contrato', 'DOLAR')->values());
          const todasCobranzasUSD = [...cobranzasUSD, ...cobranzasUSD2];
          
          const cantidadCobranzasUSD = todasCobranzasUSD.length;
          const totalEnUSD = todasCobranzasUSD.reduce((sum, c) => sum + parseFloat(c.total || 0), 0);

          // Actualizar resumen en el modal
          document.getElementById('cantidadUSD').textContent = cantidadCobranzasUSD + ' cobranzas';
          document.getElementById('totalUSD').textContent = totalEnUSD.toFixed(2);

        // Calcular conversión cuando se ingresa la tasa de cambio
        const tasaCambioInput = document.getElementById('tasa_cambio');
        const totalMXNSpan = document.getElementById('totalMXN');

        tasaCambioInput.addEventListener('input', function() {
          const tasa = parseFloat(this.value) || 0;
          const totalConvertido = totalEnUSD * tasa;
          totalMXNSpan.textContent = totalConvertido.toFixed(2);
        });

        // Validación antes de enviar el formulario
        document.getElementById('formConversionMoneda').addEventListener('submit', function(e) {
          const tasa = parseFloat(tasaCambioInput.value);
          
          if (!tasa || tasa <= 0) {
            e.preventDefault();
            alert('Por favor, ingrese una tasa de cambio válida.');
            return false;
          }

          if (cantidadCobranzasUSD === 0) {
            e.preventDefault();
            alert('No hay cobranzas en dólares (USD) para convertir en los filtros actuales.');
            return false;
          }

          const confirmar = confirm(
            `¿Está seguro de convertir ${cantidadCobranzasUSD} cobranzas de USD a MXN?\n\n` +
            `Total en USD: $${totalEnUSD.toFixed(2)}\n` +
            `Tasa de cambio: $${tasa.toFixed(2)} MXN\n` +
            `Total convertido: $${(totalEnUSD * tasa).toFixed(2)} MXN\n\n` +
            `Esta acción no se puede deshacer.`
          );

          if (!confirmar) {
            e.preventDefault();
            return false;
          }
        });
        @endif
      });
    </script>
@endsection