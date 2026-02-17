@extends('layouts.app')

@section('title', 'Editar Contrato')

@section('content')
@php $user = Auth::user(); @endphp

<div class="pagetitle">
  <h1>Editar Contrato</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Contratos</li>
      <li class="breadcrumb-item"><a href="{{ route('contrato.mostrar') }}">Ver Contratos</a></li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Editar Contrato</h5>

          @if ($errors->any())
            <div id="error-alert" class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form class="row g-3" method="POST" action="{{ route('contrato.update', $contrato->id) }}">
            @csrf

            {{-- Comercializadora --}}
            <div class="col-md-6">
              <label class="form-label">Comercializadora</label>
              <select class="form-select" name="idcomercializadora" required>
                <option value="">Selecciona</option>
                @foreach($comercializadoras as $comercializadora)
                  <option value="{{ $comercializadora->id }}" {{ $contrato->idcomercializadora == $comercializadora->id ? 'selected' : '' }}>
                    {{ $comercializadora->abreviatura }} - {{ $comercializadora->nombrecomercializadora }}
                  </option>
                @endforeach
              </select>
            </div>

            {{-- Usuario --}}
            <div class="col-md-6">
              <label class="form-label">Usuario</label>
              <input type="hidden" name="idusuario" value="{{ $contrato->idusuario }}">
              <input type="text" class="form-control" value="{{ $contrato->usuario->name ?? $user->name }} {{ $contrato->usuario->apellidos ?? '' }}" readonly>
            </div>

            {{-- Tipo Cliente --}}
            <div class="col-md-3">
              <label class="form-label">Tipo de Cliente</label>
              <select class="form-select" name="tipocliente" required>
                <option value="">Selecciona</option>
                <option value="EXPORTACIÓN" {{ $contrato->tipocliente == 'EXPORTACIÓN' ? 'selected' : '' }}>EXPORTACIÓN</option>
                <option value="IMPORTACIÓN" {{ $contrato->tipocliente == 'IMPORTACIÓN' ? 'selected' : '' }}>IMPORTACIÓN</option>
              </select>
            </div>

            {{-- Tipo Contrato --}}
            <div class="col-md-3">
              <label class="form-label">Tipo de Contrato</label>
              <select class="form-select" name="tipocontrato" required>
                <option value="">Selecciona</option>
                <option value="TEMPORADA" {{ $contrato->tipocontrato == 'TEMPORADA' ? 'selected' : '' }}>TEMPORADA</option>
                <option value="ANUAL" {{ $contrato->tipocontrato == 'ANUAL' ? 'selected' : '' }}>ANUAL</option>
              </select>
            </div>

            {{-- Coolers --}}
            <div class="col-md-3">
              <label class="form-label">Coolers</label>
              <select class="form-select" name="idcooler" required>
                <option value="">Selecciona</option>
                @foreach($coolers as $cooler)
                  <option value="{{ $cooler->id }}" {{ $contrato->idcooler == $cooler->id ? 'selected' : '' }}>
                    {{ $cooler->codigoidentificador }} - {{ $cooler->nombrecooler }}
                  </option>
                @endforeach
              </select>
            </div>
            {{-- Fecha --}}
            <div class="col-md-3">
              <label class="form-label">Fecha de Contrato</label>
              <input type="date" class="form-control" name="fechacontrato" value="{{ \Carbon\Carbon::parse($contrato->fechacontrato)->format('Y-m-d') }}" required>
            </div>

            {{-- Tabla de detalle --}}
            <div class="col-md-12 mt-4">
              <h5 class="card-title">Detalle de Contrato</h5>
              <table class="table table-bordered" id="detalles-recepcion">
                <thead>
                  <tr>
                    <th>Fruta</th>
                    <th>Variedad</th>
                    <th>Presentación</th>
                    <th>Servicio</th>
                    <th>Monto (Caja)</th>
                    <th>Moneda</th>
                    <th><i class="bi bi-trash"></i></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($contrato->detalleContrato as $detalle)
                    <tr>
                      <td>
                        <input type="hidden" name="iddetalle[]" value="{{ $detalle->id }}">
                        <select name="idfruta[]" class="form-select" required>
                          <option value="">Fruta</option>
                          @foreach($frutas as $fruta)
                            <option value="{{ $fruta->id }}" {{ $detalle->idfruta == $fruta->id ? 'selected' : '' }}>
                              {{ $fruta->nombrefruta }}
                            </option>
                          @endforeach
                        </select>
                      </td>
                      <td>
                        <select name="idvariedad[]" class="form-select" required>
                          <option value="">Variedad</option>
                          @foreach($variedades as $variedad)
                            <option value="{{ $variedad->id }}" {{ $detalle->idvariedad == $variedad->id ? 'selected' : '' }}>
                              {{ $variedad->tipofruta }}
                            </option>
                          @endforeach
                        </select>
                      </td>
                      <td>
                        <select name="idpresentacion[]" class="form-select" required>
                          <option value="">Presentación</option>
                          @foreach($presentaciones as $presenta)
                            <option value="{{ $presenta->id }}">{{ $presenta->nombrepresentacion }}</option>
                            <option value="{{ $presenta->id }}" {{ $detalle->idpresentacion == $presenta->id ? 'selected' : '' }}>
                              {{ $presenta->nombrepresentacion }}
                            </option>
                          @endforeach
                        </select>
                      </td>
                       <td>
                        <select name="tiposervicio[]" class="form-select" required>
                          <option value="">Servicio</option>
                          <option value="{{ $detalle->tiposervicio }}" {{ $detalle->tiposervicio ? 'selected' : '' }}>
                              @if ($detalle->tiposervicio == 'preenfrio')
                                  PRE ENFRIADO
                              @elseif ($detalle->tiposervicio == 'conservacion')
                                  CONSERVACIÓN
                              @elseif ($detalle->tiposervicio == 'anden')
                                  CRUCE DE ANDÉN
                              @endif
                            </option>
                          <option value="preenfrio">PRE ENFRIADO</option>
                          <option value="conservacion">CONSERVACIÓN</option>
                          <option value="anden">CRUCE DE ANDÉN</option>
                        </select>
                      </td>
                      <td>
                        <input type="number" step="0.01" name="monto[]" value="{{ $detalle->monto }}" class="form-control" required>
                      </td>
                      <td>
                        <select name="moneda[]" class="form-select" required>
                          <option value="">Moneda</option>
                          <option value="DOLAR" {{ ($detalle->moneda ?? '') == 'DOLAR' ? 'selected' : '' }}>USD</option>
                          <option value="PESO" {{ ($detalle->moneda ?? '') == 'PESO' ? 'selected' : '' }}>$MXN</option>
                        </select>
                      </td>
                      <td><button type="button" class="btn btn-danger btn-sm eliminar-fila"><i class="bi bi-trash"></i></button></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <div class="mb-3">
                <button type="button" class="btn btn-secondary" id="agregar-fila">Agregar fila</button>
              </div>
            </div>

            {{-- Botones --}}
            <div class="col-md-12 text-center">
              <button type="submit" class="btn btn-primary">ACTUALIZAR</button>
              <a href="{{ route('contrato.mostrar') }}" class="btn btn-secondary">CANCELAR</a>
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
document.getElementById('agregar-fila').addEventListener('click', function () {
  const tabla = document.querySelector('#detalles-recepcion tbody');
  const filaBase = tabla.rows[0];
  const nuevaFila = filaBase.cloneNode(true);
  nuevaFila.querySelectorAll('input').forEach(input => input.value = '');
  nuevaFila.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
  tabla.appendChild(nuevaFila);
});

document.querySelector('#detalles-recepcion').addEventListener('click', function (e) {
  if (e.target.closest('.eliminar-fila')) {
    const filas = document.querySelectorAll('#detalles-recepcion tbody tr');
    if (filas.length > 1) {
      e.target.closest('tr').remove();
    } else {
      alert('Debe haber al menos una fila.');
    }
  }
});
</script>
@endpush
