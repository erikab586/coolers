@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
    <div class="pagetitle">
      <h1>Tipo Empaque</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active">Tipo Empaque</li>
          <li class="breadcrumb-item"><a href="{{ route('pallets.crear') }}">Crear Empaque</a></li>
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
                  <h5 class="card-title">Tipo Empaque <span>| Todas</span></h5>

                  <table class="table table-borderless datatable">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Código de Empaque</th>
                        <th scope="col">Fecha</th>
                        <th scope="col" style="text-align:center">Acción</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($pallets as $pallet)
                      <tr>
                          <th scope="row"><a href="#">#{{ $pallet->id }}</a></th>
                          <td>{{ $pallet->codigopallet }}</td>
                          <td>{{ $pallet->created_at }}</td>
                          <td>
                            {{-- Botón Editar --}}
                            <a href="{{ route('pallets.editar', $pallet->id) }}" class="btn btn-success" title="Editar">
                              <i class="bi bi-pencil-square"></i>
                            </a>

                            {{-- Botón Eliminar --}}
                            <form action="{{ route('pallets.eliminar', $pallet->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Estás seguro de eliminar esta fruta?');">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger" title="Eliminar">
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