@extends('layouts.app')

@section('title', 'Editar Permisos')

@section('content')
    <div class="pagetitle">
      <h1>Editar Permisos del Rol: {{ $rol->nombrerol }}</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('permisos.index') }}">Permisos</a></li>
          <li class="breadcrumb-item active">Editar</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Asignar Permisos</h5>

              <form action="{{ route('permisos.actualizar', $rol->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="alert alert-info">
                  <i class="bi bi-info-circle"></i> Selecciona los permisos que deseas asignar al rol <strong>{{ $rol->nombrerol }}</strong>
                </div>

                @foreach($permissionsByModule as $module => $permissions)
                  <div class="card mb-3">
                    <div class="card-header bg-light">
                      <div class="form-check">
                        <input class="form-check-input module-checkbox" type="checkbox" id="module_{{ $module }}" 
                               data-module="{{ $module }}"
                               {{ $permissions->every(fn($p) => $rol->permissions->contains($p->id)) ? 'checked' : '' }}>
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
                              <input class="form-check-input permission-checkbox" 
                                     type="checkbox" 
                                     name="permissions[]" 
                                     value="{{ $permission->id }}" 
                                     id="permission_{{ $permission->id }}"
                                     data-module="{{ $module }}"
                                     {{ $rol->permissions->contains($permission->id) ? 'checked' : '' }}>
                              <label class="form-check-label" for="permission_{{ $permission->id }}">
                                <strong>{{ $permission->display_name }}</strong>
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
                  <a href="{{ route('permisos.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                  </a>
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
