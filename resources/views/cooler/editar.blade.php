@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
    <div class="pagetitle">
      <h1>Formulario de Sucursal</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Sucursal</li>
          <li class="breadcrumb-item"><a href="{{ route('cooler.mostrar') }}">Ver Sucursal</a></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Editar Sucursal</h5>

              <!-- TIPOS DE COOLER -->
              @php
                  $tiposCooler = ['Preenfrio', 'Conservación'];
              @endphp

              <!-- General Form Elements -->
              <form class="row g-3" method="POST" action="{{ route('cooler.update', ['cooler' => $cooler->id])}}">
                @csrf
                <div class="col-md-6">
                  <label for="validationDefault03" class="form-label">Nombre</label>
                  <input type="text" class="form-control" id="validationDefault03" name="nombrecooler" value="{{ $cooler->nombrecooler}}">
                </div>
                <div class="col-md-6">
                  <label for="validationDefault03" class="form-label">Código</label>
                  <input type="text" class="form-control" id="validationDefault03" name="codigoidentificador" value="{{ $cooler->codigoidentificador}}" >
                </div>
                <div class="col-md-12">
                  <label for="validationDefault03" class="form-label">Ubicación</label>
                  <input type="text" class="form-control" id="validationDefault03" name="ubicacion" value="{{ $cooler->ubicacion}}">
                </div>
                
                <div class="col md-12">
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">GUARDAR</button>
                        <button type="reset" class="btn btn-secondary">CANCELAR</button>
                    </div>
                </div>

              </form><!-- End General Form Elements -->

            </div>
          </div>

        </div>
      </div>
    </section>
@endsection