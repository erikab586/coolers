@extends('layouts.app')

@section('title', 'Formulario de Pre-Enfriado')

@section('content')
<div class="pagetitle">
  <h1>Detalle de Conservación </h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Recepción</li>
      <li class="breadcrumb-item"><a href="{{ route('enfrio.mostrar') }}">Volver a Recepciones</a></li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <form class="row g-3" action="#" method="POST">
            @csrf
            <h5 class="card-title">Información de Conservación</h5>
            <table class="table table-bordered">
                <tr>
                    <!-- Logo -->
                    <td rowspan="3" class="logo-cell"
                        style="width: 90px; text-align:center; vertical-align:middle; padding:4px;">
                        <img src="{{ asset('assets/img/logo.jpg') }}"
                            alt="Logo"
                            style="max-width: 80px; max-height: 80px;">
                    </td>

                    <!-- Título -->
                      <td colspan="4" style="text-align: center; font-weight: bold; font-size: 20px;">
                        Conservación
                    </td>
                </tr>

                <!-- Fila de etiquetas -->
                <tr style="text-align: center; font-weight: bold;">
                    <td>Área</td>
                    <td>Clave</td>
                    <td>Emisión</td>
                    <td>Revisión</td>
                </tr>

                <!-- Fila de valores -->
                <tr class="header-value">
                    <td>Conservación</td>
                    <td>F-BCM-PRO-04</td>
                    <td>{{ $conservaciones->tarima->tarimaDetarec->first()->detalle->recepcion->fechaemision ? \Carbon\Carbon::parse($conservaciones->tarima->tarimaDetarec->first()->detalle->recepcion->fechaemision)->format('Y-m-d') : '—' }}</td>
                    <td>{{ $conservaciones->tarima->tarimaDetarec->first()->detalle->recepcion->revision ?? '—' }}</td>
                </tr>
                            
                <!-- Fila de cliente, folio y fecha -->
                <tr>
                    <td colspan="3"style="text-align: left;">
                        Tarima: {{ $conservaciones->tarima->codigo }}
                    </td>
                    <td colspan="2"style="text-align: left;">
                        Fecha: {{ \Carbon\Carbon::now()->format('Y-m-d') }}
                    </td>
                </tr>
            </table>
            <h5 class="card-title mt-4">Detalle de Conservación</h5>
            <table class="table table-bordered" id="tabla-espreenfrio">
              <thead>
                <tr>
                  <th colspan="6" class="text-center">Detalle</th>
                  <th colspan="2" class="text-center">Entrada</th>
                  <th colspan="1" class="text-center">Salida</th>
                  <th rowspan="1" class="text-center align-middle">Total</th>
                </tr>
                <tr>
                  <th class="text-center">Recepción</th>
                  <th class="text-center">Fruta</th>
                  <th class="text-center">Presentación</th>
                  <th class="text-center">Variedad</th>
                  <th class="text-center">Cantidad</th>
                  <th class="text-center">Cámara</th>
                  <th class="text-center">Hora</th>
                  <th class="text-center">Temp.</th>
                  <th class="text-center">Hora</th>
                  <th class="text-center"></th>
                </tr>
              </thead>

              <tbody id="detalles-body">
                @foreach($conservaciones->detallesConservacion as $data)
                <tr>
                  <td>{{ $data->detalleRecepcion->recepcion->folio }}</td>
                  <td>
                    <input type="hidden" class="form-control" name="idconservacion[]" value="{{ $identificador->id ?? 'N/A' }}">
                    <input type="hidden" name="iddetalle[]" class="form-control" value="{{ $data->detalleRecepcion->id ?? 'No asignada' }}" readonly>
                    <input type="text" name="fruta[]" class="form-control" value="{{ $data->detalleRecepcion->fruta->nombrefruta ?? 'No asignada' }}" readonly>
                  </td>
                  <td>
                    <input type="text" name="presentacion[]" class="form-control" value="{{ $data->detalleRecepcion->presentacion->nombrepresentacion ?? 'No asignada' }}" readonly>
                  </td>
                  <td>
                    <input type="text" name="variedad[]" class="form-control" value="{{ $data->detalleRecepcion->variedad->tipofruta ?? 'No asignada' }}" readonly>
                  </td>
                  <td>
                    <input type="text" class="form-control" name="cantidad" value="{{ $data->tarimaDetarec->cantidadcarga ?? 'No asignada' }}" readonly>
                  </td>
                   <td>
                    <input type="text" class="form-control" name="camara" value="{{ $conservaciones->camara->codigo ?? 'No asignada' }}" readonly>
                  </td>
                  <td>
                    <input type="datetime-local" name="hora_entrada[]" class="form-control" value="{{ $data->hora_entrada ? \Carbon\Carbon::parse($data->hora_entrada)->format('Y-m-d\TH:i') : '' }}" readonly>
                  </td>
                  <td>
                    <input type="number" step="0.1" name="temperatura_entrada[]" value="{{ $data->temperatura_entrada ?? '' }}" class="form-control" readonly>
                  </td>
                  <td>
                    <input type="datetime-local" name="hora_salida[]"  value="{{ $data->hora_salida ? \Carbon\Carbon::parse($data->hora_salida)->format('Y-m-d\TH:i') : '' }}" class="form-control">
                 
                    <input type="hidden" step="0.1" name="temperatura_salida[]"  value="{{ $data->temperatura_salida ?? '' }}" class="form-control" >
                  </td>
                  <td>
                    <input type="text" name="tiempototal[]" class="form-control" readonly>
                  </td>
                </tr>
                @endforeach
              </tbody>

            </table>

          </form>

          <!-- Sección de Firmas -->
          <div class="mt-4">
            <h5 class="card-title">
              <i class="bi bi-pen"></i> Firmas Digitales
              @if($conservaciones->firma_responsable1 && $conservaciones->firma_responsable2)
                <span class="badge bg-success">Completadas</span>
              @else
                <span class="badge bg-warning">Pendientes</span>
              @endif
            </h5>
            
            @if($conservaciones->firma_responsable1 && $conservaciones->firma_responsable2)
              <!-- Mostrar firmas existentes -->
              <div class="row">
                <div class="col-md-6 text-center mb-3">
                  <div class="border rounded p-3">
                    <h6>Responsable 1</h6>
                    <img src="{{ $conservaciones->firma_responsable1 }}" alt="Firma 1" style="max-width: 100%; height: 100px; border: 1px solid #ddd;">
                    <p class="mt-2 mb-0"><strong>{{ $conservaciones->nombre_responsable1 }}</strong></p>
                  </div>
                </div>
                <div class="col-md-6 text-center mb-3">
                  <div class="border rounded p-3">
                    <h6>Responsable 2</h6>
                    <img src="{{ $conservaciones->firma_responsable2 }}" alt="Firma 2" style="max-width: 100%; height: 100px; border: 1px solid #ddd;">
                    <p class="mt-2 mb-0"><strong>{{ $conservaciones->nombre_responsable2 }}</strong></p>
                  </div>
                </div>
              </div>
              
              @if($conservaciones->nota_firmas)
              <div class="alert alert-info">
                <strong><i class="bi bi-info-circle"></i> Nota:</strong> {{ $conservaciones->nota_firmas }}
              </div>
              @endif
              
              <div class="text-center">
                <a href="{{ route('conservacion.firmas', $conservaciones->id) }}" class="btn btn-warning">
                  <i class="bi bi-pencil"></i> Editar Firmas
                </a>
              </div>
            @else
              <!-- Botón para agregar firmas -->
              <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i> Esta conservación aún no tiene firmas digitales.
              </div>
              <div class="text-center">
                <a href="{{ route('conservacion.firmas', $conservaciones->id) }}" class="btn btn-primary">
                  <i class="bi bi-pen"></i> Agregar Firmas
                </a>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
   <script>
    document.addEventListener("DOMContentLoaded", function () {
    const tabla = document.getElementById("detalles-body");

    function calcularFila(fila) {
        const horaEntrada = fila.querySelector('input[name="hora_entrada[]"]').value;
        const horaSalida  = fila.querySelector('input[name="hora_salida[]"]').value;

        if (horaEntrada && horaSalida) {
            const entrada = new Date(horaEntrada);
            const salida  = new Date(horaSalida);
            const diffMs  = salida - entrada;

            if (diffMs < 0) {
                fila.querySelector('input[name="tiempototal[]"]').value = '';
                return;
            }

            const diffHrs  = Math.floor(diffMs / (1000 * 60 * 60));
            const diffMins = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));

            fila.querySelector('input[name="tiempototal[]"]').value =
                `${diffHrs}h ${diffMins}m`;
        }
    }

    // 1) Calcular todas las filas al cargar
    tabla.querySelectorAll('tr').forEach(calcularFila);

    // 2) Mantener tu lógica actual al cambiar hora_salida
    tabla.addEventListener("change", function (e) {
        if (e.target.name === "hora_salida[]") {
            const fila = e.target.closest("tr");
            calcularFila(fila);
        }
    });
});

  </script>
</section>
@endsection
