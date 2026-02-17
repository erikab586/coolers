@extends('layouts.app')

@section('title', 'Formulario de EspreEnfrío')

@section('content')
<div class="pagetitle">
  <h1>Formulario de Entrada de Pre-Enfriado </h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Recepción</li>
      <li class="breadcrumb-item"><a href="{{ route('enfrio.mostrar') }}">Volver a Recepciones</a></li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <form class="row g-3" action="{{ route('enfrio.guardardetalle') }}" method="POST">
            @csrf
            <h5 class="card-title">Información de la Recepción</h5>
            <div class="col-md-12">
              <label class="form-label">Selecciona Comercializadora</label>
             <select id="idrecepcion" name="idrecepcion" class="form-select" required>
              <option value="">-- Selecciona --</option>
              @foreach($tarimasAgrupadas as $idRecepcion => $coleccion)
                @php
                  $primeraTarima = $coleccion->first();
                  $recepcion = $primeraTarima->detalle->recepcion ?? null;
                @endphp
                @if($recepcion)
                  <option value="{{ $recepcion->id }}">
                    {{ $recepcion->contrato->comercializadora->nombrecomercializadora }}
                  </option>
                @endif
              @endforeach
            </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Recepción</label>
              <input type="text" class="form-control" name="area" id="area" value="">
            </div>
            <div class="col-md-4">
              <label class="form-label">Área</label>
              <input type="text" class="form-control" name="area" id="area" value="{{ old('area') }}">
            </div>
            <div class="col-md-4">
              <label class="form-label">Clave</label>
              <input type="text" class="form-control" name="clave" id="clave" value="F-BCM-PRO-04" readonly>
            </div>
            <div class="col-md-3">
              <label class="form-label">Fecha Emisión</label>
              <input type="text" class="form-control" name="fechaemision" id="fechaemision" value="{{ old('fechaemision') }}">
            </div>
            <div class="col-md-3">
              <label class="form-label">Comercializadora</label>
              <input type="text" class="form-control" name="cliente" id="cliente" value="">
            </div>
            <div class="col-md-3">
              <label class="form-label">Fecha </label>
              <input type="date" class="form-control" name="fecha" id="fecha" value="{{ old('fecha') }}">
            </div>
            <div class="col-md-3">
              <label class="form-label">Cooler</label>
              <input type="text" class="form-control" name="tipocamara" id="tipocamara" value="" readonly>
            </div>

            <h5 class="card-title">Detalles de Pre Enfriado</h5>

            <table class="table table-bordered" id="tabla-espreenfrio">
              <thead>
                <tr>
                  <th colspan="4" class="text-center">Detalle</th>
                  <th colspan="2" class="text-center">Entrada</th>
                  <th colspan="2" class="text-center">Salida</th>
                  <th rowspan="2" class="text-center align-middle">Total</th>
                </tr>
                <tr>
                  <th class="text-center">Fruta</th>
                  <th class="text-center">Presentación</th>
                  <th class="text-center">Variedad</th>
                  <th class="text-center">Camara</th>
                  <th class="text-center">Hora</th>
                  <th class="text-center">Temp.</th>
                  <th class="text-center">Hora</th>
                  <th class="text-center">Temp.</th>
                </tr>
              </thead>

              <tbody id="detalles-body">
               
                  <tr>
                    <td>
                    <input type="hidden" class="form-control" name="idpreenfriado[]" value= "{{$tarima->preenfriado->id ?? 'No asignada' }}">
                    <input type="hidden" name="iddetalle[]" class="form-control" value="" required>
                    <input type="text" name="fruta[]" class="form-control" value="" required></td>
                    <td><input type="text" name="presentacion[]" class="form-control" value="" required></td>
                    <td><input type="text" name="variedad[]" class="form-control" value="" required></td>
                    <td>
                      <input type="text" class="form-control" name="camara" value= "{{$tarima->preenfriado->camara->codigo ?? 'No asignada' }}">
                    </td>
                    <td><input type="time" name="hora_entrada[]" class="form-control" required></td>
                    <td><input type="number" step="0.1" name="temperatura_entrada[]" class="form-control" required></td>
                    <td><input type="time" name="hora_salida[]" class="form-control" readonly></td>
                    <td><input type="number" step="0.1" name="temperatura_salida[]" class="form-control" readonly></td>
                    <td><input type="text" name="tiempototal[]" class="form-control"></td>
                  </tr>
                
              </tbody>

            </table>

            

            <div class="mb-3">
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script>
      // Datos de PHP
      const recepcionesData = @json($recepcionesData);
      const detallesData = @json($detallesData);

      const selectRecepcion = document.getElementById('idrecepcion');
      const tbody = document.getElementById('detalles-body'); // <tbody> donde van los inputs

      selectRecepcion.addEventListener('change', function() {
          const id = this.value;

          // Completar campos generales
          const datos = recepcionesData[id];
          if (datos) {
              document.getElementById('area').value = datos.area;
              document.getElementById('fechaemision').value = datos.fechaemision;
              document.getElementById('fecha').value = datos.fecha;
              document.getElementById('cliente').value = datos.comercializadora;
              document.getElementById('tipocamara').value = datos.cooler;
          } else {
              document.getElementById('area').value = '';
              document.getElementById('fechaemision').value = '';
              document.getElementById('fecha').value = '';
              document.getElementById('cliente').value = '';
              document.getElementById('tipocamara').value = '';
          }

          // Limpiar tabla
          tbody.innerHTML = '';

          // Llenar tabla con detalles de fruta, presentación y variedad
          const detalles = detallesData[id] || [];
          detalles.forEach(d => {
              const row = document.createElement('tr');

              row.innerHTML = `
                  <td>
                    <input type="hidden" class="form-control" name="idpreenfriado[]" value="${d.idpreenfrio}">
                    <input type="hidden" name="iddetalle[]" class="form-control" value="${d.id}" required>
                    <input type="text" name="fruta[]" class="form-control" value="${d.fruta}" required>
                  </td>
                  <td><input type="text" name="presentacion[]" class="form-control" value="${d.presentacion}" required></td>
                  <td><input type="text" name="variedad[]" class="form-control" value="${d.variedad}" required></td>
                  <td>
                      <input type="text" class="form-control" name="camara" value= "{{$tarima->preenfriado->camara->codigo ?? 'No asignada' }}">
                    </td>
                    <td><input type="time" name="hora_entrada[]" class="form-control" required></td>
                    <td><input type="number" step="0.1" name="temperatura_entrada[]" class="form-control" required></td>
                    <td><input type="time" name="hora_salida[]" class="form-control" readonly></td>
                    <td><input type="number" step="0.1" name="temperatura_salida[]" class="form-control" readonly></td>
                    <td><input type="text" name="tiempototal[]" class="form-control"></td>
                  `;

              tbody.appendChild(row);
          });
      });
  </script>



</section>
@endsection
