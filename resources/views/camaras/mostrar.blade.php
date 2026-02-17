@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
    <div class="pagetitle">
      <h1>Cámaras</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active">Cámaras</li>
          <li class="breadcrumb-item"><a href="{{ route('camara.crear') }}">Crear Cámara</a></li>
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
        }, 5000);
      </script>
    @endif

    <section class="section dashboard">
      <div class="row">
        <div class="col-lg-12">
          <div class="row">
            <div class="col-12">
              <div class="card recent-sales overflow-auto">

                <div class="card-body">
                  @if($camaras->isEmpty())
                    <div class="alert alert-warning">
                      <i class="bi bi-exclamation-triangle"></i>
                      No tienes cámaras asignadas. Contacta al administrador para que te asigne coolers con cámaras.
                    </div>
                  @else
                    @foreach ($camaras as $idcooler => $grupoCamaras)
                      <h5 class="card-title">Cooler <span>| {{ $grupoCamaras->first()->cooler->nombrecooler }}</span></h5>
                      <table class="table table-borderless datatable mb-4">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Número</th>
                          <th scope="col">Tipo</th>
                          <th scope="col">Fecha</th>
                          <th scope="col" style="text-align:center">Acción</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($grupoCamaras as $camara)
                          <tr>
                            <th scope="row"><a href="#">#{{ $camara->id }}</a></th>
                            <td>{{ $camara->codigo }}</td>
                            <td>{{ $camara->tipo }}</td>
                            <td>{{ $camara->created_at }}</td>
                            <td>
                              <a href="{{ route('camara.editar', $camara->id) }}" class="btn btn-sm btn-success" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                              </a>
                              <a href="{{ route('camara.eliminar', $camara->id) }}" class="btn btn-sm btn-danger" title="Eliminar">
                                <i class="bi bi-trash"></i>
                              </a>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                      </table>
                    @endforeach
                  @endif

                </div>

              </div>
            </div>
          
          </div>
        </div>
      </div>
    </section>
@endsection
