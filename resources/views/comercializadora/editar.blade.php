@extends('layouts.app')

@section('title', 'Editar Comercializadora')

@section('content')
<div class="pagetitle">
  <h1>Editar Comercializadora</h1>
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
          <h5 class="card-title">Actualizar Comercializadora</h5>

          @if ($errors->any())
            <div id="error-alert" class="alert alert-danger">
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
          <form class="row g-3" method="POST" action="{{ route('comercializadora.update', $comercializadora->id) }}" enctype="multipart/form-data">
            @csrf
            <div class="col-md-6">
                <label class="form-label">RFC</label>
                <input type="text" class="form-control" name="rfc" value="{{ old('rfc', $comercializadora->rfc) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Representante</label>
                <input type="text" class="form-control" name="nombrerepresentante" value="{{ old('nombrerepresentante', $comercializadora->nombrerepresentante) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Teléfono</label>
                <input type="text" class="form-control" name="numtelefono" value="{{ old('numtelefono', $comercializadora->numtelefono) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="correo" value="{{ old('correo', $comercializadora->correo) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Banco</label>
                <input type="text" class="form-control" name="banco" value="{{ old('banco', $comercializadora->banco) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Clabe Interbancaria</label>
                <input type="text" class="form-control" name="clave" value="{{ old('clave', $comercializadora->clave) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Abreviatura</label>
                <input type="text" class="form-control" name="abreviatura" value="{{ old('abreviatura', $comercializadora->abreviatura) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Imagen Comercializadora</label>
                @if ($comercializadora->imgcomercializadora)
                    <div class="mb-2">
                        <img src="{{ asset($comercializadora->imgcomercializadora) }}" alt="Imagen actual" width="100">
                    </div>
                @endif
                <input class="form-control" type="file" name="imgcomercializadora" accept="image/*">
            </div>

            <div class="col-md-6">
                <label class="form-label">Comercializadora</label>
                <input type="text" class="form-control" name="nombrecomercializadora" value="{{ old('nombrecomercializadora', $comercializadora->nombrecomercializadora) }}">
            </div>
            <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-primary">ACTUALIZAR</button>
                <a href="{{ route('comercializadora.mostrar') }}" class="btn btn-secondary">CANCELAR</a>
            </div>
        </form>


        </div>
      </div>

    </div>
  </div>
</section>
@endsection
