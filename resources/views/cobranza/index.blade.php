@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
    <div class="pagetitle">
      <h1>Cobranzas</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active">Cobranzas</li>
          @if(hasPermission('crear_contratos'))
            <li class="breadcrumb-item"><a href="{{ route('contrato.crear') }}">Cobranza</a></li>
          @endif
        </ol>
      </nav>
    </div><!-- End Page Title -->
    @if(session('success'))
      <div id="success-alert" class="alert alert-success">
        {{ session('success') }}
      </div>
      <script>
        setTimeout(function () {
          const alertBox = document.getElementById('success-alert');
          if (alertBox) {
            alertBox.style.transition = "opacity 0.5s ease";
            alertBox.style.opacity = 0;
            setTimeout(() => alertBox.remove(), 500);
          }
        }, 5000); // 5 segundos
      </script>
    @endif
    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">
            <!-- Recent Sales -->
            <div class="col-12">
              <div class="card recent-sales overflow-auto">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Cobranzas <span>| Todas</span></h5>

                  <table class="table table-borderless datatable">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Comercializadora</th>
                        <th scope="col">Contador</th>
                        <th scope="col">Cooler</th>
                        <th scope="col">Fecha Contrato</th>
                        @if(hasAnyPermission(['editar_contratos', 'eliminar_contratos']))
                          <th scope="col" style="text-align:center">Acción</th>
                        @endif
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($contratos as $contrato)
                      <tr>
                            <th scope="row"><a href="#">#{{ $contrato->id }}</a></th>
                            <td><a href="#" class="text-primary">{{ $contrato->tipocontrato }}</a></td>
                            <td>
                                <img src="{{ asset($contrato->comercializadora->imgcomercializadora) }}" alt="FrutiMax" width="40" height="40" class="rounded-circle me-2">
                                {{ $contrato->comercializadora->nombrecomercializadora }}
                            </td>
                            <td>{{ $contrato->users->name }} {{ $contrato->users->apellidos }}</td>
                            <td>{{ $contrato->cooler->nombrecooler }}</td>
                            <td>{{ $contrato->fechacontrato }}</td>
                            <td>
                                <a href="{{ route('cobranza.pendiente', $contrato->id) }}" class="btn btn-sm btn-danger" title="Cobro Pendiente">
                                <i class="bi bi-pencil-square"></i>
                                <a href="{{ route('cobranza.mostrar', $contrato->id) }}" class="btn btn-sm btn-success" title="Ver Cobranzas">
                                <i class="bi bi-arrow-repeat"></i>
                                </a>
                                </a>
                            </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>

                </div>

              </div>
            </div><!-- End Recent Sales -->

          </div>
        </div><!-- End Left side columns -->
      </div>
    </section>
@endsection