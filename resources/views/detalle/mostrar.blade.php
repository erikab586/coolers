@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
    <div class="pagetitle">
      <h1>Detalle de Recepciones</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active">Detalle de Recepciones</li>
          <li class="breadcrumb-item"><a href="{{ route('recepcion.crear') }}">Crear Detalle de Recepción</a></li>
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
                  <h5 class="card-title">Detalle de Recepceciones <span>| Todas</span></h5>

                  <table class="table table-borderless datatable">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Folio</th>
                        <th scope="col">Fruta</th>
                        <th scope="col">Variedad</th>
                        <th scope="col" style="text-align:center">Acción</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($detalles as $detalle)
                      <tr>
                          <th scope="row"><a href="#">#{{ $detalle->id }}</a></th>
                          <td>{{ $detalle->folio }}</td>
                          <td><a href="#" class="text-primary">{{ $detalle->fruta->nombrefruta }}</a></td>
                          <td>{{ $detalle->variedad }}</td>
                          <td>
                            {{-- Botón Editar --}}
                            <a href="{{ route('detallerecepcion.editar', $detalle->id) }}" class="btn btn-sm btn-success" title="Editar">
                              <i class="bi bi-pencil-square"></i>
                            </a>

                            {{-- Botón Eliminar --}}
                            <form action="{{ route('detallerecepcion.eliminar', $detalle->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Estás seguro de eliminar este Detalle?');">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                <i class="bi bi-trash"></i>
                              </button>
                            </form>

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