@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
    <div class="pagetitle">
      <h1>Sucursales</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active">Sucursales</li>
          @if(hasPermission('crear_coolers'))
            <li class="breadcrumb-item"><a href="{{ route('cooler.crear') }}">Crear Sucursal</a></li>
          @endif
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
                  <h5 class="card-title">Sucursales <span>| Todas</span></h5>

                  @if($coolers->isEmpty())
                    <div class="alert alert-warning">
                      <i class="bi bi-exclamation-triangle"></i>
                      No tienes sucursales asignadas. Contacta al administrador para que te asigne sucursales.
                    </div>
                  @else
                    <table class="table table-borderless datatable">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Sucursal</th>
                          <th scope="col">Código</th>
                          <th scope="col">Ubicación</th>
                          <th scope="col">Fecha</th>
                          <th scope="col" style="text-align:center">Acción</th>
                      
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($coolers as $cooler)
                      <tr>
                          <th scope="row"><a href="#">#{{ $cooler->id }}</a></th>
                          <td><a href="#" class="text-primary">{{ $cooler->nombrecooler }}</a></td>
                          <td>{{ $cooler->codigoidentificador}}</td>
                          <td>{{ $cooler->ubicacion}}</td>
                          <td>{{ $cooler->created_at}}</td>
                          <td>
                            {{-- Botón Editar --}}
                            <a href="{{ route('cooler.editar', $cooler->id) }}"  class="btn btn-sm btn-success" title="Editar">
                              <i class="bi bi-pencil-square"></i>
                            </a>
                            {{-- Botón Eliminar --}}
                            <a href="{{ route('cooler.eliminar', $cooler->id) }}"  class="btn btn-sm btn-danger" title="Eliminar">
                              <i class="bi bi-trash"></i>
                            </a>
                          </td>
                          
                      </tr>
                        @endforeach
                      </tbody>
                    </table>
                  @endif

                </div>

              </div>
            </div><!-- End Recent Sales -->
             
          </div>
        </div><!-- End Left side columns -->
      </div>
    </section>
@endsection