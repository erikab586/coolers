@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
    <div class="pagetitle">
      <h1>Editar Usuarios</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Usuarios</li>
          <li class="breadcrumb-item"><a href="{{ route('usuario.mostrar') }}">Ver Usuarios</a></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Editar Usuarios</h5>
              <!-- General Form Elements -->
              <form class="row g-3" method="POST" action="{{ route('usuario.update', ['usuario' => $usuario->id]) }}">
                  @csrf

                  <div class="col-md-6">
                    <label class="form-label">Nombres</label>
                    <input type="text" class="form-control" name="name" value="{{ $usuario->name }}">
                  </div>

                  <div class="col-md-6">
                    <label class="form-label">Apellidos</label>
                    <input type="text" class="form-control" name="apellidos" value="{{ $usuario->apellidos }}">
                  </div>

                  <div class="col-md-6">
                    <label class="form-label">Teléfono</label>
                    <input type="text" class="form-control" name="telefono" value="{{ $usuario->telefono }}">
                  </div>

                  <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="{{ $usuario->email }}">
                  </div>

                  <div class="col-md-6">
                    <label class="form-label">Rol de Usuario</label>
                    <select class="form-select" name="idrol">
                      @foreach($roles as $rol)
                        <option value="{{ $rol->id }}" {{ $usuario->idrol == $rol->id ? 'selected' : '' }}>
                          {{ $rol->nombrerol }}
                        </option>
                      @endforeach
                    </select>
                  </div>

                 <div class="col-md-6" id="cooler-container">
                  <label class="form-label">Cooler</label>
                  <select class="form-select" name="idcooler[]" multiple>
                    @foreach($coolers as $cooler)
                      <option value="{{ $cooler->id }}" 
                        {{ $usuario->coolers->contains($cooler->id) ? 'selected' : '' }}>
                        {{ $cooler->codigoidentificador }} - {{ $cooler->nombrecooler }}
                      </option>
                    @endforeach
                  </select>
                </div>

                  <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary">GUARDAR</button>
                    <button type="reset" class="btn btn-secondary">CANCELAR</button>
                  </div>
                </form>
                <!-- End General Form Elements -->

            </div>
          </div>

        </div>
      </div>
    </section>
@endsection
@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const rolSelect = document.querySelector('select[name="idrol"]');
    const coolerContainer = document.getElementById('cooler-container');

    function toggleCooler() {
      if (rolSelect.value == '1') {
        coolerContainer.style.display = 'none'; // Oculta cooler
      } else {
        coolerContainer.style.display = 'block'; // Muestra cooler
      }
    }

    // Ejecuta al cargar
    toggleCooler();

    // Ejecuta al cambiar rol
    rolSelect.addEventListener('change', toggleCooler);
  });
</script>
@endpush
