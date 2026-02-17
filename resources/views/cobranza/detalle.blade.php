@extends('layouts.app')

@section('title', 'Detalle de Cobranza')

@section('content')
    <div class="pagetitle">
      <h1>Detalle de Cobranza</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('cobranza') }}">Cobranzas</a></li>
          <li class="breadcrumb-item active">Detalle</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Información de Cobranza - Folio: <span class="badge bg-success">{{ $cobranza->folio }}</span></h5>

              <!-- Información General -->
              <div class="row mb-4">
                <div class="col-md-6">
                  <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                      <h6 class="mb-0"><i class="bi bi-building"></i> Información de Comercializadora</h6>
                    </div>
                    <div class="card-body">
                      @if($cobranza->recepcion && $cobranza->recepcion->contrato && $cobranza->recepcion->contrato->comercializadora)
                        <div class="d-flex align-items-center mb-3">
                          <img src="{{ asset($cobranza->recepcion->contrato->comercializadora->imgcomercializadora ?? 'imagenes/comercializadoras/comercializadora.png') }}" 
                               alt="{{ $cobranza->recepcion->contrato->comercializadora->nombrecomercializadora }}" 
                               width="60" height="60" class="rounded-circle me-3">
                          <div>
                            <h5 class="mb-0">{{ $cobranza->recepcion->contrato->comercializadora->nombrecomercializadora }}</h5>
                            <small class="text-muted">Comercializadora</small>
                          </div>
                        </div>
                        @if($cobranza->recepcion->contrato->cooler)
                          <p class="mb-1"><strong>Cooler:</strong> {{ $cobranza->recepcion->contrato->cooler->nombrecooler }}</p>
                          <p class="mb-1"><strong>Tipo:</strong> <span class="badge bg-info">{{ ucfirst($cobranza->recepcion->contrato->cooler->tipocooler) }}</span></p>
                        @endif
                      @else
                        <p class="text-muted">No disponible</p>
                      @endif
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="card border-success">
                    <div class="card-header bg-success text-white">
                      <h6 class="mb-0"><i class="bi bi-info-circle"></i> Información General</h6>
                    </div>
                    <div class="card-body">
                      <p class="mb-2"><strong>Folio:</strong> <span class="badge bg-success">{{ $cobranza->folio }}</span></p>
                      <p class="mb-2"><strong>Fecha de Recepción:</strong> {{ $cobranza->fecha_recepcion ? $cobranza->fecha_recepcion->format('d/m/Y') : 'N/A' }}</p>
                      <p class="mb-2"><strong>Día de Recepción:</strong> {{ $cobranza->dia_recepcion }}</p>
                      <p class="mb-2"><strong>Moneda:</strong> <span class="badge bg-secondary">{{ $cobranza->moneda }}</span></p>
                      @if($cobranza->moneda_contrato && $cobranza->moneda_contrato != $cobranza->moneda)
                        <p class="mb-2"><strong>Moneda Contrato:</strong> <span class="badge bg-info">{{ $cobranza->moneda_contrato }}</span></p>
                        <p class="mb-2"><strong>Tipo de Cambio:</strong> ${{ number_format($cobranza->tipo_cambio, 4) }}</p>
                      @endif
                      
                      <p class="mb-2"><strong>Estatus:</strong> 
                        @if($cobranza->estatus == 'PAGADA')
                          <span class="badge bg-success">PAGADA</span>
                        @else
                          <span class="badge bg-warning text-dark">PENDIENTE</span>
                        @endif
                      </p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Información de Productos del Folio -->
              <div class="row mb-4">
                <div class="col-md-12">
                  <div class="card border-info">
                    <div class="card-header bg-info text-white">
                      <h6 class="mb-0"><i class="bi bi-box-seam"></i> Detalle de Cobranza</h6>
                    </div>
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-sm table-striped">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Fruta</th>
                              <th>Variedad</th>
                              <th>Presentación</th>
                              <th class="text-end">Cantidad (cajas)</th>
                              <th class="text-end">Preenfriado</th>
                              <th class="text-end">Conservación</th>
                              <th class="text-end">Andén</th>
                              <th class="text-end">Total</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($cobranzas as $idx => $c)
                              <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>{{ $c->fruta }}</td>
                                <td>{{ $c->variedad }}</td>
                                <td>{{ $c->presentacion }}</td>
                                <td class="text-end">{{ $c->cantidad }}</td>
                                <td class="text-end">
                                  @if($c->subtotal_preenfriado > 0)
                                    ${{ number_format($c->subtotal_preenfriado, 2) }}
                                  @else
                                    -
                                  @endif
                                </td>
                                <td class="text-end">
                                  @if($c->subtotal_conservacion > 0)
                                    ${{ number_format($c->subtotal_conservacion, 2) }}
                                  @else
                                    -
                                  @endif
                                </td>
                                <td class="text-end">
                                  @if($c->subtotal_anden > 0)
                                    ${{ number_format($c->subtotal_anden, 2) }}
                                  @else
                                    -
                                  @endif
                                </td>
                                <td class="text-end">${{ number_format($c->total, 2) }}</td>
                              </tr>
                            @endforeach
                          </tbody>
                          <tfoot>
                            <tr class="table-light">
                              <th colspan="4" class="text-end">Totales del Folio:</th>
                              <th class="text-end">{{ $totalCantidad }}</th>
                              <th class="text-end">${{ number_format($totalSubtotalPreenfriado, 2) }}</th>
                              <th class="text-end">${{ number_format($totalSubtotalConservacion, 2) }}</th>
                              <th class="text-end">${{ number_format($totalSubtotalAnden, 2) }}</th>
                              <th class="text-end"><strong>${{ number_format($totalGeneral, 2) }}</strong></th>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Desglose de Costos -->
              <div class="row mb-4">
                <div class="col-md-4">
                  <div class="card border-warning">
                    <div class="card-header bg-warning text-dark">
                      <h6 class="mb-0"><i class="bi bi-snow"></i> Preenfriado </h6>
                    </div>
                    <div class="card-body">
                      <table class="table table-sm">
                        <tr>
                          <td><strong>Monto por caja (línea de referencia):</strong></td>
                          <td class="text-end">${{ number_format($cobranza->monto_preenfriado, 2) }}</td>
                        </tr>
                        <tr>
                          <td><strong>Cantidad total folio:</strong></td>
                          <td class="text-end">{{ $totalCantidad }} cajas</td>
                        </tr>
                        <tr>
                          <td><strong>Tiempo:</strong></td>
                          <td class="text-end">{{ number_format($cobranza->tiempo_preenfriado, 2) }} horas</td>
                        </tr>
                        <tr class="table-active">
                          <td><strong>Subtotal del Folio:</strong></td>
                          <td class="text-end"><strong class="text-success">${{ number_format($totalSubtotalPreenfriado, 2) }}</strong></td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                      <h6 class="mb-0"><i class="bi bi-thermometer-snow"></i> Conservación </h6>
                    </div>
                    <div class="card-body">
                      <table class="table table-sm">
                        <tr>
                          <td><strong>Monto por caja:</strong></td>
                          <td class="text-end">${{ number_format($cobranza->monto_conservacion, 2) }}</td>
                        </tr>
                        <tr>
                          <td><strong>Cantidad total folio:</strong></td>
                          <td class="text-end">{{ $totalCantidad }} cajas</td>
                        </tr>
                        <tr>
                          <td><strong>Tiempo:</strong></td>
                          <td class="text-end">{{ number_format($cobranza->tiempo_conservacion, 2) }} horas</td>
                        </tr>
                        <tr class="table-active">
                          <td><strong>Subtotal del Folio:</strong></td>
                          <td class="text-end"><strong class="text-info">${{ number_format($totalSubtotalConservacion, 2) }}</strong></td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="card border-secondary">
                    <div class="card-header bg-secondary text-white">
                      <h6 class="mb-0"><i class="bi bi-truck"></i> Cruce de Andén </h6>
                    </div>
                    <div class="card-body">
                      <table class="table table-sm">
                        <tr>
                          <td><strong>Monto por caja:</strong></td>
                          <td class="text-end">${{ number_format($cobranza->monto_anden, 2) }}</td>
                        </tr>
                        <tr>
                          <td><strong>Cantidad total folio:</strong></td>
                          <td class="text-end">{{ $totalCantidad }} cajas</td>
                        </tr>
                        <tr>
                          <td><strong>Tiempo:</strong></td>
                          <td class="text-end">{{ number_format($cobranza->tiempo_anden, 2) }} horas</td>
                        </tr>
                        <tr class="table-active">
                          <td><strong>Subtotal del Folio:</strong></td>
                          <td class="text-end"><strong class="text-warning">${{ number_format($totalSubtotalAnden, 2) }}</strong></td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
              </div>


              <!-- Total General del Folio -->
              <div class="row">
                <div class="col-md-12">
                  <div class="card border-success">
                    <div class="card-body">
                      <table class="table table-borderless mb-0">
                        <tr>
                          <td class="text-end"><h5>Subtotal del Folio (Preenfriado + Conservación + Andén):</h5></td>
                          <td class="text-end" width="200"><h5>${{ number_format($totalSubtotalPreenfriado + $totalSubtotalConservacion + $totalSubtotalAnden, 2) }}</h5></td>
                        </tr>
                        <tr>
                          <td class="text-end"><h5>IVA (16%):</h5></td>
                          <td class="text-end"><h5 class="text-danger">${{ number_format($totalIva, 2) }}</h5></td>
                        </tr>
                        <tr class="table-success">
                          <td class="text-end"><h3 class="mb-0"><i class="bi bi-cash-stack"></i> Total a Cobrar del Folio:</h3></td>
                          <td class="text-end"><h2 class="mb-0 text-success"><strong>${{ number_format($totalGeneral, 2) }} {{ $cobranza->moneda }}</strong></h2></td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Botones de Acción -->
              <div class="row mt-4">
                <div class="col-md-12">
                  <a href="{{ route('cobranza') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                  </a>
                  <a href="{{ route('cobranza.cambiarestatus', $cobranza->id) }}" class="btn btn-warning">
                    <i class="bi bi-arrow-repeat"></i> Cambiar Estatus
                  </a>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </section>
@endsection
