@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="pagetitle">
  <h1>Formulario de Recepciones</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Recepciones</li>
      <li class="breadcrumb-item"><a href="{{ route('recepcion.mostrar') }}">Ver Recepciones</a></li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Crear Recepciones</h5>
          @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Éxito',
                        text: '{{ session('success') }}',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Aquí rediriges a otra ventana/página
                            window.location.href = "{{ route('recepcion.mostrar') }}";
                        }
                    });
                });
            </script>
           @endif
          @if ($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Errores de validación',
                        html: `{!! implode('<br>', $errors->all()) !!}`,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            </script>
          @endif

          <form class="row g-3" action="{{ route('recepcion.guardar') }}" method="POST">
            @csrf
            <input type="hidden" name="idcontrato" value="{{ $contratos->id }}">
            {{-- Clave --}}
            <div class="col-md-4">
              <label class="form-label">Clave</label>
              <select name="datosclave" class="form-control @error('datosclave') is-invalid @enderror">
                <option value="">Seleccione una clave</option>
                <option value="F-BCM-PRO-01">Frutas Convencionales: F-BCM-PRO-01</option>
                <option value="F-BCM-PRO-02">Frutas Órganicas: F-BCM-PRO-02</option>
                <option value="F-BCM-PRO-10">Hortalizas: F-BCM-PRO-10</option>
                <!-- Agregá aquí las demás opciones -->
              </select>
              @error('datosclave')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Área --}}
            <div class="col-md-4">
              <label class="form-label">Área</label>
              <input type="text" class="form-control @error('area') is-invalid @enderror" name="area" value="Recepción" readonly>
              @error('area')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Revisión --}}
            <div class="col-md-4">
              <label class="form-label">Revisión</label>
              <input type="text" class="form-control @error('revision') is-invalid @enderror" name="revision" value="01" readonly>
              @error('revision')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Fecha de Emisión --}}
            <div class="col-md-4">
              <label class="form-label">Fecha Emisión</label>
              <input type="date" class="form-control @error('fechaemision') is-invalid @enderror" name="fechaemision" value="{{ old('fechaemision', date('Y-m-d')) }}">
              @error('fechaemision')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            {{-- Comercializadora --}}
            <div class="col-md-4">
                <label class="form-label">Comercializadora</label>
                <input type="text" class="form-control" value="{{ $contratos->comercializadora->nombrecomercializadora }}" readonly>
                <input type="hidden" name="idusuario" value="{{ $contratos->comercializadora->id }}">
            </div>
            {{-- Usuario --}}
               <div class="col-md-4">
                <label class="form-label">Usuario</label>
                <input type="text" class="form-control" value="{{ auth()->user()->name }} {{ auth()->user()->apellidos }}" readonly>
                <input type="hidden" name="idusuario" value="{{ auth()->user()->id }}">
              </div>

            {{-- Folio --}}
            <div class="col-md-4">
              <label class="form-label">Folio</label>
              <input type="text" class="form-control @error('folio') is-invalid @enderror" name="folio"  value="{{ $folio }}" readonly>
              @error('folio')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-12">
              <h5 class="card-title">Detalle de Contrato</h5>
            </div>
              <table class="table table-bordered" id="detalles-recepcion">
                  <thead>
                     <tr>
                      <th style="width:10%;">Hora</th>
                      <th style="width:8%;">Cantidad</th>
                      <th style="width:30%;">Fruta</th>
                      <th style="width:15%;">Tipo Fruta</th>
                      <th style="width:17%;">Presentación</th>
                      <th style="width:8%;">Temperatura</th>
                      <th style="width:7%;">Tipo</th>
                      <th style="width:5%;"><i class="bi bi-trash"></i></th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr>
                      <td><input type="time" name="hora[]" class="form-control" value="{{ date('H:i') }}" ></td>
                      <td><input type="number" name="cantidad[]" min="0"  class="form-control" required></td>
                      <td>
                        <div class="d-flex align-items-center">
                          <select name="idfruta[]" class="form-select select-fruta" required>
                            <option value="">Frutas</option>
                            @foreach($frutas as $fruta)
                              <option value="{{ $fruta->id }}"
                                      data-imagen="{{ asset($fruta->imgfruta ?? 'imagenes/frutas/frutas.png') }}">
                                {{ $fruta->nombrefruta }}
                              </option>
                            @endforeach
                          </select>
                        </div>
                      </td>
                      <td>
                        <select name="variedad[]" class="form-select" required>
                          <option value="">Variación</option>
                          @foreach($variedades as $variedad)
                            <option value="{{ $variedad->id }}">{{ $variedad->tipofruta }}</option>
                          @endforeach
                        </select>
                      </td>
                      <td>
                        <select name="presentacion[]" class="form-select" required>
                          <option value="">Presentación</option>
                          @foreach($presentaciones as $presentacion)
                            <option value="{{ $presentacion->id }}">{{ $presentacion->nombrepresentacion }}</option>
                          @endforeach
                        </select>
                      </td>
                      <td><input type="number" step="0.1" name="temperatura[]" class="form-control" value="25" readonly></td>
                      <td>
                        <input type="text" name="tipo_temperatura[]" class="form-control" value="°C" readonly>
                      </td>
                      <td><button type="button" class="btn btn-danger btn-sm eliminar-fila"><i class="bi bi-trash"></i></button></td>
                    </tr>
                  </tbody>
                </table>
                
                <div class="mb-3">
                  <button type="button" class="btn btn-secondary" id="agregar-fila">Agregar fila</button>
                </div>
            {{-- Botones --}}
            <div class="col-md-12 text-center">
              <button type="submit" class="btn btn-primary">GUARDAR</button>
              <button type="reset" class="btn btn-secondary">CANCELAR</button>
            </div>
          </form>

        </div>
      </div>

    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
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
      theme: 'bootstrap-5',            // IMPORTANTE
      templateResult: formatFruta,
      templateSelection: formatFruta,
      escapeMarkup: m => m
  });
}


    initSelectFruta();



    /* -----------------------------
       3. AGREGAR FILA Y RE-INICIALIZAR SELECT2
       ----------------------------- */
    document.getElementById('agregar-fila').addEventListener('click', function () {

        const tabla = document.querySelector('#detalles-recepcion tbody');
        const filaBase = tabla.rows[0];
        const nuevaFila = filaBase.cloneNode(true);

        // Resetear inputs
        nuevaFila.querySelectorAll('input').forEach(input => {
            if (input.type === 'time') {
                const ahora = new Date();
                input.value = ahora.toISOString().slice(11,16);
            } else if (input.name === 'temperatura[]') {
                input.value = 25;
            } else if (input.name === 'tipo_temperatura[]') {
                input.value = '°C';
            } else {
                input.value = "";
            }
        });

        // Reset selects
        nuevaFila.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

        // Eliminar contenedores de Select2 antes de reactivarlo
        $(nuevaFila).find(".select2-container").remove();

        // Insertar fila
        tabla.appendChild(nuevaFila);

        // ACTIVAR SELECT2 DE NUEVO
        initSelectFruta();
    });



    /* -----------------------------
       4. ELIMINAR FILA
       ----------------------------- */
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

});
</script>
@endpush

