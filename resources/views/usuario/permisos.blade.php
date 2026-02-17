@extends('layouts.app')

@section('title', 'Permisos del Usuario')

@section('content')
    <div class="pagetitle">
      <h1>Permisos de: {{ $user->name }} {{ $user->apellidos }}</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('usuario.mostrar') }}">Usuarios</a></li>
          <li class="breadcrumb-item active">Permisos</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Asignar Permisos Directos</h5>

              <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> 
                <strong>Información del Usuario:</strong><br>
                <strong>Rol:</strong> {{ $user->rol->nombrerol ?? 'Sin rol' }}<br>
                <strong>Email:</strong> {{ $user->email }}
              </div>

              <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i> 
                Los permisos marcados en <span class="badge bg-secondary">gris</span> son heredados del rol.
                Los permisos que selecciones aquí son <strong>adicionales</strong> a los del rol.
              </div>

              <form action="{{ route('usuario.permisos.actualizar', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                @foreach($permissionsByModule as $module => $permissions)
                  <div class="card mb-3">
                    <div class="card-header bg-light">
                      <div class="form-check">
                        <input class="form-check-input module-checkbox" 
                               type="checkbox" 
                               id="module_{{ $module }}" 
                               data-module="{{ $module }}">
                        <label class="form-check-label fw-bold" for="module_{{ $module }}">
                          <i class="bi bi-folder"></i> {{ ucfirst($module) }} (Seleccionar todos)
                        </label>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        @foreach($permissions as $permission)
                          <div class="col-md-6 mb-2">
                            <div class="form-check">
                              @php
                                $hasFromRole = in_array($permission->id, $rolePermissions);
                                $hasDirectly = in_array($permission->id, $userDirectPermissions);
                              @endphp
                              
                              <input class="form-check-input permission-checkbox" 
                                     type="checkbox" 
                                     name="permissions[]" 
                                     value="{{ $permission->id }}" 
                                     id="permission_{{ $permission->id }}"
                                     data-module="{{ $module }}"
                                     {{ $hasDirectly ? 'checked' : '' }}>
                              
                              <label class="form-check-label" for="permission_{{ $permission->id }}">
                                <strong>{{ $permission->display_name }}</strong>
                                @if($hasFromRole)
                                  <span class="badge bg-secondary" title="Heredado del rol">Del Rol</span>
                                @endif
                                <br>
                                <small class="text-muted">{{ $permission->description }}</small>
                              </label>
                            </div>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  </div>
                @endforeach

                <div class="mt-4">
                  <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Guardar Permisos
                  </button>
                  <a href="{{ route('usuario.mostrar') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                  </a>
                  
                  <div class="btn-group float-end">
                    <form action="{{ route('usuario.permisos.asignar-todos', $user->id) }}" method="POST" style="display: inline;">
                      @csrf
                      <button type="submit" class="btn btn-success" onclick="return confirm('¿Asignar todos los permisos a este usuario?')">
                        <i class="bi bi-check-all"></i> Asignar Todos
                      </button>
                    </form>
                    
                    <form action="{{ route('usuario.permisos.remover-todos', $user->id) }}" method="POST" style="display: inline;">
                      @csrf
                      <button type="submit" class="btn btn-danger" onclick="return confirm('¿Remover todos los permisos directos de este usuario?')">
                        <i class="bi bi-x-circle"></i> Remover Todos
                      </button>
                    </form>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>

    <script>
      // Script para seleccionar/deseleccionar todos los permisos de un módulo
      document.addEventListener('DOMContentLoaded', function() {
        // Manejar checkbox de módulo
        document.querySelectorAll('.module-checkbox').forEach(function(moduleCheckbox) {
          moduleCheckbox.addEventListener('change', function() {
            const module = this.dataset.module;
            const isChecked = this.checked;
            
            document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`).forEach(function(permCheckbox) {
              permCheckbox.checked = isChecked;
            });
          });
        });

        // Actualizar estado del checkbox de módulo cuando cambian los permisos individuales
        document.querySelectorAll('.permission-checkbox').forEach(function(permCheckbox) {
          permCheckbox.addEventListener('change', function() {
            const module = this.dataset.module;
            const moduleCheckbox = document.querySelector(`.module-checkbox[data-module="${module}"]`);
            const allPermsInModule = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`);
            const checkedPermsInModule = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]:checked`);
            
            moduleCheckbox.checked = allPermsInModule.length === checkedPermsInModule.length;
          });
        });
      });
    </script>
@endsection
