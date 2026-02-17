@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
    <div class="pagetitle">
      <h1>Formulario de Frutas</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Frutas</li>
          <li class="breadcrumb-item"><a href="{{ route('fruta.mostrar') }}">Ver Frutas</a></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Crear Frutas</h5>
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
              <!-- General Form Elements -->
              <form method="POST" action="{{ route('fruta.guardar') }}" enctype="multipart/form-data">
                @csrf
                {{-- NOMBRE FRUTA --}}
                <div class="row mb-3">
                  <label for="inputText" class="col-sm-6 col-form-label">Nombre Fruta</label>
                  <div class="col-sm-12">
                    <input type="text" class="form-control" name="nombrefruta" value="{{ old('nombrefruta') }}">
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputNumber" class="col-sm-6 col-form-label">Imagen Fruta</label>
                  <div class="col-sm-10">
                    <input class="form-control" type="file" id="imgfruta" name="imgfruta">
                  </div>
                </div>
                <div class="row mb-3">
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">GUARDAR</button>
                        <button type="reset" class="btn btn-secondary">CANCELAR</button>
                    </div>
                </div>

              </form><!-- End General Form Elements -->

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