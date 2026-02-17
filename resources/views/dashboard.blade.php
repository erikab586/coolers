@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Coolers Bonum</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">

            <!-- Sales Card -->
            <div class="col-xxl-4 col-md-4">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title">Sucursales <span></span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-grid-1x2"></i>
                    </div>
                    <div class="ps-3">
                      <h6>{{ $coolers}}</h6>
                      <span class="text-success small pt-1 fw-bold">100%</span> <span class="text-muted small pt-2 ps-1">Registrado</span>

                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->

            <!-- Revenue Card -->
            <div class="col-xxl-4 col-md-4">
              <div class="card info-card revenue-card">

                <div class="card-body">
                  <h5 class="card-title">Comercializadoras <span></span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-shop"></i>
                    </div>
                    <div class="ps-3">
                      <h6>{{ $comercializadoras}}</h6>
                      <span class="text-success small pt-1 fw-bold">100%</span> <span class="text-muted small pt-2 ps-1">Registrado</span>

                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Revenue Card -->

            <!-- Customers Card -->
            <div class="col-xxl-4 col-md-4">

              <div class="card info-card customers-card">
                <div class="card-body">
                  <h5 class="card-title">Contratos</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-people"></i>
                    </div>
                    <div class="ps-3">
                      <h6>{{ $contratos }}</h6>
                      <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span>

                    </div>
                  </div>

                </div>
              </div>

            </div><!-- End Customers Card -->


          </div>
        </div><!-- End Left side columns -->

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
                  <h5 class="card-title">Recepceciones <span>| Todas</span></h5>

                  <table class="table table-borderless datatable">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Folio</th>
                        <th scope="col">Datos clave</th>
                        <th scope="col">Comercializadora</th>
                        <th scope="col">Área</th>
                        <th scope="col">Estatus</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($recepciones as $recepcion)
                      <tr>
                          <th scope="row"><a href="#">#{{ $recepcion->id }}</a></th>
                          <td>{{ $recepcion->folio }}</td>
                          <td><a href="#" class="text-primary">{{ $recepcion->datosclave }}</a></td>
                          <td>{{ $recepcion->area }}</td>
                          <td> <img src="{{ asset($recepcion->contrato->comercializadora->imgcomercializadora ?? 'imagenes/comercializadoras/comercializadora.png') }}" alt="FrutiMax" width="40" height="40" class="rounded-circle me-2">
                            {{ $recepcion->contrato->comercializadora->abreviatura }}</td>
                          <td>
                            @php
                                switch ($recepcion->estatus) {
                                   case 'CON DETALLE':
                                        $badge = 'primary';
                                        $texto = 'Con Detalle';
                                        break;
                                    case 'TARIMA':
                                        $badge = 'info';
                                        $texto = 'En Tarima';
                                        break;
                                    case 'EN PREENFRIADO':
                                        $badge = 'warning';
                                        $texto = 'En Preenfriado';
                                        break;
                                    case 'EN CONSERVACIÓN':
                                        $badge = 'secondary';
                                        $texto = 'En Conservación';
                                        break;
                                    case 'EN CRUCE DE ANDÉN':
                                        $badge = 'warning';
                                        $texto = 'En Cruce de Andén';
                                        break;
                                    case 'EN EMBARQUE':
                                        $badge = 'dark';
                                        $texto = 'En Embarque';
                                        break;
                                    case 'FINALIZADO':
                                        $badge = 'success';
                                        $texto = 'Finalizado';
                                        break;
                                    case 'CANCELADA':
                                        $badge = 'danger';
                                        $texto = 'Cancelada';
                                        break;
                                    default:
                                        $badge = 'light';
                                        $texto = $recepcion->estatus;
                                        break;
                                }
                            @endphp

                            <span class="badge bg-{{ $badge }}">{{ $texto }}</span>
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
                  <h5 class="card-title">Tarimas <span>| Listado</span></h5>
                  <table class="table table-borderless datatable">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Tarima</th>
                        <th scope="col">Cantidad Disponible</th>
                        <th scope="col">Cantidad Usada</th>
                        <th scope="col">Ubicación</th>
                        <th scope="col">Estatus</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($tarimas as $tarima)
                        @php
                          $cantidadUsada = $tarima->tarimaDetarec->sum('cantidadcarga');
                          $capacidadTotal = $tarima->capacidad ?? 0;
                          $cantidadDisponible = max($capacidadTotal - $cantidadUsada, 0);
                        @endphp
                      <tr>
                          <th scope="row"><a href="#">#{{ $tarima->id }}</a></th>
                          <td>{{ $tarima->codigo }}</td>
                          <td>{{ $cantidadDisponible }}</td>
                          <td>{{ $cantidadUsada }}</td>
                          <td> 
                              <span class="badge bg-primary">
                                  @php
                                      $ubicaciones = [
                                          'tarima' => 'TARIMA',
                                          'preenfriado' => 'PREENFRIADO',
                                          'conservacion' => 'CONSERVACIÓN',
                                          'cruce_anden' => 'CRUCE DE ANDÉN',
                                          'embarque' => 'EMBARQUE'
                                      ];
                                      echo $ubicaciones[$tarima->ubicacion] ?? strtoupper($tarima->ubicacion);
                                  @endphp
                              </span>
                          </td>
                          <td>
                              @if($tarima->estatus == 'disponible')
                                  <span class="badge bg-success">Disponible</span>
                              @else
                                  <span class="badge bg-danger">Completo</span>
                              @endif
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