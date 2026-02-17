@extends('layouts.app')

@section('title', 'Formulario de Pre-Enfriado')

@section('content')
<div class="pagetitle">
  <h1>Horario de Conservación </h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Recepción</li>
      <li class="breadcrumb-item"><a href="{{ route('enfrio.mostrar') }}">Volver a Recepciones</a></li>
    </ol>
  </nav>
</div>
 {{-- ALERTA DE YA EDITADO (SweetAlert) --}}
            @if($yaEditado)
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: '¡Atención!',
                        text: 'Este Conservación ya fue completado anteriormente. No se puede editar nuevamente.',
                        icon: 'warning',
                        confirmButtonText: 'Entendido',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                        showCloseButton: false,
                        showCancelButton: false,
                        showConfirmButton: true,
                        focusConfirm: true
                    }).then((result) => {
                        // Opcional: Redirigir a otra página después de aceptar
                         if (result.isConfirmed) {
                             window.location.href = "{{ route('conservacion.mostrar') }}";
                         }
                    });
                });
            </script>
            @endif

          {{-- MENSAJE DE ÉXITO DESDE EL CONTROLADOR (SweetAlert) --}}
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
                            // Recargar la misma página
                             window.location.href = window.location.href = "{{ route('conservacion.editar', $identificador->idtarima) }}";
                        }
                    });
                });
            </script>
          @endif
