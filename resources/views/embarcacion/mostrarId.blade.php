@extends('layouts.app')

@section('title', 'Detalle de Embarcación')

@section('content')
<div class="pagetitle">
  <h1>Detalle de Embarcación</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Embarcación</li>
      <li class="breadcrumb-item"><a href="{{ route('embarcacion.mostrar') }}">Volver a Embarcaciones</a></li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        
        <div class="card-body">
          <h5 class="card-title">Información General</h5>

          {{-- Encabezado tipo formato (similar a ver_pdf / mostrarId de conservación) --}}
          @php
              $primerDetalle = $embarcacion->detalles->first();
              $primerTarimaDet = $primerDetalle->tarimaDetarec ?? null;
              $primerDetRecep = $primerTarimaDet && $primerTarimaDet->detalle ? $primerTarimaDet->detalle : null;
              $primerRecepcion = $primerDetRecep && $primerDetRecep->recepcion ? $primerDetRecep->recepcion : null;
          @endphp

          <table class="table table-bordered mb-4">
              <tr>
                  <td rowspan="3" style="width: 90px; text-align:center; vertical-align:middle; padding:4px;">
                      <img src="{{ asset('assets/img/logo.jpg') }}" alt="Logo" style="max-width: 80px; max-height: 80px;">
                  </td>
                  <td colspan="4" class="text-center fw-bold" style="font-size: 20px;">Embarcación</td>
              </tr>
              <tr class="text-center fw-bold">
                  <td>Área</td>
                  <td>Clave</td>
                  <td>Emisión</td>
                  <td>Revisión</td>
              </tr>
              <tr class="text-center">
                  <td>Embarque</td>
                  <td>F-BCM-PRO-06</td>
                  <td>{{ $primerRecepcion && $primerRecepcion->fechaemision ? \Carbon\Carbon::parse($primerRecepcion->fechaemision)->format('Y-m-d') : '—' }}</td>
                  <td>{{ $primerRecepcion->revision ?? '—' }}</td>
              </tr>
              <tr>
                 <td colspan="1">{{ optional($embarcacion->detalles->first()->conservacion->detallesConservacion->first()->detalleRecepcion->recepcion)->area ?? '—' }}</td>
                  <td colspan="2" style="text-align: left;">
                      Cliente: {{ optional($primerRecepcion->contrato->comercializadora)->nombrecomercializadora ?? '—' }}
                  </td>
                  <td colspan="2" style="text-align: left;">
                      Fecha: {{ \Carbon\Carbon::now()->format('Y-m-d') }}
                  </td>
              </tr>
          </table>

          {{-- Detalle de productos (similar a ver_pdf, usando TarimaDetarec) --}}
          <h5 class="card-title mt-4">Detalle de Productos</h5>
          <table class="table table-bordered">
              <thead>
                  <tr>
                      <th>Comercializadora</th>
                      <th>Fruta</th>
                      <th>Variedad</th>
                      <th>Presentación</th>
                      <th>Cantidad</th>
                      <th>Tarima</th>
                  </tr>
              </thead>
              <tbody>
                  @forelse($embarcacion->detalles as $detalle)
                      @php
                          $tarimaDet = $detalle->tarimaDetarec ?? null;
                          $detRecep  = $tarimaDet && $tarimaDet->detalle ? $tarimaDet->detalle : null;
                          $recepcion = $detRecep && $detRecep->recepcion ? $detRecep->recepcion : null;
                      @endphp
                      @if($tarimaDet && $detRecep)
                          <tr>
                              <td>{{ optional($tarimaDet->comercializadora)->nombrecomercializadora ?? (optional($recepcion->contrato->comercializadora)->nombrecomercializadora ?? 'N/A') }}</td>
                              <td>{{ $tarimaDet->fruta->nombrefruta ?? ($detRecep->fruta->nombrefruta ?? 'N/A') }}</td>
                              <td>{{ $tarimaDet->variedad->tipofruta ?? ($detRecep->variedad->tipofruta ?? 'N/A') }}</td>
                              <td>{{ $tarimaDet->presentacion->nombrepresentacion ?? ($detRecep->presentacion->nombrepresentacion ?? 'N/A') }}</td>
                              <td>{{ $tarimaDet->cantidadcarga ?? 0 }}</td>
                              <td>{{ $tarimaDet->tarima->codigo ?? 'N/A' }}</td>
                          </tr>
                      @endif
                  @empty
                      <tr>
                          <td colspan="6" class="text-center">No hay productos embarcados</td>
                      </tr>
                  @endforelse
              </tbody>
          </table>

          {{-- Tabla de Totales por producto (similar a ver_pdf) --}}
          <h5 class="card-title mt-4">Totales por Producto</h5>
          @php
              $totalesPorProducto = [];
              foreach($embarcacion->detalles as $detalle) {
                  $tarimaDet = $detalle->tarimaDetarec ?? null;
                  $detRecep  = $tarimaDet && $tarimaDet->detalle ? $tarimaDet->detalle : null;
                  if ($tarimaDet && $detRecep) {
                      $fruta        = $tarimaDet->fruta->nombrefruta ?? ($detRecep->fruta->nombrefruta ?? 'N/A');
                      $variedad     = $tarimaDet->variedad->tipofruta ?? ($detRecep->variedad->tipofruta ?? 'N/A');
                      $presentacion = $tarimaDet->presentacion->nombrepresentacion ?? ($detRecep->presentacion->nombrepresentacion ?? 'N/A');
                      $cantidad     = $tarimaDet->cantidadcarga ?? 0;

                      $key = $fruta . '|' . $variedad . '|' . $presentacion;

                      if (!isset($totalesPorProducto[$key])) {
                          $totalesPorProducto[$key] = [
                              'fruta' => $fruta,
                              'variedad' => $variedad,
                              'presentacion' => $presentacion,
                              'total' => 0,
                          ];
                      }

                      $totalesPorProducto[$key]['total'] += $cantidad;
                  }
              }
          @endphp

          <table class="table table-bordered">
              <thead>
                  <tr>
                      <th class="text-center">Fruta</th>
                      <th class="text-center">Variedad</th>
                      <th class="text-center">Presentación</th>
                      <th class="text-center">Total</th>
                  </tr>
              </thead>
              <tbody>
                  @forelse($totalesPorProducto as $producto)
                      <tr>
                          <td class="text-center">{{ $producto['fruta'] }}</td>
                          <td class="text-center">{{ $producto['variedad'] }}</td>
                          <td class="text-center">{{ $producto['presentacion'] }}</td>
                          <td class="text-center fw-bold">{{ $producto['total'] }}</td>
                      </tr>
                  @empty
                      <tr>
                          <td colspan="4" class="text-center">No hay totales para mostrar</td>
                      </tr>
                  @endforelse
                  @if(count($totalesPorProducto))
                      <tr class="table-light">
                          <td colspan="3" class="text-end fw-bold">TOTAL GENERAL:</td>
                          <td class="text-center fw-bold">{{ array_sum(array_column($totalesPorProducto, 'total')) }}</td>
                      </tr>
                  @endif
              </tbody>
          </table>

          {{-- Bloques de Información del Transporte, Condición y Responsables (versión Bootstrap de ver_pdf) --}}
          <div class="row mt-4">
              <div class="col-md-6 mb-3">
                  <h5 class="card-title">Información del Transporte</h5>
                  <table class="table table-bordered">
                      <tbody>
                          <tr>
                              <th style="width: 40%;">Placa del Tracto</th>
                              <td>{{ $embarcacion->trans_placa ?? 'N/A' }}</td>
                          </tr>
                          <tr>
                              <th>Placa de la Caja</th>
                              <td>{{ $embarcacion->trans_placacaja ?? 'N/A' }}</td>
                          </tr>
                          <tr>
                              <th>Temperatura de la Caja (°C/°F)</th>
                              <td>{{ $embarcacion->trans_temperaturacaja ?? 'N/A' }}</td>
                          </tr>
                      </tbody>
                  </table>
              </div>

              <div class="col-md-6 mb-3">
                  <h5 class="card-title">Condición de Transporte</h5>
                  <table class="table table-bordered">
                      <tbody>
                          <tr>
                              <th style="width: 60%;">En buen estado</th>
                              <td>{{ $embarcacion->condtrans_estado ? 'Sí' : 'No' }}</td>
                          </tr>
                          <tr>
                              <th>Limpio y libre de malos olores</th>
                              <td>{{ $embarcacion->condtrans_higiene ? 'Sí' : 'No' }}</td>
                          </tr>
                          <tr>
                              <th>Sin presencia de plagas</th>
                              <td>{{ $embarcacion->condtrans_plagas ? 'Sí' : 'No' }}</td>
                          </tr>
                          <tr>
                              <th>Producto de la última carga</th>
                              <td>{{ $embarcacion->prod_ultimacarga ?? 'N/A' }}</td>
                          </tr>
                      </tbody>
                  </table>
              </div>
          </div>

          <div class="row mt-2">
              <div class="col-md-6 mb-3">
                  <h5 class="card-title">Condición de Tarimas</h5>
                  <table class="table table-bordered">
                      <tbody>
                          <tr>
                              <th style="width: 60%;">Remontado Correcto</th>
                              <td>{{ $embarcacion->condtar_desmontado ? 'Sí' : 'No' }}</td>
                          </tr>
                          <tr>
                              <th>Flejado Correcto</th>
                              <td>{{ $embarcacion->condtar_flejado ? 'Sí' : 'No' }}</td>
                          </tr>
                          <tr>
                              <th>Distribución del embarque</th>
                              <td>{{ $embarcacion->condtar_distribucion ? 'Sí' : 'No' }}</td>
                          </tr>
                      </tbody>
                  </table>
              </div>

              <div class="col-md-6 mb-3">
                  <h5 class="card-title">Información de la Carga</h5>
                  <table class="table table-bordered">
                      <tbody>
                          <tr>
                              <th style="width: 50%;">Hora de llegada</th>
                              <td>{{ $embarcacion->infcarga_hrallegada ?? 'N/A' }}</td>
                          </tr>
                          <tr>
                              <th>Hora de carga</th>
                              <td>{{ $embarcacion->infcarga_hracarga ?? 'N/A' }}</td>
                          </tr>
                          <tr>
                              <th>Hora de salida</th>
                              <td>{{ $embarcacion->infcarga_hrasalida ?? 'N/A' }}</td>
                          </tr>
                          <tr>
                              <th>N° Sello</th>
                              <td>{{ $embarcacion->infcarga_nsello ?? 'N/A' }}</td>
                          </tr>
                          <tr>
                              <th>N° Chismografo</th>
                              <td>{{ $embarcacion->infcarga_nchismografo ?? 'N/A' }}</td>
                          </tr>
                      </tbody>
                  </table>
              </div>
          </div>

          <div class="row mt-2">
              <div class="col-md-12 mb-3">
                  <h5 class="card-title">Responsables y Línea de Transporte</h5>
                  <table class="table table-bordered">
                      <tbody>
                          <tr>
                              <th style="width: 25%;">Responsable Cooler</th>
                              <td style="width: 25%;">{{ $embarcacion->usuario->name ?? 'N/A' }}</td>
                              <th style="width: 25%;">Responsable Cliente</th>
                              <td style="width: 25%;">{{ $embarcacion->nombre_responsblecliente ?? 'N/A' }} {{ $embarcacion->apellido_responsablecliente ?? '' }}</td>
                          </tr>
                          <tr>
                              <th>Responsable Chofer</th>
                              <td>{{ $embarcacion->nombre_responsblechofer ?? 'N/A' }} {{ $embarcacion->apellido_responsablechofer ?? '' }}</td>
                              <th>Línea de Transporte</th>
                              <td>{{ $embarcacion->linea_transporte ?? 'N/A' }}</td>
                          </tr>
                      </tbody>
                  </table>
              </div>
          </div>

          <div class="row mt-4">
            <div class="col-12">
              <a href="{{ route('embarcacion.mostrar') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
              </a>
            </div>
          </div>

          <!-- Sección de Firmas -->
          <div class="mt-4">
            <h5 class="card-title">
              <i class="bi bi-pen"></i> Firmas Digitales
              @if($embarcacion->firma_usuario && $embarcacion->firma_cliente && $embarcacion->firma_chofer)
                <span class="badge bg-success">Completadas</span>
              @else
                <span class="badge bg-warning">Pendientes</span>
              @endif
            </h5>
            
            @if($embarcacion->firma_usuario && $embarcacion->firma_cliente && $embarcacion->firma_chofer)
              <!-- Mostrar firmas existentes -->
              <div class="row">
                <div class="col-md-4 text-center mb-3">
                  <div class="border rounded p-3">
                    <h6>Firma Usuario</h6>
                    <img src="{{ $embarcacion->firma_usuario }}" alt="Firma Usuario" style="max-width: 100%; height: 100px; border: 1px solid #ddd;">
                    <p class="mt-2 mb-0"><strong>{{ $embarcacion->usuario->name ?? 'Usuario' }}</strong></p>
                  </div>
                </div>
                <div class="col-md-4 text-center mb-3">
                  <div class="border rounded p-3">
                    <h6>Firma Cliente</h6>
                    <img src="{{ $embarcacion->firma_cliente }}" alt="Firma Cliente" style="max-width: 100%; height: 100px; border: 1px solid #ddd;">
                    <p class="mt-2 mb-0"><strong>{{ $embarcacion->nombre_responsblecliente }} {{ $embarcacion->apellido_responsablecliente }}</strong></p>
                  </div>
                </div>
                <div class="col-md-4 text-center mb-3">
                  <div class="border rounded p-3">
                    <h6>Firma Chofer</h6>
                    <img src="{{ $embarcacion->firma_chofer }}" alt="Firma Chofer" style="max-width: 100%; height: 100px; border: 1px solid #ddd;">
                    <p class="mt-2 mb-0"><strong>{{ $embarcacion->nombre_responsblechofer }} {{ $embarcacion->apellido_responsablechofer }}</strong></p>
                  </div>
                </div>
              </div>
              
              <div class="text-center">
                <a href="{{ route('embarcacion.firmas', $embarcacion->id) }}" class="btn btn-warning">
                  <i class="bi bi-pencil"></i> Editar Firmas
                </a>
              </div>
            @else
              <!-- Botón para agregar firmas -->
              <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i> Esta embarcación aún no tiene firmas digitales.
              </div>
              <div class="text-center">
                <a href="{{ route('embarcacion.firmas', $embarcacion->id) }}" class="btn btn-primary">
                  <i class="bi bi-pen"></i> Agregar Firmas
                </a>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
