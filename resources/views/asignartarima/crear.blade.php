@extends('layouts.app')

@section('title', 'Iniciar Sesión')
@section('content')
@php use Illuminate\Support\Str; @endphp

<div class="pagetitle">
  <h1>Formulario de Recepciones</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Recepciones</li>
      <li class="breadcrumb-item"><a href="{{ route('recepcion.mostrar') }}">Ver Recepciones</a></li>
      <li class="breadcrumb-item"><a href="{{ route('tarima.mostrar') }}">Ver Tarimas</a></li>
      <li class="breadcrumb-item"><button type="button" class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#basicModal">Crear Tarima</button></li>
    </ol>
  </nav>
</div>
 <!-- Basic Modal -->
             
<div class="modal fade" id="basicModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">¿Cuantas Tarimas deseas crear?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- General Form Elements -->
            <form  action="{{ route('tarima.guardarautomatico') }}" method="POST">
            @csrf
                <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">Cantidad</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="cuantos">
                  </div>
                </div>
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Tarimas</label>
                  <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                  </div>
                </div>

              </form><!-- End General Form Elements -->

      </div>
    </div>
  </div>
</div><!-- End Basic Modal-->
<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Cargar Tarima</h5>
          @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Éxito',
                        text: @json(session('success')),
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Aquí rediriges a la vista de mostrar tarimas
                            window.location.href = "{{ route('tarima.mostrar') }}";
                        }
                    });
                });
            </script>
          @endif
          @if ($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Errores de validación',
                        html: `{!! implode('<br>', $errors->all()) !!}`,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            </script>
          @endif


          <form class="row g-3" action="{{ route('asignartarima.guardar') }}" method="POST">
            @csrf
            <div class="col-md-4">
                <label class="form-label">Tarimas</label>
                <select name="idtarima" class="form-select" required>
                    <option value="">Tarimas</option>
                    @foreach($tarimas as $tarima)
                      @php
                        $disponible = $tarima->capacidadDisponible();
                        $usado = $tarima->cantidadActual();
                      @endphp
                      <option
                        value="{{ $tarima->id }}"
                        data-disponible="{{ $disponible }}"
                        data-uso="{{ $usado }}"
                        data-capacidad="{{ $tarima->capacidad }}"
                        data-estatus="{{ strtolower($tarima->estatus ?? 'sin estatus') }}">
                        {{ $tarima->codigo }} | Disponible: {{ $disponible }}/{{ $tarima->capacidad }} | {{ Str::ucfirst(Str::lower($tarima->estatus)) }}
                      </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Disponible</label>
              <input type="text" class="form-control" name="disponible" id="disponible" readonly>
            </div>
            <div class="col-md-4">
              <label class="form-label">Uso</label>
              <input type="text" class="form-control" name="uso" id="uso" readonly>
            </div>
            <div class="col-md-4">
              <label class="form-label">Estatus</label>
              <div class="d-flex align-items-center gap-2">
                <span id="badge-estatus" class="badge bg-secondary">Sin estatus</span>
              </div>
            </div>
            
            <!-- Panel de DEBUG temporal -->
            <div class="col-12" id="debug-panel" style="display:none;">
              <div class="alert alert-info">
                <strong>🔍 DEBUG - Información de Tarima:</strong>
                <ul class="mb-0 mt-2">
                  <li>Capacidad Total: <strong id="debug-capacidad">-</strong> cajas</li>
                  <li>En Uso Actual: <strong id="debug-uso">-</strong> cajas</li>
                  <li>Disponible Real: <strong id="debug-disponible" class="text-success">-</strong> cajas</li>
                  <li>Total a Asignar: <strong id="debug-total-asignar" class="text-primary">0</strong> cajas</li>
                  <li>Resultado: <strong id="debug-resultado">-</strong></li>
                </ul>
              </div>
            </div>

            <div class="col-md-4">
                <label class="form-label">Tipo Pallet</label>
                <select name="idtipopallet[]" class="form-select" required>
                  <option value="">Pallet</option>
                    @foreach($pallets as $pallet)
                      <option value="{{ $pallet->id }}">{{ $pallet->id }}-{{ $pallet->tipopallet}}</option>
                    @endforeach
                            
                </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Total</label>
              <input type="text" class="form-control" name="total" id="input-total" readonly>
            </div>
            <div class="col-md-4">
              <label class="form-label">¿Completar Tarima?</label>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="completar_tarima" value="1" id="completarTarima">
                <label class="form-check-label" for="completarTarima">
                  Marcar como completada
                </label>
              </div>
            </div>

            <div class="col-md-12">
              <h5 class="card-title">Selecciona las recepciones.</h5>
            </div>
              <table class="table table-bordered" id="detalles-recepcion">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Folio</th>
                        <th>Comercializadora</th>
                        <th>Fruta</th>
                        <th>Variedad</th>
                        <th>Pendientes</th>
                        <th>Asignar</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($detalles as $detalle)
                    <tr>
                        <td>
                            <input type="checkbox"
                                class="detalle-checkbox"
                                name="iddetalle[]"
                                value="{{ $detalle->id }}"
                                data-fruta="{{ $detalle->fruta->nombrefruta }}"
                                data-pendientes="{{ $detalle->pendientes }}">
                        </td>

                        <td>{{ $detalle->recepcion->folio }}</td>
                        <td><img src="{{ asset($detalle->recepcion->contrato->comercializadora->imgcomercializadora ?? 'imagenes/comercializadoras/comercializadora.png' ) }}" alt="FrutiMax" width="40" height="40" class="rounded-circle me-2">
                              <span>{{ $detalle->recepcion->contrato->comercializadora->nombrecomercializadora }}</span></td>
                        <td>{{ $detalle->fruta->nombrefruta }}</td>
                        <td>{{ $detalle->variedad->tipofruta }}</td>

                        <td>
                            <span class="badge bg-info">
                                {{ $detalle->pendientes }}
                            </span>
                        </td>

                        <td>
                            <input type="number" id="cantidad_asignada[]"
                                  name="cantidad_asignada[]"
                                  class="form-control cantidad-asignada"
                                  min="0"
                                  max="{{ $detalle->pendientes }}"
                                  disabled
                                  placeholder="0">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
              </table>

                
            {{-- Botones --}}
            <div class="col-md-12 text-center">
              <button type="submit" class="btn btn-primary">GUARDAR</button>
              <button type="reset" class="btn btn-secondary">CANCELAR</button>
            </div>
          </form>

        </div>
      </div>

    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const inputTotal = document.getElementById('input-total');
    const selectTarima = document.querySelector('select[name="idtarima"]');
    let disponibleTarima = 0;

    // Cuando el usuario selecciona una tarima
    selectTarima.addEventListener('change', function () {
        let opt = this.options[this.selectedIndex];
        disponibleTarima = parseInt(opt.dataset.disponible || 0);
        let usoTarima = parseInt(opt.dataset.uso || 0);
        let capacidadTarima = parseInt(opt.dataset.capacidad || 0);
        let estatusTarima = opt.dataset.estatus || 'sin estatus';
        
        // Actualizar campos de disponible y uso
        document.getElementById('disponible').value = disponibleTarima + ' / ' + capacidadTarima;
        document.getElementById('uso').value = usoTarima + ' / ' + capacidadTarima;
        
        // Actualizar badge de estatus
        const badgeEstatus = document.getElementById('badge-estatus');
        if (badgeEstatus) {
            badgeEstatus.textContent = estatusTarima.charAt(0).toUpperCase() + estatusTarima.slice(1);
            
            // Cambiar color según estatus
            badgeEstatus.className = 'badge ';
            if (estatusTarima === 'disponible') {
                badgeEstatus.classList.add('bg-success');
            } else if (estatusTarima === 'completo') {
                badgeEstatus.classList.add('bg-danger');
            } else {
                badgeEstatus.classList.add('bg-secondary');
            }
        }
        
        calcularTotal();
    });

    // Habilitar asignación solo si el checkbox está activado
    document.querySelectorAll('.detalle-checkbox').forEach((cb, index) => {
        cb.addEventListener('change', function () {
            let input = document.querySelectorAll('.cantidad-asignada')[index];
            input.disabled = !cb.checked;
            
            if (cb.checked) {
                // Al activar, llenar con el valor de pendientes
                let pendientes = cb.dataset.pendientes || 0;
                input.value = pendientes;
            } else {
                // Al desactivar, limpiar el campo
                input.value = "";
            }
            
            calcularTotal();
        });
    });

    // Recalcular cada vez que el usuario escribe asignaciones
    document.querySelectorAll('.cantidad-asignada').forEach(input => {
        input.addEventListener('input', calcularTotal);
    });

    // Calcula el TOTAL asignado y controla frutas
    function calcularTotal() {
        let total = 0;
        let frutas = new Set();

        document.querySelectorAll('.detalle-checkbox').forEach((cb, index) => {
            if (cb.checked) {
                let asignada = parseInt(document.querySelectorAll('.cantidad-asignada')[index].value || 0);
                total += asignada;

                let fruta = cb.dataset.fruta.toLowerCase();
                frutas.add(fruta);
            }
        });

        inputTotal.value = total;
        
        // Actualizar debug si existe
        if (debugTotalAsignar) {
            debugTotalAsignar.textContent = total;
            
            if (disponibleTarima > 0) {
                if (total > disponibleTarima) {
                    debugResultado.textContent = '❌ EXCEDE (' + (total - disponibleTarima) + ' cajas de más)';
                    debugResultado.className = 'text-danger';
                } else {
                    debugResultado.textContent = '✅ OK (quedarían ' + (disponibleTarima - total) + ' cajas disponibles)';
                    debugResultado.className = 'text-success';
                }
            }
        }

        // Validación capacidad de tarima
        if (disponibleTarima > 0 && total > disponibleTarima) {
            alert("La cantidad supera la capacidad disponible de la tarima.");
            return;
        }

        // >>> Límites por fruta <<<
        if (frutas.size === 1) {
            if (frutas.has("arándano") && total > 120) {
                alert("Límite máximo: 120 cajas para arándanos.");
            }

            if (!frutas.has("arándano") && total > 240) {
                alert("Límite máximo: 240 cajas para esta fruta.");
            }
        }
    }

});
</script>

@endpush



