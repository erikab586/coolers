@extends('layouts.app')

@section('title', 'Cobranza Pendiente')

@section('content')
    <div class="pagetitle">
        <h1>Cobranza Pendiente - {{ $contrato->comercializadora->nombre }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cobranza') }}">Cobranzas</a></li>
                <li class="breadcrumb-item active">Pendiente</li>
            </ol>
        </nav>
    </div>

    @if(session('success'))
        <div id="success-alert" class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Detalle de Tarimas para Cobro</h5>
                        
                        @if($tarimas->isEmpty())
                            <div class="alert alert-info">
                                No hay tarimas pendientes de cobro para este contrato.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover datatable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Tarima ID</th>
                                            <th>Folio</th>
                                            <th>Fruta</th>
                                            <th>Variedad</th>
                                            <th>Presentación</th>
                                            <th class="text-end">Cantidad Total</th>
                                            <th class="text-end">Preenfriado</th>
                                            <th class="text-end">Cruce Andén</th>
                                            <th class="text-end">Conservación</th>
                                            <th>Fecha Ingreso</th>
                                            <th>Fecha Salida</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tarimas as $index => $tarima)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $tarima['id'] }}</td>
                                                <td>{{ $tarima['folio'] }}</td>
                                                <td>{{ $tarima['fruta'] }}</td>
                                                <td>{{ $tarima['variedad'] }}</td>
                                                <td>{{ $tarima['presentacion'] }}</td>
                                                <td class="text-end fw-bold">{{ number_format($tarima['cantidad'], 0) }}</td>
                                                <td class="text-end">{{ number_format($tarima['tiempo_preenfriado'], 0) }}</td>
                                                <td class="text-end">{{ number_format($tarima['tiempo_anden'], 0) }}</td>
                                                <td class="text-end">{{ number_format($tarima['tiempo_conservacion'], 0) }}</td>
                                                <td>{{ \Carbon\Carbon::parse($tarima['fecha_ingreso'])->format('d/m/Y') }}</td>
                                                 <td>{{ \Carbon\Carbon::parse($tarima['fecha_salida'])->format('d/m/Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-active">
                                            <th colspan="6" class="text-end">TOTALES:</th>
                                            <th class="text-end">{{ number_format($tarimas->sum('cantidad'), 0) }}</th>
                                            <th class="text-end">{{ number_format($tarimas->sum('tiempo_preenfriado'), 0) }}</th>
                                            <th class="text-end">{{ number_format($tarimas->sum('tiempo_anden'), 0) }}</th>
                                            <th class="text-end">{{ number_format($tarimas->sum('tiempo_conservacion'), 0) }}</th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="mt-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <button class="btn btn-success" onclick="generarCobroMultiple()">
                                        <i class="bi bi-credit-card me-1"></i> Generar Cobro Masivo
                                    </button>
                                </div>
                                <div>
                                    <a href="{{ route('cobranza') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left me-1"></i> Volver
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .table th {
        white-space: nowrap;
        vertical-align: middle;
    }
    .table td {
        vertical-align: middle;
    }
    .table tfoot th {
        background-color: #f8f9fa;
        font-weight: bold;
    }
</style>
@endpush

@push('scripts')
<script>

   function generarCobroMultiple() {
    if (confirm('¿Desea generar el cobro para todas las tarimas mostradas?')) {
        // Convertir los IDs a números enteros
        const tarimaIds = @json($tarimas->pluck('id')).map(id => parseInt(id));
        const url = "{{ route('cobranza.crear.multiple') }}";
        
        console.log('URL de la petición:', url);
        console.log('Tarima IDs (tipo y valor):', tarimaIds, 'Tipo:', typeof tarimaIds[0]);

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                tarima_ids: tarimaIds
            })
        })
        .then(handleResponse)
        .then(handleSuccess)
        .catch(handleError);
    }
}

function handleResponse(response) {
    console.log('Status:', response.status, response.statusText);
    return response.json().then(data => {
        if (!response.ok) {
            const error = new Error(data.message || 'Error en la petición');
            error.response = data;
            error.status = response.status;
            throw error;
        }
        return data;
    });
}

function handleSuccess(data) {
    console.log('Respuesta exitosa:', data);
    if (data.exito) {
        alert(data.mensaje || 'Cobros generados exitosamente');
        window.location.reload();
    } else {
        throw new Error(data.mensaje || 'Error al procesar la respuesta');
    }
}

function handleError(error) {
    console.error('Error en la petición:', {
        error: error,
        message: error.message,
        response: error.response,
        status: error.status
    });
    
    let errorMessage = 'Error al generar los cobros: ';
    
    if (error.response) {
        // Manejar errores de validación de Laravel
        if (error.response.errors) {
            const errors = Object.values(error.response.errors).flat();
            errorMessage += errors.join('\n');
        } else {
            errorMessage += error.response.message || error.message;
        }
    } else {
        errorMessage += error.message || 'Error desconocido';
    }
    
    alert(errorMessage);
}

    // Cerrar automáticamente la alerta después de 5 segundos
    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.getElementById('success-alert');
        if (alert) {
            setTimeout(() => {
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 150);
            }, 5000);
        }

        // Inicializar DataTable
        $('.datatable').DataTable({
            "order": [[10, "asc"]], // Ordenar por fecha de ingreso por defecto
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
            },
            "columnDefs": [
                { "orderable": false, "targets": [0, 11] } // Deshabilitar ordenación en columna de acciones
            ]
        });
    });
</script>
@endpush