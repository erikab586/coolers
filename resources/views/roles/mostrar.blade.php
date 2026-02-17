@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
    <div class="pagetitle">
      <h1>Rol de usuario</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active">Roles</li>
          <li class="breadcrumb-item"><a href="{{ route('rolusuario.crear') }}">Crear Rol de Usuario</a></li>
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
                  <h5 class="card-title">Rol de Usuario <span>| Todas</span></h5>

                  <table class="table table-borderless datatable">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre Rol</th>
                        <th scope="col">Fecha</th>
                        <th scope="col" style="text-align:center">Acción</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($roles as $rol)
                      <tr>
                          <th scope="row"><a href="#">#{{ $rol->id }}</a></th>
                          <td>{{ $rol->nombrerol }}</td>
                          <td>{{ $rol->created_at }}</td>
                          <td>
                            {{-- Botón Editar --}}
                            <a href="{{ route('rolusuario.editar', $rol->id) }}" class="btn btn-success" title="Editar">
                              <i class="bi bi-pencil-square"></i>
                            </a>

                            {{-- Botón Eliminar --}}
                            <form action="{{ route('rolusuario.eliminar', $rol->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Estás seguro de eliminar este rol?');">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger" title="Eliminar">
                                <i class="bi bi-trash"></i>
                              </button>
                            </form>
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