@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
    <div class="pagetitle">
      <h1>Editar Cámara</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Cámaras</li>
          <li class="breadcrumb-item"><a href="{{ route('camara.mostrar') }}">Ver Cámaras</a></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Editar Cámara</h5>
              @if ($errors->any())
                <div class="alert alert-danger">
                  <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif

              <!-- General Form Elements -->
              <form method="POST" action="{{ route('camara.update', $camara->id) }}">
                @csrf
                  @method('PUT')
                <div class="col-md-12">
                  <label for="validationDefault04" class="form-label">Cooler</label>
                  <select class="form-select" id="validationDefault04" name="idcooler">
                    @foreach( $coolers as $cooler)
                    <option value="{{ $cooler->id }}" {{ $cooler->id == $camara->idcooler ? 'selected' : '' }}>
                        {{ $cooler->nombrecooler }}
                    </option>
                    @endforeach
                  </select>
                </div>
                 <div class="row mb-3">
                  <label for="inputText" class="col-sm-6 col-form-label">Número</label>
                  <div class="col-sm-12">
                    <input type="text" class="form-control" name="codigo" value="{{ $camara->codigo}}">
                  </div>
                </div>
                <div class="col-md-12">
                  <label for="validationDefault04" class="form-label">Tipo</label>
                  <select class="form-select" id="validationDefault04" name="tipo">
                    <option value="PRE ENFRIADO" {{ $camara->tipo == 'PRE ENFRIADO' ? 'selected' : '' }}>PRE ENFRIADO</option>
                    <option value="CONSERVACIÓN" {{ $camara->tipo == 'CONSERVACIÓN' ? 'selected' : '' }}>CONSERVACIÓN</option>

                  </select>
                </div>
               

                <div class="row mb-3">
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