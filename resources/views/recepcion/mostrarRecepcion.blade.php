@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<style>
    .card-img-top {
        width: 100%;
        height: 200px;
        object-fit: cover;
        object-position: center;
    }
</style>
    <div class="pagetitle">
      <h1>Recepciones</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active">Recepciones</li>
          <li class="breadcrumb-item"><a href="{{ route('recepcion.mostrar') }}">Ver Recepciones</a></li>
          <li class="breadcrumb-item"><a href="{{ route('asignartarima.crear') }}">Cargar Tarima</a></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    @if(session('success'))
      <div id="success-alert" class="alert alert-success">
        {{ session('success') }}
      </div>
      <script>
        setTimeout(function () {
          const alertBox = document.getElementById('success-alert');
          if (alertBox) {
            alertBox.style.transition = "opacity 0.5s ease";
            alertBox.style.opacity = 0;
            setTimeout(() => alertBox.remove(), 500);
          }
        }, 5000); // 5 segundos
      </script>
    @endif
    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">
            <!-- Recent Sales -->
            <div class="col-12">
               <div class="card recent-sales overflow-auto">
                    <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    </ul>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title">Crear Recepción <span>| Selecciona una comercializadora</span></h5>
                        <div class="row">
                            @foreach($contratos as $contrato)
                            <div class="col-lg-3">
                                <!-- Card with an image on top -->
                                <div class="card">
                                    <img src="{{ asset($contrato->comercializadora->imgcomercializadora) }}" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <p class="card-text">{{ $contrato->comercializadora->nombrecomercializadora }}- {{ $contrato->cooler->nombrecooler }}</p>
                                        <a href="{{ route('recepcion.crear', $contrato->id) }}" class="card-title">Crear Recepción</a>
                                    </div>
                                </div><!-- End Card with an image on top -->
                            </div>
                            @endforeach
                            
                        </div>
                        

                    </div>
                </div>
            </div><!-- End Recent Sales -->

          </div>
        </div><!-- End Left side columns -->
      </div>
    </section>
@endsection