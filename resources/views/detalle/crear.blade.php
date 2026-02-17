@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
    <div class="pagetitle">
      <h1>Formulario de Detalle de Recepciones</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"> Detalle de Recepciones</li>
          <li class="breadcrumb-item"><a href="{{ route('detallerecepcion.mostrar') }}">Ver Detalle de Recepciones</a></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <form action="{{ route('detallerecepcion.guardar') }}" method="POST">
              @csrf
                <h5 class="card-title">Crear Detalle de Recepciones</h5>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Folio</label>
                    <input type="hidden" class="form-control" name="idrecepcion" value="{{ $idrecepcion }}" readonly>
                    <input type="text" class="form-control" name="folio" value="{{ $folio }}" readonly>
                  </div>
                </div>
                <table class="table table-bordered" id="detalles-recepcion">
                  <thead>
                    <tr>
                      <th>Hora</th>
                      <th>Cantidad</th>
                      <th>Fruta</th>
                      <th>Tipo Fruta</th>
                      <th>Presentación</th>
                      <th>Tarima</th>
                      <th>Temperatura</th>
                      <th>Tipo</th>
                      <th><i class="bi bi-trash"></i></th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr>
                      <td><input type="time" name="hora[]" class="form-control" required></td>
                      <td><input type="number" name="cantidad[]" min="0"  class="form-control" required></td>
                      <td>
                        <select name="idfruta[]" class="form-select" required>
                          <option value="">Frutas</option>
                          @foreach($frutas as $fruta)
                            <option value="{{ $fruta->id }}">{{ $fruta->nombrefruta }}</option>
                          @endforeach
                        </select>
                      </td>
                      <td>
                        <select name="variedad[]" class="form-select" required>
                          <option value="">Varieación</option>
                          @foreach($variedades as $variedad)
                            <option value="{{ $variedad->tipofruta }}">{{ $variedad->tipofruta }}</option>
                          @endforeach
                        </select>
                      </td>
                      <td>
                        <select name="presentacion[]" class="form-select" required>
                          <option value="">Presentación</option>
                          @foreach($presentaciones as $presentacion)
                            <option value="{{ $presentacion->nombrepresentacion }}">{{ $presentacion->nombrepresentacion }}</option>
                          @endforeach
                        </select>
                      </td>
                     <td>
                        <select name="idtarima[]" class="form-select" required>
                          <option value="">Seleccione</option>
                          @foreach($tarimas as $tarima)
                            <option value="{{ $tarima->id }}">Tarima #{{ $tarima->id }} - Código: {{ $tarima->codigo }}</option>
                          @endforeach
                        </select>
                      </td>


                      <td><input type="number" step="0.1" name="temperatura[]" class="form-control" required></td>
                      <td>
                        <select name="tipo_temperatura[]" class="form-select" required>
                          <option value="">Temperatura</option>
                          <option value="°C">°C</option>
                          <option value="°F">°F</option>
                        </select>
                      </td>
                      <td><button type="button" class="btn btn-danger btn-sm eliminar-fila"><i class="bi bi-trash"></i></button></td>
                    </tr>
                  </tbody>

                </table>
                <div class="mb-3">
                  <button type="button" class="btn btn-secondary" id="agregar-fila">Agregar fila</button>
                </div>
                <div class="mb-3">
                  <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
              </form>
            </div>
          </div>
          @push('scripts')
          <script>
          document.getElementById('agregar-fila').addEventListener('click', function () {
              const tabla = document.querySelector('#detalles-recepcion tbody');
              const filaBase = tabla.rows[0];
              const nuevaFila = filaBase.cloneNode(true);

              // Limpiar los campos de texto (input)
              nuevaFila.querySelectorAll('input').forEach(input => input.value = '');

              // Resetear los selects a su valor por defecto
              nuevaFila.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

              tabla.appendChild(nuevaFila);
          });

          // Eliminar fila con validación de que quede al menos una
          document.querySelector('#detalles-recepcion').addEventListener('click', function (e) {
              if (e.target.classList.contains('eliminar-fila')) {
                  const filas = document.querySelectorAll('#detalles-recepcion tbody tr');
                  if (filas.length > 1) {
                      e.target.closest('tr').remove();
                  } else {
                      alert('Debe haber al menos una fila.');
                  }
              }
          });
          </script>
          @endpush
          

        </div>
      </div>
    </section>
@endsection