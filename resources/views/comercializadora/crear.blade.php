@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="pagetitle">
  <h1>Formulario de Comercializadora</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Comercializadoras</li>
      <li class="breadcrumb-item"><a href="{{ route('comercializadora.mostrar') }}">Ver Comercializadoras</a></li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Crear Comercializadora</h5>
          {{-- ERRORES DE VALIDACIÓN --}}
          @if ($errors->any())
            <div  id="error-alert" class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
            <script>
              setTimeout(function () {
                const alertBox = document.getElementById('error-alert');
                if (alertBox) {
                  alertBox.style.transition = "opacity 0.5s ease";
                  alertBox.style.opacity = 0;
                  setTimeout(() => alertBox.remove(), 500); 
                }
              }, 5000); 
            </script>
          @endif

          <!-- FORMULARIO -->
          <form class="row g-3" method="POST" action="{{ route('comercializadora.registrar') }}" enctype="multipart/form-data">
            @csrf
             <div class="col-md-6">
              <label class="form-label">RFC</label>
              <input type="text" class="form-control" name="rfc" value="{{ old('rfc') }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">Representante</label>
              <input type="text" class="form-control" name="nombrerepresentante" value="{{ old('nombrerepresentante') }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">Teléfono</label>
              <input type="text" class="form-control" name="numtelefono" value="{{ old('numtelefono') }}">
            </div>

            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" name="correo" value="{{ old('correo') }}">
            </div>

            <div class="col-md-6">
              <label class="form-label">Banco</label>
              <input type="text" class="form-control" name="banco" value="{{ old('banco') }}">
            </div>

            <div class="col-md-6">
              <label class="form-label">CLABE Interbancaria</label>
              <input type="text" class="form-control" name="clave" value="{{ old('clave') }}">
            </div>
            
            <div class="col-md-6">
              <label class="form-label">Abreviatura</label>
              <input type="text" class="form-control" name="abreviatura" value="{{ old('abreviatura') }}">
            </div>
            
            <div class="col-md-6">
              <label class="form-label">Imagen Comercializadora</label>
               <input class="form-control" type="file" id="imgcomercializadora" name="imgcomercializadora">
            </div>

            <div class="col-md-6">
              <label class="form-label">Comercializadora</label>
              <input type="text" class="form-control" name="nombrecomercializadora" value="{{ old('nombrecomercializadora') }}">
            </div>
            <div class="col-md-6"></div>

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
    const form = document.querySelector('form');
    const requiredFields = form.querySelectorAll('input, select');
    const btnGuardar = form.querySelector('button[type="submit"]');
    const btnCancelar = form.querySelector('button[type="reset"]');

    // Evita envío con Enter
    form.addEventListener('keydown', function(event) {
      if (event.key === 'Enter' && event.target.tagName !== 'TEXTAREA') {
        event.preventDefault();
      }
    });

    // Desactiva botones si no están llenos todos los campos
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

    validarCamposLlenos(); // Ejecutar al inicio
  });
</script>
@endpush
