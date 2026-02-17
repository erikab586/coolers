@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
    <div class="pagetitle">
      <h1>Formulario de Tarimas</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Tarimas</li>
          <li class="breadcrumb-item"><a href="{{ route('tarima.mostrar') }}">Ver Tarimas</a></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Crear Tarimas</h5>
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
              <form class="row g-3" method="POST" action="{{ route('tarima.guardar') }}">
                @csrf
                 
                {{-- CANTIDAD --}}
                <div class="col-md-6">
                  <label for="inputText" class="col-sm-6 col-form-label">Cantidad Recepciones</label>
                  <div class="col-sm-12">
                    <input type="text" class="form-control" name="cantidad" value="{{ old('cantidad') }}">
                  </div>
                </div>
                {{-- CODIGO --}}
                <div class="col-md-6">
                  <label for="inputText" class="col-sm-6 col-form-label">Código</label>
                  <div class="col-sm-12">
                    <input type="text" class="form-control" name="codigo" value="{{ $codigo }}" readonly>
                  </div>
                </div>
                <div class="col-md-6">
                  <select name="estatus" class="form-select" required>
                    <option value="">Estatus</option>
                    <option value="disponible">Disponible</option>
                    <option value="completo">Completo</option>
                  </select>
                </div>
                      
                <div class="col-md-12">
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