@extends('layouts.app')

@section('title', 'Detalle de Cobranza')

@section('content')
    <div class="pagetitle">
        <h1>Detalle de Cobranza</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('cobranza') }}">Cobranza</a></li>
                <li class="breadcrumb-item active">Detalle</li>
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
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card recent-sales overflow-auto">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title">{{ $comercializadora->nombrecomercializadora ?? '' }} <span>|Reporte de Cobranza </span></h5>
                                    @if(isset($comercializadora))
                                        <a href="{{ route('cobranza.porcomercializadora.pdf', $comercializadora->id) }}" 
                                           class="btn btn-danger" 
                                           target="_blank"
                                           title="Descargar PDF">
                                            <i class="bi bi-file-pdf"></i> Descargar PDF
                                        </a>
                                    @endif
                                </div>

                                @if(isset($cobranzas) && count($cobranzas) > 0)
                                    <table class="table table-borderless datatable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Folio</th>
                                                <th>Fruta</th>
                                                <th>Presentación</th>
                                                <th>Variedad</th>
                                                <th>Cantidad</th>
                                                <th>Tiempo Pre.</th>
                                                <th>Tiempo Cons.</th>
                                                <th>Tiempo Anden</th>
                                                <th>Monto Pre.</th>
                                                <th>Monto Cons.</th>
                                                <th>Monto Anden</th>
                                                <th>Subtotal Pre.</th>
                                                <th>Subtotal Cons.</th>
                                                <th>Subtotal Anden</th>
                                                <th>Fecha Rec.</th>
                                                <th>Estatus</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cobranzas as $index => $cobranza)
                                                <tr>
                                                    <th scope="row">{{ $index + 1 }}</th>
                                                    <td><span class="badge bg-success">{{ $cobranza->folio }}</span></td>
                                                    <td>{{ $cobranza->fruta }}</td>
                                                    <td>{{ $cobranza->presentacion }}</td>
                                                    <td>{{ $cobranza->variedad }}</td>
                                                    <td>{{ $cobranza->cantidad }}</td>
                                                    <td>{{ number_format($cobranza->tiempo_preenfriado , 2) }} hrs</td>
                                                    <td>{{ number_format($cobranza->tiempo_conservacion , 2) }} hrs</td>
                                                    <td>{{ number_format($cobranza->tiempo_anden , 2) }} hrs</td>
                                                    <td>${{ number_format($cobranza->monto_preenfriado, 2) }}</td>
                                                    <td>${{ number_format($cobranza->monto_conservacion, 2) }}</td>
                                                    <td>${{ number_format($cobranza->monto_anden, 2) }}</td>
                                                    <td>${{ number_format($cobranza->subtotal_preenfriado, 2) }}</td>
                                                    <td>${{ number_format($cobranza->subtotal_conservacion, 2) }}</td>
                                                    <td>${{ number_format($cobranza->subtotal_anden, 2) }}</td>
                                                    <td>{{ $cobranza->fecha_recepcion ? $cobranza->fecha_recepcion->format('d/m/Y') : 'N/A' }}</td>
                                                    <td>
                                                        @if($cobranza->estatus == 'PAGADA')
                                                            <span class="badge bg-success">PAGADA</span>
                                                        @else
                                                            <span class="badge bg-warning">PENDIENTE</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('cobranza.verdetalle', $cobranza->folio) }}" class="btn btn-dark btn-sm"><i class="bi bi-eye"></i></a>
                                                        <a href="{{ route('cobranza.cambiarestatus', $cobranza->id) }}" class="btn btn-danger btn-sm"><i class="bi bi-arrow-repeat"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-info">
                                                <th colspan="14" class="text-end">Total:</th>
                                                <th colspan="3">
                                                    <strong>
                                                        ${{ number_format(
                                                            $cobranzas->sum('subtotal_preenfriado') +
                                                            $cobranzas->sum('subtotal_conservacion') +
                                                            $cobranzas->sum('subtotal_anden'),
                                                        2) }}
                                                    </strong>
                                                </th>
                                                <th colspan="5"></th>
                                            </tr>
                                            <tr class="table-warning">
                                                <th colspan="14" class="text-end">Pendientes de Pago:</th>
                                                <th colspan="3">
                                                    <strong>
                                                        ${{ number_format(
                                                            $cobranzas->where('estatus', 'PENDIENTE')->sum(function($c) {
                                                                return ($c->subtotal_preenfriado ?? 0)
                                                                    + ($c->subtotal_conservacion ?? 0)
                                                                    + ($c->subtotal_anden ?? 0);
                                                            }),
                                                        2) }}
                                                    </strong>
                                                </th>
                                                <th colspan="5"></th>
                                            </tr>
                                            <tr class="table-success">
                                                <th colspan="14" class="text-end">Pagadas:</th>
                                                <th colspan="3">
                                                    <strong>
                                                        ${{ number_format(
                                                            $cobranzas->where('estatus', 'PAGADA')->sum(function($c) {
                                                                return ($c->subtotal_preenfriado ?? 0)
                                                                    + ($c->subtotal_conservacion ?? 0)
                                                                    + ($c->subtotal_anden ?? 0);
                                                            }),
                                                        2) }}
                                                    </strong>
                                                </th>
                                                <th colspan="5"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                @else
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-1"></i>
                                        No hay registros de cobranza para este contrato. Las cobranzas se generan automáticamente al crear una embarcación.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div><!-- End Recent Sales -->
                </div>
            </div>
        </div>
    </section>
@endsection
