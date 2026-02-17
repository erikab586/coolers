@extends('layouts.app')

@section('title', 'Editar Tarima')

@section('content')
    <div class="pagetitle">
      <h1>Editar Tarima</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Tarimas</li>
          <li class="breadcrumb-item"><a href="{{ route('tarima.mostrar') }}">Ver Tarimas</a></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-1"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-1"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Editar Tarima</h5>

              <!-- General Form Elements -->
              <form method="POST" action="{{ route('tarima.update', $tarima->id) }}">
                @csrf
                <div class="row mb-3">
                  <label for="codigo" class="col-sm-3 col-form-label">Código de Tarima</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="codigo" id="codigo" value="{{ $tarima->codigo }}" required>
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="capacidad" class="col-sm-3 col-form-label">Capacidad Disponible</label>
                  <div class="col-sm-9">
                    <input type="number" class="form-control" name="capacidad" id="capacidad" value="{{ $tarima->capacidad }}" required>
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="ubicacion" class="col-sm-3 col-form-label">Ubicación</label>
                  <div class="col-sm-9">
                    <select name="ubicacion" id="ubicacion" class="form-select" required>
                      <option value="tarima" {{ $tarima->ubicacion == 'tarima' ? 'selected' : '' }}>Tarima</option>
                      <option value="preenfriado" {{ $tarima->ubicacion == 'preenfriado' ? 'selected' : '' }}>Preenfriado</option>
                      <option value="conservacion" {{ $tarima->ubicacion == 'conservacion' ? 'selected' : '' }}>Conservación</option>
                      <option value="cruce_anden" {{ $tarima->ubicacion == 'cruce_anden' ? 'selected' : '' }}>Cruce de Andén</option>
                      <option value="embarque" {{ $tarima->ubicacion == 'embarque' ? 'selected' : '' }}>Embarque</option>
                    </select>
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="estatus" class="col-sm-3 col-form-label">Estatus</label>
                  <div class="col-sm-9">
                    <select name="estatus" id="estatus" class="form-select" required>
                      <option value="disponible" {{ $tarima->estatus == 'disponible' ? 'selected' : '' }}>Disponible</option>
                      <option value="completo" {{ $tarima->estatus == 'completo' ? 'selected' : '' }}>Completo</option>
                    </select>
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="observaciones" class="col-sm-3 col-form-label">Observaciones</label>
                  <div class="col-sm-9">
                    <textarea name="observaciones" id="observaciones" class="form-control" rows="3" placeholder="Ingrese observaciones sobre los cambios realizados (opcional)">{{ old('observaciones', $tarima->observaciones) }}</textarea>
                    <small class="text-muted">Este campo es opcional. Use este espacio para documentar cambios o notas importantes.</small>
                  </div>
                </div>

                <div class="row mb-3">
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">
                          <i class="bi bi-save me-2"></i>GUARDAR
                        </button>
                        <a href="{{ route('tarima.mostrar') }}" class="btn btn-secondary">
                          <i class="bi bi-x-circle me-2"></i>CANCELAR
                        </a>
                    </div>
                </div>

              </form><!-- End General Form Elements -->

            </div>
          </div>

        </div>
      </div>
    </section>
@endsection