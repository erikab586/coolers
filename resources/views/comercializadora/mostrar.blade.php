@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
    <div class="pagetitle">
      <h1>Comercializadoras</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active">Comercializadoras</li>
          @if(hasPermission('crear_comercializadoras'))
            <li class="breadcrumb-item"><a href="{{ route('comercializadora.crear') }}">Crear Comercializadora</a></li>
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
                  <h5 class="card-title">Comercializadoras <span>| Todas</span></h5>

                  <table class="table table-borderless datatable">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">RFC</th>
                        <th scope="col">Siglas</th>
                        <th scope="col">Camercializadora</th>
                        <th scope="col">Representante</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Email</th>
                        @if(hasAnyPermission(['editar_comercializadoras', 'eliminar_comercializadoras']))
                          <th scope="col" style="text-align:center">Acción</th>
                        @endif
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($comercializadoras as $comercializadora)
                      <tr>
                          <th scope="row"><a href="#">#{{ $comercializadora->id }}</a></th>
                          <td><a href="#" class="text-primary">{{ $comercializadora->rfc }}</a></td>
                          <td>{{ $comercializadora->abreviatura }}</td>
                          <td>
                            <div class="d-flex align-items-center">
                              <img src="{{ asset($comercializadora->imgcomercializadora ?? 'imagenes/comercializadoras/comercializadora.png' ) }}" alt="FrutiMax" width="40" height="40" class="rounded-circle me-2">
                              <span>{{ $comercializadora->nombrecomercializadora }}</span>

                            </div>
                          </td>
                          <td>{{ $comercializadora->nombrerepresentante }}</td>
                          <td>{{ $comercializadora->numtelefono}}</td>
                          <td>{{ $comercializadora->correo }}</td>
                          <td>
                            {{-- Botón Editar --}}
                            <a href="{{ route('comercializadora.editar', $comercializadora->id) }}"  class="btn btn-sm btn-success" title="Editar">
                              <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="{{ route('comercializadora.eliminar', $comercializadora->id) }}"  class="btn btn-sm btn-danger" title="Eliminar">
                              <i class="bi bi-trash"></i>
                            </a>
                            <!--a class="btn btn-warning" title="Mosttrar"><i class="bi bi-exclamation-triangle"></i></a-->
                          </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>

                </div>

              </div>
            </div><!-- End Recent Sales -->

          </div>
        </div><!-- End Left side columns -->
      </div>
    </section>
@endsection