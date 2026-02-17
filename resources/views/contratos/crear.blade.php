@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
    @php
      $user = Auth::user();
    @endphp
<div class="pagetitle">
  <h1>Formulario de Contrato</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Contratos</li>
      <li class="breadcrumb-item"><a href="{{ route('contrato.mostrar') }}">Ver Contrato</a></li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Crear Contrato</h5>

          {{-- ERRORES GLOBALES --}}
          @if ($errors->any())
            <div id="error-alert" class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form class="row g-3" method="POST" action="{{ route('contrato.registrar') }}">
            @csrf
            {{-- Comercializadora --}}
            <div class="col-md-6">
              <label class="form-label">Comercializadora</label>
              <select class="form-select @error('idcomercializadora') is-invalid @enderror" name="idcomercializadora">
                <option value="">Selecciona</option>
                @foreach($comercializadoras as $comercializadora)
                  <option value="{{ $comercializadora->id }}" 
                          data-img="{{ asset($comercializadora->imgcomercializadora) }}" {{ old('idcomercializadora') == $comercializadora->id ? 'selected' : '' }}>
                  {{ $comercializadora->abreviatura }}-{{ $comercializadora->nombrecomercializadora }}
                  </option>
                @endforeach
              </select>
              @error('idcomercializadora')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
             {{-- Usuario --}}
            <div class="col-md-6">
              <label class="form-label">Usuario</label>
               <input type="hidden" class="form-control " name="idusuario" value="{{ $user->id }}" readonly>
              <input type="text" class="form-control @error('usuario') is-invalid @enderror" name="usuario" value="{{ $user->name }} {{ $user->apellidos }} " readonly>
              @error('usuario')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Tipo de Cliente --}}
            <div class="col-md-3">
              <label class="form-label">Tipo de Cliente</label>
              <select class="form-select @error('tipocliente') is-invalid @enderror" name="tipocliente">
                <option value="">Selecciona</option>
                <option value="EXPORTACIÓN" {{ old('tipocliente') == 'EXPORTACIÓN' ? 'selected' : '' }}>EXPORTACIÓN</option>
                <option value="IMPORTACIÓN" {{ old('tipocliente') == 'IMPORTACIÓN' ? 'selected' : '' }}>IMPORTACIÓN</option>
              </select>
              @error('tipocliente')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Tipo de Contrato --}}
            <div class="col-md-3">
              <label class="form-label">Tipo de Contrato</label>
              <select class="form-select @error('tipocontrato') is-invalid @enderror" name="tipocontrato">
                <option value="">Selecciona</option>
                <option value="TEMPORADA" {{ old('tipocontrato') == 'TEMPORADA' ? 'selected' : '' }}>TEMPORADA</option>
                <option value="ANUAL" {{ old('tipocontrato') == 'ANUAL' ? 'selected' : '' }}>ANUAL</option>
              </select>
              @error('tipocontrato')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Coolers --}}
            <div class="col-md-3">
              <label class="form-label">Coolers</label>
              <select class="form-select @error('idcooler') is-invalid @enderror" name="idcooler">
                <option value="">Selecciona</option>
                @foreach($coolers as $cooler)
                  <option value="{{ $cooler->id }}" {{ old('idcooler') == $cooler->id ? 'selected' : '' }}>
                    {{ $cooler->codigoidentificador }}-{{ $cooler->nombrecooler}}
                  </option>
                @endforeach
              </select>
              @error('idcooler')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            {{-- Fecha de Contrato --}}
            <div class="col-md-3">
              <label class="form-label">Fecha de Contrato</label>
              <input type="date" class="form-control @error('fechacontrato') is-invalid @enderror" name="fechacontrato"  value="{{ old('fechacontrato', date('Y-m-d')) }}">
              @error('fechacontrato')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Monto --}}
            <div class="col-md-12">
              <h5 class="card-title">Detalle de Contrato</h5>
            </div>

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
                    <tr>
                      <td>
                        <select name="idfruta[]" class="form-select" required>
                          <option value="">Frutas</option>
                          @foreach($frutas as $fruta)
                            <option value="{{ $fruta->id }}">{{ $fruta->nombrefruta }}</option>
                          @endforeach
                        </select>
                      </td>
                      <td>
                        <select name="idvariedad[]" class="form-select" required>
                          <option value="">Variación</option>
                          @foreach($variedades as $variedad)
                            <option value="{{ $variedad->id }}">{{ $variedad->tipofruta }}</option>
                          @endforeach
                        </select>
                      </td>
                      <td>
                        <select name="idpresentacion[]" class="form-select" required>
                          <option value="">Presentación</option>
                          @foreach($presentaciones as $presenta)
                            <option value="{{ $presenta->id }}">{{ $presenta->nombrepresentacion }}</option>
                          @endforeach
                        </select>
                      </td>
                      <td>
                        <select name="tiposervicio[]" class="form-select" required>
                          <option value="">Servicio</option>
                          <option value="preenfrio">PRE ENFRIADO</option>
                          <option value="conservacion">CONSERVACIÓN</option>
                          <option value="anden">CRUCE DE ANDÉN</option>
                        </select>
                      </td>
                      <td><input type="number" step="0.001" name="monto[]" class="form-control" required></td>
                      <td>
                        <select name="moneda[]" class="form-select" required>
                          <option value="">Moneda</option>
                          <option value="DOLAR">USD</option>
                          <option value="PESO">$MXN</option>
                        </select>
                      </td>
                      <td><button type="button" class="btn btn-danger btn-sm eliminar-fila"><i class="bi bi-trash"></i></button></td>
                    </tr>
                  </tbody>

                </table>
                 <div class="mb-3">
                  <button type="button" class="btn btn-secondary" id="agregar-fila">Agregar fila</button>
                </div>
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
   document.getElementById('agregar-fila').addEventListener('click', function () {
              const tabla = document.querySelector('#detalles-recepcion tbody');
              const filaBase = tabla.rows[0];
              const nuevaFila = filaBase.cloneNode(true);

              // Limpiar los campos de texto (input)
              nuevaFila.querySelectorAll('input').forEach(input => input.value = '');

              // Resetear los selects a su valor por defecto
              nuevaFila.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

              tabla.appendChild(nuevaFila);
          });

          // Eliminar fila con validación de que quede al menos una
          document.querySelector('#detalles-recepcion').addEventListener('click', function (e) {
              if (e.target.classList.contains('eliminar-fila')) {
                  const filas = document.querySelectorAll('#detalles-recepcion tbody tr');
                  if (filas.length > 1) {
                      e.target.closest('tr').remove();
                  } else {
                      alert('Debe haber al menos una fila.');
                  }
              }
          });
      
  document.addEventListener('DOMContentLoaded', function () {
    // Ocultar error global
    setTimeout(function () {
      const alertBox = document.getElementById('error-alert');
      if (alertBox) {
        alertBox.style.transition = "opacity 0.5s ease";
        alertBox.style.opacity = 0;
        setTimeout(() => alertBox.remove(), 500);
      }
    }, 5000);

    // Validación campos llenos
    const form = document.querySelector('form');
    const requiredFields = form.querySelectorAll('input, select');
    const btnGuardar = form.querySelector('button[type="submit"]');
    const btnCancelar = form.querySelector('button[type="reset"]');

    form.addEventListener('keydown', function (event) {
      if (event.key === 'Enter' && event.target.tagName !== 'TEXTAREA') {
        event.preventDefault();
      }
    });

    function validarCamposLlenos() {
      let completo = true;
      requiredFields.forEach(field => {
        if (field.type !== 'hidden' && !field.value.trim()) {
          completo = false;
        }
      });

      if (btnGuardar) btnGuardar.disabled = !completo;
      if (btnCancelar) btnCancelar.disabled = !completo;
    }

    requiredFields.forEach(field => {
      field.addEventListener('input', validarCamposLlenos);
      field.addEventListener('change', validarCamposLlenos);
    });

    validarCamposLlenos();
  });
</script>
@endpush
