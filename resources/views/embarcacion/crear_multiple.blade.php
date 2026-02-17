@extends('layouts.app')

@section('title', 'Crear Embarcación Múltiple')

@section('content')
<div class="pagetitle">
  <h1>Crear Embarcación con Múltiples Conservaciones</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('conservacion.mostrar') }}">Conservación</a></li>
      <li class="breadcrumb-item active">Crear Embarcación Múltiple</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Conservaciones Seleccionadas ({{ $conservaciones->count() }})</h5>

         @if ($errors->any())
            @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const errors = {!! json_encode($errors->all()) !!};
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de validación',
                        html: errors.join('<br>'),
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#dc3545'
                    });
                });
            </script>
            @endpush
        @endif

        @if (session('error'))
            @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: '{{ session('error') }}',
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#dc3545'
                    });
                });
            </script>
            @endpush
        @endif

        @if (session('success'))
            @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: '{{ session('success') }}',
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#198754'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('embarcacion.mostrar') }}";
                        }
                    });
                });
            </script>
            @endpush
        @endif

          <form class="row g-3" action="{{ route('embarcacion.guardar.multiple') }}" method="POST">
            @csrf
            <input type="hidden" name="conservaciones_ids" value="{{ $conservaciones->pluck('id')->implode(',') }}">
            <!-- 1.- Responsables de Embarque -->
            <h5 class="card-title">1.- Responsables de Embarque</h5>
            <div class="row mb-3">
              <div class="col-md-4">
                <label class="form-label">Responsable Bonum Coolers</label>
                <input type="text" class="form-control" value="{{ auth()->user()->name }} {{ auth()->user()->apellidos }}" readonly>
                <input type="hidden" name="id_usuario" value="{{ auth()->user()->id }}">
              </div>
              <div class="col-md-4">
                <label class="form-label">Nombre Cliente</label>
                <input type="text" class="form-control" name="nombre_responsblecliente" required>
              </div>
              <div class="col-md-4">
                <label class="form-label">Apellido Cliente</label>
                <input type="text" class="form-control" name="apellido_responsablecliente" required>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">Nombre Chofer</label>
                <input type="text" class="form-control" name="nombre_responsblechofer" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Apellido Chofer</label>
                <input type="text" class="form-control" name="apellido_responsablechofer" required>
              </div>
            </div>

            <!-- Firmas (opcionales por ahora) -->
            <input type="hidden" name="firma_usuario" value="">
            <input type="hidden" name="firma_cliente" value="">
            <input type="hidden" name="firma_chofer" value="">
             <div class="col-lg-6">
                            <h5 class="card-title">2.- Información de Transporte</h5>
                        {{-- Placa del Tractor --}}
                        <div class="col-md-12">
                            <label class="form-label">Placa del Tracto</label>
                            <input type="text" class="form-control @error('trans_placa') is-invalid @enderror" name="trans_placa" value="{{ old('trans_placa') }}">
                            @error('trans_placa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Placa de la caja --}}
                        <div class="col-md-12">
                            <label class="form-label">Placa de la Caja</label>
                            <input type="text" class="form-control @error('trans_placacaja') is-invalid @enderror" name="trans_placacaja" value="{{ old('revision') }}">
                            @error('trans_placacaja')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Temperatura de la Caja (°C/°F) --}}
                        <div class="col-md-12">
                            <label class="form-label">Temperatura de la Caja (°C/°F)</label>
                            <input type="text" class="form-control @error('trans_temperaturacaja') is-invalid @enderror" name="trans_temperaturacaja" value="{{ old('trans_temperaturacaja') }}">
                            @error('trans_temperaturacaja')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Línea de Transporte --}}
                        <div class="col-md-12">
                            <label class="form-label">Línea de Transporte</label>
                            <input type="text" class="form-control @error('linea_transporte') is-invalid @enderror" name="linea_transporte" value="{{ old('linea_transporte') }}">
                            @error('linea_transporte')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <h5 class="card-title">3.- Condición de Transporte</h5>

                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="radio" 
                                    class="form-check-input" 
                                    id="condtrans_estado" 
                                    name="condtrans_estado" 
                                    value="1" 
                                    {{ old('condtrans_estado') ? 'checked' : '' }}>
                                <label class="form-check-label" for="condtrans_estado">En buen estado</label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="radio" 
                                    class="form-check-input" 
                                    id="condtrans_higiene" 
                                    name="condtrans_higiene" 
                                    value="1" 
                                    {{ old('condtrans_higiene') ? 'checked' : '' }}>
                                <label class="form-check-label" for="condtrans_higiene">Limpio y libre de malos olores</label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="radio" 
                                    class="form-check-input" 
                                    id="condtrans_plagas" 
                                    name="condtrans_plagas" 
                                    value="1" 
                                    {{ old('condtrans_plagas') ? 'checked' : '' }}>
                                <label class="form-check-label" for="condtrans_plagas">Sin Presencia de plagas</label>
                            </div>
                        </div>
                        {{-- Producta de la última carga --}}
                        <div class="col-md-12">
                            <label class="form-label">Producto de la última carga</label>
                            <input type="text" class="form-control @error('prod_ultimacarga') is-invalid @enderror" name="prod_ultimacarga"  >
                            @error('prod_ultimacarga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h5 class="card-title">4.- Condición de Tarima</h5>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="radio" 
                                    class="form-check-input" 
                                    id="condtar_desmontado" 
                                    name="condtar_desmontado" 
                                    value="1" 
                                    {{ old('condtar_desmontado') ? 'checked' : '' }}>
                                <label class="form-check-label" for="condtar_desmontado">Remontado Correcto</label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="radio" 
                                    class="form-check-input" 
                                    id="condtar_flejado" 
                                    name="condtar_flejado" 
                                    value="1" 
                                    {{ old('condtar_flejado') ? 'checked' : '' }}>
                                <label class="form-check-label" for="condtar_flejado">Flejado Correcto</label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="radio" 
                                    class="form-check-input" 
                                    id="condtar_distribucion" 
                                    name="condtar_distribucion" 
                                    value="1" 
                                    {{ old('condtar_distribucion') ? 'checked' : '' }}>
                                <label class="form-check-label" for="condtar_distribucion">Distribución del embarque</label>
                            </div>
                        </div>
                        <h5 class="card-title">5.- Informacion de Carga </h5>
                        {{-- Área --}}
                            <div class="col-md-12">
                                <label class="form-label">Hora de llegada</label>
                                <input type="datetime-local" 
                                    class="form-control @error('infcarga_hrallegada') is-invalid @enderror" 
                                    name="infcarga_hrallegada" 
                                    value="{{ old('infcarga_hrallegada', $horaLlegadaSugerida ?? '') }}">
                                @error('infcarga_hrallegada')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Hora de carga</label>
                                <input type="datetime-local" 
                                    class="form-control @error('infcarga_hracarga') is-invalid @enderror" 
                                    name="infcarga_hracarga" 
                                    value="{{ old('infcarga_hracarga') }}">
                                @error('infcarga_hracarga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        

                        {{-- Hora de salida --}}
                        <div class="col-md-12">
                            <label class="form-label">Hora de salida</label>
                            <input type="datetime-local" class="form-control @error('infcarga_hrasalida') is-invalid @enderror" name="infcarga_hrasalida" value="{{ old('infcarga_hrasalida') }}">
                            @error('infcarga_hrasalida')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            {{-- N° Sello --}}
                            <div class="col-md-6">
                                <label class="form-label">N° Sello</label>
                                <input type="text" class="form-control @error('infcarga_nsello') is-invalid @enderror" name="infcarga_nsello" value="{{ old('infcarga_nsello') }}">
                                @error('infcarga_nsello')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- N° Chismografo --}}
                            <div class="col-md-6">
                                <label class="form-label">N° Chismografo</label>
                                <input type="text" class="form-control @error('infcarga_nchismografo') is-invalid @enderror" name="infcarga_nchismografo" value="{{ old('infcarga_nchismografo') }}">
                                @error('infcarga_nchismografo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
            <!-- Tabla de conservaciones seleccionadas -->
             <h5 class="card-title">6. Información de Embarque</h5>
            <div class="col-lg-8">
                        <table class="table table-bordered" id="tabla-espreenfrio">
                            <thead>
                                <tr>
                                <th colspan="6" class="text-center">Detalle</th>
                                </tr>
                                <tr>
                                <th class="text-center">#Tarima</th>
                                <th class="text-center">Temperatura</th>
                                <th class="text-center">Fruta</th>
                                <th class="text-center">Presentación</th>
                                <th class="text-center">Variedad</th>
                                <th class="text-center">Cant.</th>
                            </thead>

                            <tbody id="detalles-body">
                                <!-- Reemplaza el bucle actual por este: -->
                              @foreach($conservaciones as $conservacion)
                                  @foreach($conservacion->detallesConservacion as $data)
                                  <tr>
                                      <td>
                                          <input type="hidden" name="idconservacion[]" value="{{ $conservacion->id }}">
                                          <input type="hidden" name="iddetalletarima[]" value="{{ $data->tarimaDetarec->id ?? '' }}">
                                          <input type="text" class="form-control" value="{{ $conservacion->tarima->codigo ?? 'N/A' }}" readonly>
                                      </td>
                                      <td>
                                          <input type="number" step="0.1" name="temperatura_entrada[]" 
                                          value="{{ $data->temperatura_entrada ?? '' }}" class="form-control" readonly>
                                      </td>
                                      <td>
                                          <input type="text" name="fruta[]" class="form-control" 
                                          value="{{ $data->detalleRecepcion->fruta->nombrefruta ?? 'No asignada' }}" readonly>
                                      </td>
                                      <td>
                                          <input type="text" name="presentacion[]" class="form-control" 
                                          value="{{ $data->detalleRecepcion->presentacion->nombrepresentacion ?? 'No asignada' }}" readonly>
                                      </td>
                                      <td>
                                          <input type="text" name="variedad[]" class="form-control" 
                                              value="{{ $data->detalleRecepcion->variedad->tipofruta ?? 'No asignada' }}" readonly>
                                      </td>
                                      <td>
                                          <input type="text" class="form-control" name="cantidad[]" 
                                              value="{{ $data->tarimaDetarec->cantidadcarga ?? 'No asignada' }}" readonly>
                                      </td>
                                  </tr>
                                  @endforeach
                              @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-4">
                        <h5 class="card-title">7.- Totales</h5>
                       @php
                          // Inicializar array para los totales
                          $totales = [];
                          
                          // Recorrer todas las conservaciones y sus detalles
                          foreach($conservaciones as $conservacion) {
                              foreach($conservacion->detallesConservacion as $data) {
                                  $fruta = $data->detalleRecepcion->fruta->nombrefruta ?? 'No asignada';
                                  $variedad = $data->detalleRecepcion->variedad->tipofruta ?? 'No asignada';
                                  $presentacion = $data->detalleRecepcion->presentacion->nombrepresentacion ?? 'No asignada';
                                  $cantidad = $data->tarimaDetarec->cantidadcarga ?? 0;
                                  
                                  $key = $fruta . '|' . $variedad . '|' . $presentacion;
                                  
                                  if (!isset($totales[$key])) {
                                      $totales[$key] = [
                                          'fruta' => $fruta,
                                          'variedad' => $variedad,
                                          'presentacion' => $presentacion,
                                          'cantidad' => 0
                                      ];
                                  }
                                  $totales[$key]['cantidad'] += $cantidad;
                              }
                          }
                          
                          // Ordenar por cantidad (de mayor a menor)
                          usort($totales, function($a, $b) {
                              return $b['cantidad'] - $a['cantidad'];
                          });
                          
                          // Limitar a 6 elementos
                          $totales = array_values($totales); // Asegurarse de que sea un array indexado
                          $totales = array_slice($totales, 0, 6);
                      @endphp

                      @for($i = 0; $i < 6; $i++)
                          <div class="col-md-12 mb-3">
                              <label class="form-label">Total {{ $i + 1 }}</label>
                              @if(isset($totales[$i]))
                                  <input type="text" 
                                      class="form-control @error('total' . ($i + 1)) is-invalid @enderror" 
                                      name="total{{ $i + 1 }}" 
                                      value="{{ $totales[$i]['fruta'] }} - {{ $totales[$i]['variedad'] }} - {{ $totales[$i]['presentacion'] }}: {{ $totales[$i]['cantidad'] }}" 
                                      readonly>
                              @else
                                  <input type="text" 
                                      class="form-control @error('total' . ($i + 1)) is-invalid @enderror" 
                                      name="total{{ $i + 1 }}" 
                                      value="0" 
                                      readonly>
                              @endif
                              @error('total' . ($i + 1))
                                  <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                          </div>
                      @endfor
                    </div>

            <!-- Botones -->
            <div class="text-center mt-4">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-truck me-1"></i>Crear Embarcación
              </button>
              <a href="{{ route('conservacion.mostrar') }}" class="btn btn-secondary">
                <i class="bi bi-x-circle me-1"></i>Cancelar
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