<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          @if($yaEditado)
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
              <i class="bi bi-exclamation-triangle me-1"></i>
              <strong>¡Atención!</strong> Esta Conservación ya fue completada anteriormente. No se puede editar nuevamente.
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif
          
          <form class="row g-3" action="{{ route('conservacion.guardardetalle') }}" method="POST">
            @csrf
            <h5 class="card-title">Información de Conservación</h5>
            <table class="table table-bordered" id="tabla-espreenfrio">
              <thead>
                <tr>
                  <th colspan="5" class="text-center">Detalle</th>
                  <th colspan="2" class="text-center">Entrada</th>
                  <th colspan="1" class="text-center">Salida</th>
                  <th rowspan="1" class="text-center">Total</th>
                </tr>
                <tr>
                  <th>Recepción</th>
                  <th class="text-center">Fruta</th>
                  <th class="text-center">Presentación</th>
                  <th class="text-center">Variedad</th>
                  <th class="text-center">Cámara</th>
                  <th class="text-center">Hora</th>
                  <th class="text-center">Temp.</th>
                  <th class="text-center">Hora</th>
                  <th class="text-center"></th>
              </thead>

              <tbody id="detalles-body">
                @foreach($conservaciones->detallesPreenfriado as $data)
                 @php
                      // buscar si ya existe un detalle_conservacion para este iddetalle
                      $detalleConservacion = $detallesConservacion[$data->detalleRecepcion->id] ?? null;
                      // buscar si existe un detalle de cruce de andén para este iddetalle
                      $detalleCruce = $detallesCruce[$data->detalleRecepcion->id] ?? null;
                       
                        if ($data->hora_entrada && $data->hora_salida) {
                            $entrada = \Carbon\Carbon::parse($data->hora_entrada);
                            $salida  = \Carbon\Carbon::parse($data->hora_salida);

                            if ($salida->lessThan($entrada)) {
                                $salida->addDay();
                            }

                            // SIEMPRE POSITIVO
                            $minTotales = abs($salida->diffInMinutes($entrada, false));

                            $horas = intdiv($minTotales, 60);
                            $minRestantes = $minTotales % 60;
                            $textoTiempo = $horas . 'h ' . $minRestantes . 'm';
                        } else {
                            $textoTiempo = 'Pendiente';
                        }
                
                  @endphp
                <tr>
                  <td>{{ $data->detalleRecepcion->recepcion->folio }}</td>
                  <td>
                    <input type="hidden" class="form-control" name="idconservacion[]" value="{{ $identificador->id ?? 'N/A' }}">
                    <input type="hidden" name="iddetalle[]" class="form-control" value="{{ $data->detalleRecepcion->id ?? 'No asignada' }}" readonly>
                    <input type="hidden" name="iddetalletarima[]" value="{{ $data->iddetalletarima ?? '' }}">
                    <input type="text" name="fruta[]" class="form-control" value="{{ $data->detalleRecepcion->fruta->nombrefruta ?? 'No asignada' }}" readonly>
                  </td>
                  <td>
                    <input type="text" name="presentacion[]" class="form-control" value="{{ $data->detalleRecepcion->presentacion->nombrepresentacion ?? 'No asignada' }}" readonly>
                  </td>
                  <td>
                    <input type="text" name="variedad[]" class="form-control" value="{{ $data->detalleRecepcion->variedad->tipofruta ?? 'No asignada' }}" readonly>
                  </td>
                  <td>
                    <input type="text" class="form-control" name="camara" value="{{ $conservaciones->camara->codigo ?? 'No asignada' }}" readonly>
                  </td>
                  <td>
                      @php
                          // Prioridad para prellenar hora_entrada de conservación:
                          // 1) Si ya existe detalleConservacion con hora_entrada, usarla.
                          // 2) Si viene de cruce de andén y hay hora_salida de cruce, usarla.
                          // 3) En último caso, usar hora_salida de preenfriado (como antes).

                          $valorHoraEntrada = '';

                          if ($detalleConservacion && $detalleConservacion->hora_entrada) {
                              $valorHoraEntrada = \Carbon\Carbon::parse($detalleConservacion->hora_entrada)->format('Y-m-d\\TH:i');
                          } elseif ($detalleCruce && $detalleCruce->hora_salida) {
                              $valorHoraEntrada = \Carbon\Carbon::parse($detalleCruce->hora_salida)->format('Y-m-d\\TH:i');
                          } elseif ($data->hora_salida) {
                              $valorHoraEntrada = \Carbon\Carbon::parse($data->hora_salida)->format('Y-m-d\\TH:i');
                          }
                      @endphp
                      <input type="datetime-local" name="hora_entrada[]" class="form-control" value="{{ $valorHoraEntrada }}" readonly>
                  </td>
                  <td>
                    <input type="number" step="0.1" name="temperatura_entrada[]" value="{{ $data->temperatura_salida ?? '' }}" class="form-control" readonly>
                  </td>
                  <td>
                    {{-- Hora de salida: aquí mostramos lo que ya se guardó si existe --}}
                        <input type="datetime-local" name="hora_salida[]" class="form-control"value="{{ $detalleConservacion && $detalleConservacion->hora_salida
                                        ? \Carbon\Carbon::parse($detalleConservacion->hora_salida)->format('Y-m-d\TH:i')
                                        : '' }}"
                            {{ $yaEditado ? 'readonly' : '' }}>
                  </td>
                  
                  <td>
                    <input type="text" name="tiempototal[]" class="form-control" readonly value="{{ $textoTiempo }}">
                  </td>
                </tr>
                @endforeach
              </tbody>


            </table>

            

            <div class="mb-3">
              <button type="submit" class="btn btn-primary" {{ $yaEditado ? 'disabled' : '' }}>
                {{ $yaEditado ? 'Ya Completado' : 'Guardar' }}
              </button>
              @if(!$yaEditado)
                <a href="{{ route('enfrio.mostrar') }}" class="btn btn-secondary">Cancelar</a>
              @else
                <a href="{{ route('enfrio.mostrar') }}" class="btn btn-secondary">Volver</a>
              @endif
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
        const tabla = document.getElementById("detalles-body");

        tabla.addEventListener("change", function (e) {
            if (e.target.name === "hora_salida[]") {
                const fila = e.target.closest("tr");

                const horaEntrada = fila.querySelector('input[name="hora_entrada[]"]').value;
                const horaSalida = fila.querySelector('input[name="hora_salida[]"]').value;

                if (horaEntrada && horaSalida) {
                    // Crear objetos Date con las fechas/horas completas del formulario
                    const entrada = new Date(horaEntrada);
                    const salida = new Date(horaSalida);

                    // Calcular la diferencia en milisegundos
                    const diffMs = salida - entrada;

                    // Validar que la salida sea posterior a la entrada
                    if (diffMs < 0) {
                        alert('La hora de salida debe ser posterior a la hora de entrada');
                        fila.querySelector('input[name="hora_salida[]"]').value = '';
                        fila.querySelector('input[name="tiempototal[]"]').value = '';
                        return;
                    }

                    // Convertir a horas y minutos
                    const diffHrs = Math.floor(diffMs / (1000 * 60 * 60));
                    const diffMins = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));

                    // Mostrar en formato "Xh Ym"
                    fila.querySelector('input[name="tiempototal[]"]').value =
                        `${diffHrs}h ${diffMins}m`;
                }
            }
        });
    });

  </script>

</section>
@endsection
