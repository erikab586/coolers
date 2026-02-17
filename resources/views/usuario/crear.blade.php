@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="pagetitle">
  <h1>Formulario de Usuarios</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Usuarios</li>
      <li class="breadcrumb-item"><a href="{{ route('usuario.mostrar') }}">Ver Usuarios</a></li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Crear Usuarios</h5>

          {{-- Mensaje de errores --}}
          @if ($errors->any())
            <div id="error-alert" class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form class="row g-3" method="POST" action="{{ route('usuario.guardar') }}">
            @csrf

            {{-- Nombres --}}
            <div class="col-md-6">
              <label class="form-label">Nombres</label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}">
              @error('nombres')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Apellidos --}}
            <div class="col-md-6">
              <label class="form-label">Apellidos</label>
              <input type="text" class="form-control @error('apellidos') is-invalid @enderror" name="apellidos" value="{{ old('apellidos') }}">
              @error('apellidos')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Teléfono --}}
            <div class="col-md-6">
              <label class="form-label">Teléfono</label>
              <input type="text" class="form-control @error('telefono') is-invalid @enderror" name="telefono" value="{{ old('telefono') }}">
              @error('telefono')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Email --}}
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Contraseña --}}
            <div class="col-md-6">
              <label class="form-label">Password</label>
              <input type="password" class="form-control @error('password') is-invalid @enderror" name="password">
              @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Confirmación --}}
            <div class="col-md-6">
              <label class="form-label">Confirmar Password</label>
              <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation">
              @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Rol --}}
          <div class="col-md-6">
            <label class="form-label">Rol de Usuario</label>
            <select id="rolSelect" class="form-select @error('idrol') is-invalid @enderror" name="idrol">
              <option value="">Selecciona</option>
              @foreach($roles as $rol)
                <option value="{{ $rol->id }}">{{ $rol->nombrerol }}</option>
              @endforeach           
            </select>
            @error('rolusuario')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Cooler --}}
          <div class="col-md-6" id="coolerContainer">
            <label class="form-label">Cooler</label><br>
            @foreach($coolers as $cooler)
                <input class="form-check-input cooler-checkbox" type="checkbox" name="idcooler[]" value="{{ $cooler->id }}">
                {{ $cooler->codigoidentificador }} - {{ $cooler->nombrecooler }}<br>
            @endforeach
            <div class="text-danger d-none" id="coolerError">Debes seleccionar al menos un cooler</div>
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
    // Ocultar mensaje de error luego de 5 segundos
    const alertBox = document.getElementById('error-alert');
    if (alertBox) {
      setTimeout(() => {
        alertBox.style.transition = "opacity 0.5s ease";
        alertBox.style.opacity = 0;
        setTimeout(() => alertBox.remove(), 500);
      }, 5000);
    }

    // Validación de campos obligatorios para habilitar el botón GUARDAR
    const form = document.querySelector('form');
    const requiredFields = form.querySelectorAll('input:not([type=hidden]), select');
    const btnGuardar = form.querySelector('button[type="submit"]');
    
    function validarCampos() {
      let completo = true;
      requiredFields.forEach(field => {
        if (!field.value.trim()) {
          completo = false;
        }
      });
      btnGuardar.disabled = !completo;
    }

    requiredFields.forEach(field => {
      field.addEventListener('input', validarCampos);
      field.addEventListener('change', validarCampos);
    });

    validarCampos();
  });
</script>
@endpush
@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const rolSelect = document.getElementById('rolSelect');
    const coolerContainer = document.getElementById('coolerContainer');
    const coolerCheckboxes = document.querySelectorAll('.cooler-checkbox');
    const coolerError = document.getElementById('coolerError');
    const form = document.querySelector('form');

    function toggleCoolerSection() {
      const selectedRoleId = rolSelect.value;

      if (selectedRoleId === "1") { // 👈 ID del Administrador General
        coolerContainer.style.display = "none"; // ocultar
        coolerCheckboxes.forEach(cb => {
          cb.checked = false; // desmarcar
        });
        coolerError.classList.add('d-none');
      } else {
        coolerContainer.style.display = "block"; // mostrar
      }
    }

    rolSelect.addEventListener('change', toggleCoolerSection);

    // Validación antes de enviar
    form.addEventListener('submit', function (e) {
      const selectedRoleId = rolSelect.value;

      if (selectedRoleId !== "1") { // si no es administrador
        const atLeastOneChecked = Array.from(coolerCheckboxes).some(cb => cb.checked);
        if (!atLeastOneChecked) {
          e.preventDefault();
          coolerError.classList.remove('d-none');
        }
      }
    });

    // Ejecutar al cargar
    toggleCoolerSection();
  });
</script>
@endpush

