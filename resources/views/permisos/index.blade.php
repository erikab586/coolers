@extends('layouts.app')

@section('title', 'Gestión de Permisos')

@section('content')
    <div class="pagetitle">
      <h1>Gestión de Permisos</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item active">Permisos</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    @if(session('success'))
      <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Permisos por Rol</h5>
              <p class="text-muted">Administra los permisos asignados a cada rol de usuario</p>

              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Rol</th>
                      <th>Permisos Asignados</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($roles as $rol)
                      <tr>
                        <td>
                          <strong>{{ $rol->nombrerol }}</strong>
                        </td>
                        <td>
                          @if($rol->permissions->count() > 0)
                            <span class="badge bg-primary">{{ $rol->permissions->count() }} permisos</span>
                          @else
                            <span class="badge bg-secondary">Sin permisos</span>
                          @endif
                        </td>
                        <td>
                          <a href="{{ route('permisos.editar', $rol->id) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-pencil"></i> Editar Permisos
                          </a>
                          
                          @if($rol->permissions->count() < $permissionsByModule->flatten()->count())
                            <form action="{{ route('permisos.asignar-todos', $rol->id) }}" method="POST" style="display: inline;">
                              @csrf
                              <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('¿Asignar todos los permisos a este rol?')">
                                <i class="bi bi-check-all"></i> Asignar Todos
                              </button>
                            </form>
                          @endif

                          @if($rol->permissions->count() > 0)
                            <form action="{{ route('permisos.remover-todos', $rol->id) }}" method="POST" style="display: inline;">
                              @csrf
                              <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Remover todos los permisos de este rol?')">
                                <i class="bi bi-x-circle"></i> Remover Todos
                              </button>
                            </form>
                          @endif
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Tabla de permisos disponibles -->
          <div class="card mt-4">
            <div class="card-body">
              <h5 class="card-title">Permisos Disponibles en el Sistema</h5>
              
              @foreach($permissionsByModule as $module => $permissions)
                <div class="mb-4">
                  <h6 class="text-primary"><i class="bi bi-folder"></i> {{ ucfirst($module) }}</h6>
                  <div class="row">
                    @foreach($permissions as $permission)
                      <div class="col-md-3 mb-2">
                        <div class="border rounded p-2">
                          <strong>{{ $permission->display_name }}</strong>
                          <br>
                          <small class="text-muted">{{ $permission->name }}</small>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </section>
@endsection
