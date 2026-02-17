@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
    <div class="pagetitle">
      <h1>Editar Tipo Empaque</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Tipo Empaque</li>
          <li class="breadcrumb-item"><a href="{{ route('pallets.mostrar') }}">Ver Tipo empaque</a></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Editar Tipo Empaque</h5>

              <!-- General Form Elements -->
              <form method="POST" action="{{ route('pallets.update', $pallet->id) }}">
                @csrf
                <div class="row mb-3">
                  <label for="inputText" class="col-sm-6 col-form-label">Código Empaque</label>
                  <div class="col-sm-12">
                    <input type="text" class="form-control" name="codigopallet" value="{{ $pallet->codigopallet}}">
                  </div>
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