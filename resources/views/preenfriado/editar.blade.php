@extends('layouts.app')

@section('title', 'Formulario de Pre-Enfriado')

@section('content')
<div class="pagetitle">
  <h1>Horario de Salida de Pre-Enfriado </h1>
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
         {{-- ALERTA DE YA EDITADO (SweetAlert) --}}
            @if($yaEditado)
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: '¡Atención!',
                        text: 'Este Pre-Enfriado ya fue completado anteriormente. No se puede editar nuevamente.',
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
                             window.location.href = "{{ route('enfrio.mostrar') }}";
                         }
                    });
                });
            </script>
            @endif

          {{-- MENSAJE DE ÉXITO DESDE EL CONTROLADOR (SweetAlert) --}}
         @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: '¡Éxito!',
                        text: '{{ session('success') }}',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                         if (result.isConfirmed) {
                             window.location.href = "{{ route('enfrio.mostrar') }}";
                         }
                    });
                });
            </script>
          @endif
          <form class="row g-3" action="{{ route('enfrio.updatedetalle') }}" method="POST">
            @csrf
            <h5 class="card-title">Información de Pre-Enfriado</h5>
            
            <table class="table table-bordered" id="tabla-espreenfrio">
              <thead>
                <tr>
                  <th colspan="5" class="text-center">Detalle</th>
                  <th colspan="2" class="text-center">Entrada</th>
                  <th colspan="2" class="text-center">Salida</th>
                  <th rowspan="1" class="text-center align-middle">Total</th>
                </tr>
                <tr>
                  <th class="text-center">Recepción</th>
                  <th class="text-center">Fruta</th>
                  <th class="text-center">Presentación</th>
                  <th class="text-center">Variedad</th>
                  <th class="text-center">Cámara</th>
                  <th class="text-center">Hora</th>
                  <th class="text-center">Temp.</th>
                  <th class="text-center">Hora</th>
                  <th class="text-center">Temp.</th>
                  <th class="text-center"></th>
                </tr>
              </thead>

              <tbody id="detalles-body">
                @forelse($preenfriado->tarima->tarimaDetarec as $tarimaDet)
                  @php
                      $detalle = $tarimaDet->detalle;
                      $recepcion = $detalle->recepcion ?? null;
                      $detallePre = $preenfriado->detallesPreenfriado
                          ->where('iddetalle', $tarimaDet->iddetalle)
                          ->first();
                  @endphp
                  <tr>
                    <td>{{ $recepcion->folio }}</td>
                    <td>
                    <input type="hidden" class="form-control" name="idpreenfriado[]" value= "{{ $preenfriado->id ?? 'N/A' }}">
                    <input type="hidden" name="iddetalle[]" class="form-control" value=" {{ $tarimaDet->detalle->id ?? 'No asignada' }} " required>
                    <input type="text" name="fruta[]" class="form-control" value="{{ $tarimaDet->detalle->fruta->nombrefruta ?? 'No asignada' }}" required></td>
                    <td><input type="text" name="presentacion[]" class="form-control" value="{{ $tarimaDet->detalle->presentacion->nombrepresentacion ?? 'No asignada' }}" required></td>
                    <td><input type="text" name="variedad[]" class="form-control" value="{{ $tarimaDet->detalle->variedad->tipofruta ?? 'No asignada' }}" required></td>
                    <td>
                      <input type="text" class="form-control" name="camara" value= "{{$preenfriado->camara->codigo ?? 'No asignada' }}">
                    </td>
                    <td>
                      <input type="datetime-local" name="hora_entrada[]" class="form-control hora-entrada" 
                             value="{{ $detallePre && $detallePre->hora_entrada ? \Carbon\Carbon::parse($detallePre->hora_entrada)->format('Y-m-d\TH:i') : '' }}" 
                             readonly>
                    </td>
                    <td>
                      <input type="number" step="0.1" name="temperatura_entrada[]" 
                             value="{{ $detallePre->temperatura_entrada ?? '' }}" 
                             class="form-control" readonly>
                    </td>
                    <td>
                      <input type="datetime-local" name="hora_salida[]" class="form-control hora-salida" 
                             value="{{ $detallePre && $detallePre->hora_salida ? \Carbon\Carbon::parse($detallePre->hora_salida)->format('Y-m-d\TH:i') : '' }}" 
                             {{ $yaEditado ? 'readonly' : 'required' }}>
                    </td>
                    <td>
                      <input type="number" step="0.1" name="temperatura_salida[]" class="form-control" 
                             value="{{ $detallePre->temperatura_salida ?? 0 }}" 
                             {{ $yaEditado ? 'readonly' : '' }}>
                    </td>
                    <td>
                      <input type="text" name="tiempototal[]" class="form-control tiempo-total" 
                             value="{{ $detallePre->tiempototal ?? '' }}" readonly>
                    </td>
                  </tr>
                   @empty
                      <tr>
                          <td colspan="10" class="text-center">No hay detalles de tarima asociados</td>
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
        const tabla = document.getElementById("detalles-body");

        // Función para calcular tiempo transcurrido
        function calcularTiempo(fila) {
            const horaEntradaInput = fila.querySelector('.hora-entrada');
            const horaSalidaInput = fila.querySelector('.hora-salida');
            const tiempoTotalInput = fila.querySelector('.tiempo-total');

            const horaEntrada = horaEntradaInput.value;
            const horaSalida = horaSalidaInput.value;

            if (horaEntrada && horaSalida) {
                // Convertir datetime-local a objetos Date
                const entrada = new Date(horaEntrada);
                const salida = new Date(horaSalida);

                // Calcular diferencia en milisegundos
                const diffMs = salida - entrada;

                if (diffMs < 0) {
                    tiempoTotalInput.value = 'Error: Salida antes de entrada';
                    return;
                }

                // Convertir a horas y minutos
                const diffHrs = Math.floor(diffMs / (1000 * 60 * 60));
                const diffMins = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));

                // Mostrar en formato "Xh Ym"
                tiempoTotalInput.value = `${diffHrs}h ${diffMins}m`;
            }
        }

        // Calcular tiempo al cambiar hora_salida
        tabla.addEventListener("change", function (e) {
            if (e.target.classList.contains('hora-salida')) {
                const fila = e.target.closest("tr");
                calcularTiempo(fila);
            }
        });

        // Calcular tiempos existentes al cargar la página
        document.querySelectorAll('#detalles-body tr').forEach(fila => {
            calcularTiempo(fila);
        });
    });
  </script>

</section>
@endsection
