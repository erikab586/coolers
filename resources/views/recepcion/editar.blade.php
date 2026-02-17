@extends('layouts.app')

@section('title', 'Editar Recepción')

@section('content')
<div class="pagetitle">
  <h1>Editar Recepción</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('recepcion.mostrar') }}">Recepciones</a></li>
      <li class="breadcrumb-item active">Editar</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Formulario de Edición</h5>

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form class="row g-3" method="POST" action="{{ route('recepcion.update', $recepcion->id) }}">
        @csrf
        @method('PUT')

        <input type="hidden" name="idcontrato" value="{{ $recepcion->idcontrato }}">

        {{-- Clave --}}
        <div class="col-md-4">
          <label class="form-label">Clave</label>
          <select name="datosclave" class="form-control">
            <option value="">Seleccione una clave</option>
            <option value="F-BCM-PRO-01" {{ $recepcion->datosclave == 'F-BCM-PRO-01' ? 'selected' : '' }}>F-BCM-PRO-01</option>
            <option value="F-BCM-PRO-02" {{ $recepcion->datosclave == 'F-BCM-PRO-02' ? 'selected' : '' }}>F-BCM-PRO-02</option>
            <option value="F-BCM-PRO-10" {{ $recepcion->datosclave == 'F-BCM-PRO-10' ? 'selected' : '' }}>F-BCM-PRO-10</option>
          </select>
        </div>

        {{-- Área --}}
        <div class="col-md-4">
          <label class="form-label">Área</label>
          <input type="text" name="area" class="form-control" value="{{ $recepcion->area }}">
        </div>

        {{-- Revisión --}}
        <div class="col-md-4">
          <label class="form-label">Revisión</label>
          <input type="text" name="revision" class="form-control" value="{{ $recepcion->revision }}">
        </div>

        {{-- Fecha Emisión --}}
        <div class="col-md-4">
          <label class="form-label">Fecha Emisión</label>
          <input type="date" name="fechaemision" class="form-control" value="{{ $recepcion->fechaemision }}">
        </div>

        {{-- Comercializadora --}}
        <div class="col-md-4">
          <label class="form-label">Comercializadora</label>
          <input type="text" class="form-control" value="{{ $recepcion->contrato->comercializadora->nombrecomercializadora ?? '—' }}" readonly>
        </div>

        {{-- Usuario --}}
        <div class="col-md-4">
          <label class="form-label">Usuario</label>
          <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
          <input type="hidden" name="idusuario" value="{{ auth()->user()->id }}">
        </div>

        {{-- Folio --}}
        <div class="col-md-4">
          <label class="form-label">Folio</label>
          <input type="text" name="folio" class="form-control" value="{{ $recepcion->folio }}" readonly>
        </div>

        {{-- Detalle de Recepción --}}
        <div class="col-md-12">
          <h5 class="card-title">Detalle de Recepción</h5>
        </div>

        <table class="table table-bordered" id="detalles-recepcion">
          <thead>
            <tr>
              <th>Hora</th>
              <th>Cantidad</th>
              <th>Fruta</th>
              <th>Tipo Fruta</th>
              <th>Presentación</th>
              <th>Temperatura</th>
              <th>Tipo</th>
              <th><i class="bi bi-trash"></i></th>
            </tr>
          </thead>
          <tbody>
            @foreach($recepcion->detalles as $detalle)
              <tr>
                <input type="hidden" name="detalle_id[]" value="{{ $detalle->id }}">
                <td><input type="time" name="hora[]" class="form-control" value="{{ $detalle->hora }}" required></td>
                <td>
                  <input type="number" name="cantidad[]" class="form-control" value="{{ $detalle->cantidad }}" required>
                  
                </td>
                <td>
                  <select name="idfruta[]" class="form-select select-fruta" required>
                    @foreach($frutas as $fruta)
                      <option value="{{ $fruta->id }}"
                              data-imagen="{{ asset($fruta->imgfruta ?? 'imagenes/frutas/frutas.png') }}"
                              {{ $detalle->idfruta == $fruta->id ? 'selected' : '' }}>
                        {{ $fruta->nombrefruta }}
                      </option>
                    @endforeach
                  </select>
                </td>
                <td>
                  <select name="variedad[]" class="form-select" required>
                    @foreach($variedades as $variedad)
                      <option value="{{ $variedad->id }}" {{ $detalle->idvariedad == $variedad->id ? 'selected' : '' }}>{{ $variedad->tipofruta }}</option>
                    @endforeach
                  </select>
                </td>
                <td>
                  <select name="presentacion[]" class="form-select" required>
                    @foreach($presentaciones as $presentacion)
                      <option value="{{ $presentacion->id }}" {{ $detalle->idpresentacion == $presentacion->id ? 'selected' : '' }}>{{ $presentacion->nombrepresentacion }}</option>
                    @endforeach
                  </select>
                </td>
                <td><input type="number" step="0.1" name="temperatura[]" class="form-control" value="{{ $detalle->temperatura }}" required></td>
                <td>
                  <input type="text" class="form-control" name="tipo_temperatura[]" value="{{ $detalle->tipo }}" readonly>
                </td>
                <td><button type="button" class="btn btn-danger btn-sm eliminar-fila"><i class="bi bi-trash"></i></button></td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <div class="mb-3">
          <button type="button" class="btn btn-secondary" id="agregar-fila">Agregar fila</button>
        </div>

        {{-- Observaciones --}}
        <div class="col-md-12">
          <label class="form-label">Observaciones</label>
          <textarea name="observaciones" class="form-control" rows="3" placeholder="Ingrese observaciones sobre los cambios realizados (opcional)">{{ old('observaciones', $recepcion->observaciones) }}</textarea>
          <small class="text-muted">Este campo es opcional. Use este espacio para documentar cambios o notas importantes.</small>
        </div>

        {{-- Botones --}}
        <div class="col-md-12 text-center mt-3">
          <button type="submit" class="btn btn-success">ACTUALIZAR</button>
          <a href="{{ route('recepcion.mostrar') }}" class="btn btn-secondary">CANCELAR</a>
        </div>

      </form>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
  // Misma lógica de agregar/eliminar fila
  document.getElementById('agregar-fila').addEventListener('click', function () {
    const tabla = document.querySelector('#detalles-recepcion tbody');
    const filaBase = tabla.rows[0];
    const nuevaFila = filaBase.cloneNode(true);

    nuevaFila.querySelectorAll('input').forEach(input => input.value = '');
    nuevaFila.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
    tabla.appendChild(nuevaFila);
  });

  document.querySelector('#detalles-recepcion').addEventListener('click', function (e) {
    if (e.target.closest('.eliminar-fila')) {
      const filas = document.querySelectorAll('#detalles-recepcion tbody tr');
      if (filas.length > 1) {
        e.target.closest('tr').remove();
      } else {
        alert('Debe haber al menos una fila.');
      }
    }
  });

document.addEventListener('DOMContentLoaded', () => {

    function formatFruta (fruta) {
        if (!fruta.id) return fruta.text; // placeholder
        let img = $(fruta.element).data('imagen');

        return $(`
            <span style="display:flex; align-items:center; gap:8px;">
                <img src="${img}" style="width:30px; height:30px; object-fit:contain;">
                ${fruta.text}
            </span>
        `);
    }

    function initSelectFruta() {
        $('.select-fruta').select2({
            width: '100%',
            theme: 'bootstrap-5',
            templateResult: formatFruta,
            templateSelection: formatFruta,
            escapeMarkup: m => m
        });
    }

    initSelectFruta();
});
</script>
@endpush

