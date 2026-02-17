@extends('layouts.app')

@section('title', 'Formulario de Cruce de Andén')

@section('content')
<div class="pagetitle">
  <h1>Cruce de Andén</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Cruce de Andén</li>
      <li class="breadcrumb-item"><a href="{{ route('cruce_anden.mostrar') }}">Volver a Cruce de Andén</a></li>
    </ol>
  </nav>
</div>
 {{-- ALERTA DE YA EDITADO (SweetAlert) --}}
            @if($yaEditado)
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: '¡Atención!',
                        text: 'Este Cruce de Ánden ya fue completado anteriormente. No se puede editar nuevamente.',
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
                             window.location.href = "{{ route('cruce_anden.mostrar') }}";
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
                            // Redirigir a la edición de conservación
                            window.location.href = "{{ route('conservacion.editar', $cruceAnden->idtarima) }}";
                        }
                    });
                });
            </script>
          @endif
          {{-- MENSAJE DE ERROR DESDE EL CONTROLADOR --}}
          @if(session('error'))
            <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Error',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
            </script>
          @endif
          {{-- ERRORES DE VALIDACIÓN --}}
          @if ($errors->any())
            <script>
            document.addEventListener('DOMContentLoaded', function () {
                let errores = `
                    @foreach ($errors->all() as $error)
                        - {{ $error }}\n
                    @endforeach
                `;

                Swal.fire({
                    title: 'Errores en el formulario',
                    text: errores,
                    icon: 'error',
                    confirmButtonText: 'Corregir'
                });
            });
            </script>
          @endif


<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">  
          <form class="row g-3" action="{{ route('cruce_anden.updatedetalle') }}" method="POST">
            @csrf
            <h5 class="card-title">Información de Cruce de Andén</h5>
            <table class="table table-bordered" id="tabla-cruce-anden">
              <thead>
                <tr>
                  <th colspan="4" class="text-center">Detalle</th>
                  <th colspan="2" class="text-center">Entrada</th>
                  <th colspan="2" class="text-center">Salida</th>
                  <th rowspan="2" class="text-center align-middle">Total</th>
                </tr>
                <tr>
                  <th class="text-center">Fruta</th>
                  <th class="text-center">Presentación</th>
                  <th class="text-center">Variedad</th>
                  <th class="text-center">Cámara</th>
                  <th class="text-center">Hora</th>
                  <th class="text-center">Temp.</th>
                  <th class="text-center">Hora</th>
                  <th class="text-center">Temp.</th>
                </tr>
              </thead>
              <tbody>
                @forelse($cruceAnden->tarima->tarimaDetarec as $index => $tarimaDetalle)
                  @php
                    $detalle = $tarimaDetalle->detalle;
                    $detalleCA = $cruceAnden->detallesCruceAnden->where('iddetalle', $detalle->id)->first();
                  @endphp
                  <tr>
                    <td>{{ $detalle->fruta->nombrefruta ?? 'N/A' }}</td>
                    <td>{{ $detalle->presentacion->nombrepresentacion ?? 'N/A' }}</td>
                    <td>{{ $detalle->variedad->tipofruta ?? 'N/A' }}</td>
                    <td>{{ $cruceAnden->camara->codigo ?? 'N/A' }}</td>
                    <td>
                      <input type="datetime-local" class="form-control" name="hora_entrada[]" value="{{ $detalleCA->hora_entrada ?? '' }}" readonly required>
                      <input type="hidden" name="idcruce_anden[]" value="{{ $cruceAnden->id }}">
                      <input type="hidden" name="iddetalle[]" value="{{ $detalle->id }}">
                      <input type="hidden" name="iddetalletarima[]" value="{{ $tarimaDetalle->id }}">
                    </td>
                    <td>
                      <input type="text"  class="form-control" name="temperatura_entrada[]" value="{{ $detalleCA->temperatura_entrada ?? '' }}" readonly required>
                    </td>
                    <td>
                      <input type="datetime-local" class="form-control" name="hora_salida[]" value="{{ $detalleCA->hora_salida ?? '' }}" {{ $yaEditado ? 'readonly' : '' }}>
                    </td>
                    <td>
                      <input type="text"  class="form-control" name="temperatura_salida[]" value="{{ $detalleCA->temperatura_salida ?? '0' }}" {{ $yaEditado ? 'readonly' : '' }}>
                    </td>
                   <td class="text-center">
                      @php
                          $textoTiempo = ' ';
                          if ($detalleCA && $detalleCA->tiempototal) {
                              $mins = $detalleCA->tiempototal;
                              $h = intdiv($mins, 60);
                              $m = $mins % 60;
                              $textoTiempo = $h . 'h ' . $m . 'm';
                          }
                      @endphp
                      <input type="text" name="tiempototal[]" class="form-control" readonly value="{{ $textoTiempo }}">
                  </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="9" class="text-center">No hay detalles disponibles</td>
                  </tr>
                @endforelse
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
        const tabla = document.querySelector("#tabla-cruce-anden tbody");

        if (!tabla) return;

        tabla.addEventListener("change", function (e) {
            if (e.target.name === "hora_salida[]") {
                const fila = e.target.closest("tr");
                const horaEntrada = fila.querySelector('input[name="hora_entrada[]"]').value;
                const horaSalida  = fila.querySelector('input[name="hora_salida[]"]').value;

                if (horaEntrada && horaSalida) {
                    const entrada = new Date(horaEntrada);
                    const salida  = new Date(horaSalida);

                    const diffMs = salida - entrada;

                    if (diffMs < 0) {
                        alert('La hora de salida debe ser posterior a la hora de entrada');
                        fila.querySelector('input[name="hora_salida[]"]').value = '';
                        return;
                    }

                    const diffHrs  = Math.floor(diffMs / (1000 * 60 * 60));
                    const diffMins = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
                    const inputTiempo = fila.querySelector('input[name="tiempototal[]"]');
                    inputTiempo.value = `${diffHrs}h ${diffMins}m`;
                }
            }
        });
    });
    </script>
</section>
@endsection
