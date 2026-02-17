@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
    <div class="pagetitle">
      <h1>Usuarios</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active">Usuarios</li>
          @if(hasPermission('crear_usuarios'))
            <li class="breadcrumb-item"><a href="{{ route('usuario.crear') }}">Crear Usuario</a></li>
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
                  <h5 class="card-title">Usuarios <span>| Todos</span></h5>

                  <table class="table table-borderless datatable">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombres y apellidos</th>
                        <th scope="col">Telefono</th>
                        <th scope="col">Email</th>
                        <th scope="col">Rol</th>
                        <th scope="col">Cooler</th>
                        <th scope="col">Conexión</th>
                        @if(hasAnyPermission(['editar_usuarios', 'eliminar_usuarios']))
                          <th scope="col">Acción</th>
                        @endif
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($usuarios as $usuario)
                      <tr>
                          <th scope="row"><a href="#">#{{ $usuario->id }}</a></th>
                          <td>{{ $usuario->name }} {{ $usuario->apellidos }}</td>
                          <td>{{ $usuario->telefono }}</td>
                          <td>{{ $usuario->email }}</td>
                          <td>{{ $usuario->rol->nombrerol }}</td>
                          <td>
                              @if($usuario->rol->nombrerol === 'Administrador')
                                  Todos los coolers
                              @elseif($usuario->coolers && $usuario->coolers->count())
                                  {{ $usuario->coolers->pluck('nombrecooler')->join(', ') }}
                              @else
                                  Sin cooler asignado
                              @endif
                          </td>
                          <td>{{ $usuario->fechaconexion }}</td>
                          @if(hasAnyPermission(['editar_usuarios', 'eliminar_usuarios']))
                            <td>
                              @if(hasPermission('editar_usuarios'))
                                {{-- Botón Editar --}}
                                <a href="{{ route('usuario.editar', $usuario->id) }}"  class="btn btn-success btn-sm" title="Editar">
                                  <i class="bi bi-pencil-square"></i>
                                </a>
                                
                                {{-- Botón Permisos --}}
                                <a href="{{ route('usuario.permisos.editar', $usuario->id) }}"  class="btn btn-primary btn-sm" title="Permisos">
                                  <i class="bi bi-shield-lock"></i>
                                </a>
                              @endif
                              
                              @if(hasPermission('eliminar_usuarios'))
                                {{-- Botón Eliminar --}}
                                <a href="{{ route('usuario.eliminar', $usuario->id) }}"  class="btn btn-danger btn-sm" title="Eliminar">
                                  <i class="bi bi-trash"></i>
                                </a>
                              @endif
                            </td>
                          @endif
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