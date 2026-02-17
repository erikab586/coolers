@extends('layouts.app')

@section('title', 'Formulario de Cámara')

@section('content')
<div class="pagetitle">
  <h1>Formulario de Cámara</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Cámara</li>
      <li class="breadcrumb-item"><a href="{{ route('camara.mostrar') }}">Ver Cámaras</a></li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
          @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="bi bi-exclamation-triangle me-1"></i>
              {{ session('error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif
          
          @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="bi bi-check-circle me-1"></i>
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif
          
          <form action="{{ route('camara.guardar') }}" method="POST">
            @csrf
            <h5 class="card-title">Crear Cámara</h5>

            <div class="col-md-12 mb-3">
              <label class="form-label">Coolers</label>
              <select name="idcooler" class="form-select" required>
                <option value="">Seleccione un Cooler</option>
                @foreach($coolers as $cooler)
                  <option value="{{ $cooler->id }}" {{ old('idcooler') == $cooler->id ? 'selected' : '' }}>
                    {{ $cooler->nombrecooler }}
                  </option>
                @endforeach
              </select>
            </div>

            <table class="table table-bordered" id="detalles-recepcion">
              <thead>
                <tr>
                  <th>Número</th>
                  <th>Tipo</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><input type="text" class="form-control" name="codigo[]" required></td>
                  <td>
                    <select name="tipo[]" class="form-control" required>
                      <option value="">Seleccione tipo</option>
                      <option value="PRE ENFRIADO">PRE ENFRIADO</option>
                      <option value="CONSERVACIÓN">CONSERVACIÓN</option>
                      <option value="CRUCE DE ANDÉN">CRUCE DE ANDÉN</option>
                    </select>
                  </td>
                  <td><button type="button" class="btn btn-danger btn-sm eliminar-fila">Eliminar</button></td>
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

          nuevaFila.querySelectorAll('input').forEach(input => input.value = '');
          nuevaFila.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

          tabla.appendChild(nuevaFila);
        });

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
