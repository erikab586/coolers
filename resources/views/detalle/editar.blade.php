@extends('layouts.app')

@section('title', 'Editar Detalle de Recepción')

@section('content')
<div class="pagetitle">
  <h1>Editar Detalle de Recepción</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Recepciones</li>
      <li class="breadcrumb-item"><a href="{{ route('detallerecepcion.mostrar') }}">Ver Detalles</a></li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Editar Detalle</h5>

          <form class="row g-3" action="{{ route('detallerecepcion.update', $detallerecepcion->id) }}" method="POST">
            @csrf
              @method('PUT')
              <input type="hidden" name="idrecepcion" value="{{ old('idrecepcion', $detallerecepcion->idrecepcion) }}" >
            <div class="col-md-6">
              <label class="form-label">Folio</label>
              <input type="text" class="form-control @error('folio') is-invalid @enderror" name="folio" value="{{ old('folio', $detallerecepcion->folio) }}" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Hora</label>
              <input type="time" class="form-control" name="hora" value="{{ old('hora', $detallerecepcion->hora) }}" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Temperatura</label>
              <input type="text" class="form-control" name="temperatura" value="{{ old('temperatura', $detallerecepcion->temperatura) }}" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Tipo de Temperatura</label>
              <input type="text" class="form-control" name="tipo_temperatura" value="{{ old('tipo_temperatura', $detallerecepcion->tipo_temperatura) }}" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Presentación</label>
              <select name="presentacion" class="form-select" required>
                @foreach ($presentaciones as $p)
                  <option value="{{ $p->nombrepresentacion }}" {{ $p->nombrepresentacion == $detallerecepcion->presentacion ? 'selected' : '' }}>
                    {{ $p->nombrepresentacion }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Fruta</label>
              <select name="idfruta" class="form-select" required>
                @foreach ($frutas as $fruta)
                  <option value="{{ $fruta->id }}" {{ $fruta->id == $detallerecepcion->idfruta ? 'selected' : '' }}>
                    {{ $fruta->nombrefruta }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Variedad</label>
              <select name="variedad" class="form-select" required>
                @foreach ($variedades as $variedad)
                  <option value="{{ $variedad->tipofruta }}" {{ $variedad->tipofruta == $detallerecepcion->variedad ? 'selected' : '' }}>
                    {{ $variedad->tipofruta }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Cantidad</label>
              <input type="number" class="form-control" name="cantidad" step="0.01" value="{{ old('cantidad', $detallerecepcion->cantidad) }}" required>
            </div>

            <div class="col-12">
              <button type="submit" class="btn btn-primary">Actualizar</button>
              <a href="{{ route('detallerecepcion.mostrar') }}" class="btn btn-secondary">Cancelar</a>
            </div>

          </form>
        </div>
      </div>

    </div>
  </div>
</section>
@endsection
