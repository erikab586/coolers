@extends('layouts.app')

@section('title', 'Editar Embarcación')

@section('content')
<div class="pagetitle">
  <h1>Editar Embarcación </h1>
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
            <h5 class="card-title"></h5>

            @if($yaEditado)
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    <strong>¡Atención!</strong> Esta Embarcación ya fue completada anteriormente. No se puede editar nuevamente.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Por favor, corrige los errores del formulario.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <form class="row g-3" action="{{ route('embarcacion.update', $embarcacion->id) }}" method="POST">
                @csrf
                @method('PUT')
                    <div class="col-lg-12">
                            <h5 class="card-title">1.- Responsables de Embarque</h5>
                        <div class="row">
                            {{-- Responsable Bonum Coolers --}}
                            <div class="col-md-4">
                                <label class="form-label">Responsable Bonum Coolers</label>
                                <input type="text" class="form-control" value="{{ $embarcacion->usuario->name ?? 'N/A' }} {{ $embarcacion->usuario->apellidos ?? '' }}" readonly>
                                <input type="hidden" name="id_usuario" value="{{ $embarcacion->id_usuario }}">
                                <input type="hidden" class="form-control" name="firma_usuario" value="{{ $embarcacion->firma_usuario ?? 'No Aplica' }}">
                            </div>
                            {{-- Nombre Cliente --}}
                            <div class="col-md-4">
                                <label class="form-label">Nombre Cliente</label>
                                <input type="text" class="form-control @error('nombre_responsblecliente') is-invalid @enderror" name="nombre_responsblecliente" value="{{ old('nombre_responsblecliente', $embarcacion->nombre_responsblecliente) }}">
                                @error('nombre_responsblecliente')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Apellido Cliente --}}
                            <div class="col-md-4">
                                <label class="form-label">Apellido Cliente</label>
                                <input type="text" class="form-control @error('apellido_responsablecliente') is-invalid @enderror" name="apellido_responsablecliente" value="{{ old('apellido_responsablecliente', $embarcacion->apellido_responsablecliente) }}">
                                @error('apellido_responsablecliente')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <input type="hidden" class="form-control" name="firma_cliente" value="{{ $embarcacion->firma_cliente ?? 'No Aplica' }}">
                        </div>
                        <div class="row">
                            {{-- Nombre Chofer --}}
                            <div class="col-md-4">
                                <label class="form-label">Nombre Chofer</label>
                                <input type="text" class="form-control @error('nombre_responsblechofer') is-invalid @enderror" name="nombre_responsblechofer" value="{{ old('nombre_responsblechofer', $embarcacion->nombre_responsblechofer) }}">
                                @error('nombre_responsblechofer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Apellido Chofer --}}
                            <div class="col-md-4">
                                <label class="form-label">Apellido Chofer</label>
                                <input type="text" class="form-control @error('apellido_responsablechofer') is-invalid @enderror" name="apellido_responsablechofer" value="{{ old('apellido_responsablechofer', $embarcacion->apellido_responsablechofer) }}">
                                @error('apellido_responsablechofer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Línea de Transporte --}}
                            <div class="col-md-4">
                                <label class="form-label">Línea de Transporte</label>
                                <input type="text" class="form-control @error('linea_transporte') is-invalid @enderror" name="linea_transporte" value="{{ old('linea_transporte', $embarcacion->linea_transporte) }}">
                                @error('linea_transporte')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <input type="hidden" class="form-control" name="firma_chofer" value="{{ $embarcacion->firma_chofer ?? 'No Aplica' }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                            <h5 class="card-title">2.- Información de Transporte</h5>
                        {{-- Placa del Tractor --}}
                        <div class="col-md-12">
                            <label class="form-label">Placa del Tracto</label>
                            <input type="text" class="form-control @error('trans_placa') is-invalid @enderror" name="trans_placa" value="{{ old('trans_placa', $embarcacion->trans_placa) }}">
                            @error('trans_placa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Placa de la caja --}}
                        <div class="col-md-12">
                            <label class="form-label">Placa de la Caja</label>
                            <input type="text" class="form-control @error('trans_placacaja') is-invalid @enderror" name="trans_placacaja" value="{{ old('trans_placacaja', $embarcacion->trans_placacaja) }}">
                            @error('trans_placacaja')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Temperatura de la Caja (°C/°F) --}}
                        <div class="col-md-12">
                            <label class="form-label">Temperatura de la Caja (°C/°F)</label>
                            <input type="text" class="form-control @error('trans_temperaturacaja') is-invalid @enderror" name="trans_temperaturacaja" value="{{ old('trans_temperaturacaja', $embarcacion->trans_temperaturacaja) }}">
                            @error('trans_temperaturacaja')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <h5 class="card-title">3.- Condición de Transporte</h5>

                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" 
                                    class="form-check-input" 
                                    id="condtrans_estado" 
                                    name="condtrans_estado" 
                                    value="1" 
                                    {{ old('condtrans_estado', $embarcacion->condtrans_estado) ? 'checked' : '' }}>
                                <label class="form-check-label" for="condtrans_estado">En buen estado</label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" 
                                    class="form-check-input" 
                                    id="condtrans_higiene" 
                                    name="condtrans_higiene" 
                                    value="1" 
                                    {{ old('condtrans_higiene', $embarcacion->condtrans_higiene) ? 'checked' : '' }}>
                                <label class="form-check-label" for="condtrans_higiene">Limpio y libre de malos olores</label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" 
                                    class="form-check-input" 
                                    id="condtrans_plagas" 
                                    name="condtrans_plagas" 
                                    value="1" 
                                    {{ old('condtrans_plagas', $embarcacion->condtrans_plagas) ? 'checked' : '' }}>
                                <label class="form-check-label" for="condtrans_plagas">Presencia de plagas</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Producto de la última carga</label>
                            <input type="text" 
                                   class="form-control @error('prod_ultimacarga') is-invalid @enderror" 
                                   name="prod_ultimacarga" 
                                   value="{{ old('prod_ultimacarga', $embarcacion->prod_ultimacarga) }}">
                            @error('prod_ultimacarga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h5 class="card-title">4.- Condición de Tarima</h5>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" 
                                    class="form-check-input" 
                                    id="condtar_desmontado" 
                                    name="condtar_desmontado" 
                                    value="1" 
                                    {{ old('condtar_desmontado', $embarcacion->condtar_desmontado) ? 'checked' : '' }}>
                                <label class="form-check-label" for="condtar_desmontado">Remontado Correcto</label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" 
                                    class="form-check-input" 
                                    id="condtar_flejado" 
                                    name="condtar_flejado" 
                                    value="1" 
                                    {{ old('condtar_flejado', $embarcacion->condtar_flejado) ? 'checked' : '' }}>
                                <label class="form-check-label" for="condtar_flejado">Flejado Correcto</label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" 
                                    class="form-check-input" 
                                    id="condtar_distribucion" 
                                    name="condtar_distribucion" 
                                    value="1" 
                                    {{ old('condtar_distribucion', $embarcacion->condtar_distribucion) ? 'checked' : '' }}>
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
                                    value="{{ old('infcarga_hrallegada', $embarcacion->infcarga_hrallegada) }}">
                                @error('infcarga_hrallegada')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Hora de carga</label>
                                <input type="datetime-local" 
                                    class="form-control @error('infcarga_hracarga') is-invalid @enderror" 
                                    name="infcarga_hracarga" 
                                    value="{{ old('infcarga_hracarga', $embarcacion->infcarga_hracarga) }}">
                                @error('infcarga_hracarga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        

                        {{-- Hora de salida --}}
                        <div class="col-md-12">
                            <label class="form-label">Hora de salida</label>
                            <input type="datetime-local" class="form-control @error('infcarga_hrasalida') is-invalid @enderror" name="infcarga_hrasalida" value="{{ old('infcarga_hrasalida', $embarcacion->infcarga_hrasalida) }}">
                            @error('infcarga_hrasalida')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            {{-- N° Sello --}}
                            <div class="col-md-6">
                                <label class="form-label">N° Sello</label>
                                <input type="text" class="form-control @error('infcarga_nsello') is-invalid @enderror" name="infcarga_nsello" value="{{ old('infcarga_nsello', $embarcacion->infcarga_nsello) }}">
                                @error('infcarga_nsello')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- N° Chismografo --}}
                            <div class="col-md-6">
                                <label class="form-label">N° Chismografo</label>
                                <input type="text" class="form-control @error('infcarga_nchismografo') is-invalid @enderror" name="infcarga_nchismografo" value="{{ old('infcarga_nchismografo', $embarcacion->infcarga_nchismografo) }}">
                                @error('infcarga_nchismografo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                  
                    {{-- Información de Embarque (detalle) --}}
                    <div class="col-lg-12">
                        <h5 class="card-title">6.- Información de Embarque</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">#Tarima</th>
                                    <th class="text-center">Fruta</th>
                                    <th class="text-center">Presentación</th>
                                    <th class="text-center">Variedad</th>
                                    <th class="text-center">Cant. Carga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($embarcacion->detalles as $detalle)
                                    @php
                                        $tarima = $detalle->tarimaDetarec->tarima ?? ($detalle->conservacion->tarima ?? null);
                                        $tarimaDet = $detalle->tarimaDetarec ?? null;
                                        $detRecep = $tarimaDet && $tarimaDet->detalle ? $tarimaDet->detalle : null;
                                    @endphp
                                    @if($tarima && $tarimaDet && $detRecep)
                                        <tr>
                                            <td class="text-center">{{ $tarima->codigo ?? $tarima->id }}</td>
                                            <td class="text-center">{{ $detRecep->fruta->nombrefruta ?? 'N/A' }}</td>
                                            <td class="text-center">{{ $detRecep->presentacion->nombrepresentacion ?? 'N/A' }}</td>
                                            <td class="text-center">{{ $detRecep->variedad->tipofruta ?? 'N/A' }}</td>
                                            <td class="text-center">{{ $tarimaDet->cantidadcarga ?? 0 }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                  
                    <div class="col-lg-12">
                        <h5 class="card-title">7.- Totales</h5>
                        <div class="row">
                            @for($i = 1; $i <= 6; $i++)
                                <div class="col-md-4">
                                    <label class="form-label">Total {{ $i }}</label>
                                    <input type="text" 
                                        class="form-control @error('total' . $i) is-invalid @enderror" 
                                        name="total{{ $i }}" 
                                        value="{{ old('total' . $i, $embarcacion->{'total' . $i}) }}">
                                    @error('total' . $i)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endfor
                        </div>
                    </div>
            </div>
                

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary" {{ $yaEditado ? 'disabled' : '' }}>
                        {{ $yaEditado ? 'Ya Completado' : 'Actualizar' }}
                    </button>
                    @if(!$yaEditado)
                        <a href="{{ route('embarcacion.mostrar') }}" class="btn btn-secondary">Cancelar</a>
                    @else
                        <a href="{{ route('embarcacion.mostrar') }}" class="btn btn-secondary">Volver</a>
                    @endif
                </div>
            </form>
        </div>
      </div>
    </div>
  
  </div>
</section>
@endsection