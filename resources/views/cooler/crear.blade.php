@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="pagetitle">
  <h1>Formulario de Sucursal</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Sucursal</li>
      <li class="breadcrumb-item"><a href="{{ route('cooler.mostrar') }}">Ver Sucursal</a></li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Crear Sucursal</h5>

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

          <form class="row g-3" method="POST" action="{{ route('cooler.registrar') }}">
            @csrf

            {{-- Nombre --}}
            <div class="col-md-6">
              <label class="form-label">Nombre</label>
              <input type="text" class="form-control @error('nombrecooler') is-invalid @enderror" name="nombrecooler" value="{{ old('nombrecooler') }}">
              @error('nombrecooler')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            {{-- Código Identificador --}}
            <div class="col-md-6">
              <label class="form-label">Código</label>
              <input type="text" class="form-control @error('codigoidentificador') is-invalid @enderror" name="codigoidentificador" value="{{ old('codigoidentificador') }}">
              @error('codigoidentificador')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Ubicación --}}
            <div class="col-md-12">
              <label class="form-label">Ubicación</label>
              <input type="text" class="form-control @error('ubicacion') is-invalid @enderror" name="ubicacion" value="{{ old('ubicacion') }}">
              @error('ubicacion')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
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
  document.addEventListener('DOMContentLoaded', function () {
    // Ocultar alertas después de 5 segundos
    setTimeout(function () {
      const alertBox = document.getElementById('error-alert');
      if (alertBox) {
        alertBox.style.transition = "opacity 0.5s ease";
        alertBox.style.opacity = 0;
        setTimeout(() => alertBox.remove(), 500);
      }
    }, 5000);

    // Validación de campos llenos
    const form = document.querySelector('form');
    const requiredFields = form.querySelectorAll('input, select');
    const btnGuardar = form.querySelector('button[type="submit"]');
    const btnCancelar = form.querySelector('button[type="reset"]');

    function validarCampos() {
      let completo = true;
      requiredFields.forEach(field => {
        if (field.type !== 'hidden' && !field.value.trim()) {
          completo = false;
        }
      });
      btnGuardar.disabled = !completo;
      btnCancelar.disabled = !completo;
    }

    requiredFields.forEach(field => {
      field.addEventListener('input', validarCampos);
      field.addEventListener('change', validarCampos);
    });

    validarCampos();
  });
</script>
@endpush
