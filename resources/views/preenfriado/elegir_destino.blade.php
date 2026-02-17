@extends('layouts.app')

@section('title', 'Elegir Destino')

@section('content')
<div class="pagetitle">
  <h1>Elegir Destino de la Tarima</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Pre-Enfriado</li>
      <li class="breadcrumb-item"><a href="{{ route('enfrio.mostrar') }}">Volver a Recepciones</a></li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Pre-Enfriado Completado - Seleccione el Siguiente Destino</h5>
          {{-- En elegir_destino.blade.php --}}
          @if(session('success'))
            <script>
              document.addEventListener('DOMContentLoaded', function() {
                  Swal.fire({
                      title: '¡Éxito!',
                      text: '{{ session('success') }}',
                      icon: 'success',
                      confirmButtonText: 'Aceptar'
                  }).then((result) => {
                      @if(session('destino') == 'conservacion')
                          window.location.href = "{{ route('conservacion.editar', $tarima->id) }}";
                      @elseif(session('destino') == 'cruce_anden')
                          window.location.href = "{{ route('cruce_anden.mostrareditar', $tarima->id) }}";
                      @endif
                  });
              });
            </script>
          @endif
          

          <!-- Información de la Tarima -->
          <div class="alert alert-info">
            <h6><i class="bi bi-info-circle me-2"></i>Información de la Tarima</h6>
            <p class="mb-1"><strong>Código:</strong> {{ $tarima->codigo }}</p>
            <p class="mb-1"><strong>Cantidad de Productos:</strong> {{ $tarima->tarimaDetarec->count() }}</p>
            @if($tarima->tarimaDetarec->first() && $tarima->tarimaDetarec->first()->detalle)
              <p class="mb-0"><strong>Comercializadora:</strong> 
                {{ $tarima->tarimaDetarec->first()->detalle->recepcion->contrato->comercializadora->nombrecomercializadora ?? 'N/A' }}
              </p>
            @endif
          </div>

          <form action="{{ route('preenfriado.procesar_destino', $tarima->id) }}" method="POST">
            @csrf

            <div class="row mb-4">
              <div class="col-md-12">
                <label class="form-label"><strong>Seleccione el Destino:</strong></label>
              </div>
            </div>

            <!-- Opción: Conservación -->
            <div class="card mb-3">
              <div class="card-body">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="destino" id="destinoConservacion"
                        value="conservacion" required>
                  <label class="form-check-label" for="destinoConservacion">
                    <h5 class="mb-2"><i class="bi bi-snow2 text-primary"></i> Conservación</h5>
                  </label>
                </div>
                
                <div id="camarasConservacion" style="display: none; margin-left: 25px; margin-top: 10px;">
                  <label class="form-label">Seleccione Cámara de Conservación:</label>
                  @if($camarasConservacion->count() > 0)
                    <select class="form-select" id="selectConservacion">
                      <option value="">Seleccione una cámara</option>
                      @foreach($camarasConservacion as $camara)
                        <option value="{{ $camara->id }}">
                          {{ $camara->codigo }} - {{ $camara->cooler->nombrecooler }}
                          (Capacidad: {{ $camara->capacidadminima }}-{{ $camara->capacidadmaxima }})
                        </option>
                      @endforeach
                    </select>
                  @else
                    <div class="alert alert-warning">
                      <i class="bi bi-exclamation-triangle me-2"></i>
                      No hay cámaras de Conservación disponibles para tus coolers asignados.
                    </div>
                  @endif
                </div>
              </div>
            </div>

            <!-- Opción: Cruce de Andén -->
            <div class="card mb-3">
              <div class="card-body">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="destino" id="destinoCruceAnden"
                        value="cruce_anden" required>
                  <label class="form-check-label" for="destinoCruceAnden">
                    <h5 class="mb-2"><i class="bi bi-truck text-success"></i> Cruce de Andén</h5>
                  </label>
                </div>
                
                <div id="camarasCruceAnden" style="display: none; margin-left: 25px; margin-top: 10px;">
                  <label class="form-label">Seleccione Cámara de Cruce de Andén:</label>
                  @if($camarasCruceAnden->count() > 0)
                    <select  class="form-select"   id="selectCruceAnden">
                      <option value="">Seleccione una cámara</option>
                      @foreach($camarasCruceAnden as $camara)
                        <option value="{{ $camara->id }}">
                          {{ $camara->codigo }} - {{ $camara->cooler->nombrecooler }}
                          (Capacidad: {{ $camara->capacidadminima }}-{{ $camara->capacidadmaxima }})
                        </option>
                      @endforeach
                    </select>
                  @else
                    <div class="alert alert-warning">
                      <i class="bi bi-exclamation-triangle me-2"></i>
                      No hay cámaras de Cruce de Andén disponibles para tus coolers asignados.
                    </div>
                  @endif
                </div>
              </div>
            </div>
           <input type="hidden" name="idcamara" id="idcamaraHidden">

            <div class="mb-3">
              <button type="submit" class="btn btn-primary" id="btnEnviar">
                <i class="bi bi-arrow-right-circle me-2"></i>Continuar
              </button>
              <a href="{{ route('enfrio.mostrar') }}" class="btn btn-secondary">
                <i class="bi bi-x-circle me-2"></i>Cancelar
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function() {
      const radioConservacion  = document.getElementById('destinoConservacion');
      const radioCruceAnden    = document.getElementById('destinoCruceAnden');
      const divConservacion    = document.getElementById('camarasConservacion');
      const divCruceAnden      = document.getElementById('camarasCruceAnden');
      const selectConservacion = document.getElementById('selectConservacion');
      const selectCruceAnden   = document.getElementById('selectCruceAnden');
      const idcamaraHidden     = document.getElementById('idcamaraHidden');

      // Cuando seleccionas CONSERVACIÓN
      radioConservacion.addEventListener('change', function() {
          if (this.checked) {
              divConservacion.style.display = 'block';
              divCruceAnden.style.display   = 'none';
              if (selectCruceAnden) selectCruceAnden.value = "";

              // Si ya hay cámara elegida en conservación, pásala al hidden
              if (selectConservacion && selectConservacion.value) {
                  idcamaraHidden.value = selectConservacion.value;
              } else {
                  idcamaraHidden.value = "";
              }
          }
      });

      // Cuando seleccionas CRUCE DE ANDÉN
      radioCruceAnden.addEventListener('change', function() {
          if (this.checked) {
              divCruceAnden.style.display   = 'block';
              divConservacion.style.display = 'none';
              if (selectConservacion) selectConservacion.value = "";

              // Si ya hay cámara elegida en cruce, pásala al hidden
              if (selectCruceAnden && selectCruceAnden.value) {
                  idcamaraHidden.value = selectCruceAnden.value;
              } else {
                  idcamaraHidden.value = "";
              }
          }
      });

      // Cuando cambias la cámara de CONSERVACIÓN
      if (selectConservacion) {
          selectConservacion.addEventListener('change', function() {
              if (radioConservacion.checked) {
                  idcamaraHidden.value = this.value || "";
              }
          });
      }

      // Cuando cambias la cámara de CRUCE DE ANDÉN
      if (selectCruceAnden) {
          selectCruceAnden.addEventListener('change', function() {
              if (radioCruceAnden.checked) {
                  idcamaraHidden.value = this.value || "";
              }
          });
      }

      // Validación sencilla al enviar (opcional pero recomendado)
      document.querySelector('form').addEventListener('submit', function(e) {
          if (!idcamaraHidden.value) {
              e.preventDefault();
              alert("Por favor, seleccione una cámara antes de continuar.");
              return false;
          }
      });
  });
</script>


@endsection
