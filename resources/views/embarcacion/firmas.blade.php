@extends('layouts.app')

@section('title', 'Agregar Firmas')

@section('content')
<div class="pagetitle">
  <h1>Firmas de Embarcación</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('embarcacion.mostrar') }}">Embarcación</a></li>
      <li class="breadcrumb-item"><a href="{{ route('embarcacion.mostrarid', $embarcacion->id) }}">Ver Embarcación</a></li>
      <li class="breadcrumb-item active">Agregar Firmas</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Agregar Firmas Digitales - Embarcación #{{ $embarcacion->id }}</h5>

      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="bi bi-check-circle me-1"></i>
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="bi bi-exclamation-triangle me-1"></i>
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <form action="{{ route('embarcacion.guardar_firmas', $embarcacion->id) }}" method="POST" id="firmasForm">
        @csrf
        
        <!-- Firma Usuario -->
        <div class="row mb-4">
          <div class="col-md-12">
            <h6 class="text-primary"><i class="bi bi-person-check"></i> Firma Usuario</h6>
          </div>
          
          <div class="col-md-6 mb-3">
            <label for="nombre_usuario" class="form-label">Nombre del Usuario</label>
            <input type="text" class="form-control" id="nombre_usuario" 
                   value="{{ $embarcacion->usuario->name ?? 'Usuario' }}" readonly>
          </div>

          <div class="col-md-12">
            <label class="form-label">Firma del Usuario <span class="text-danger">*</span></label>
            <div class="border rounded p-2 mb-2" style="background-color: #f8f9fa;">
              <canvas id="canvasUsuario" width="600" height="200" style="border: 2px solid #000; background-color: white; cursor: crosshair; display: block; margin: 0 auto;"></canvas>
            </div>
            <div class="text-center">
              <button type="button" class="btn btn-sm btn-warning" onclick="clearCanvas('canvasUsuario')">
                <i class="bi bi-eraser"></i> Limpiar Firma Usuario
              </button>
            </div>
            <input type="hidden" name="firma_usuario" id="firma_usuario">
          </div>
        </div>

        <hr>

        <!-- Firma Cliente -->
        <div class="row mb-4">
          <div class="col-md-12">
            <h6 class="text-primary"><i class="bi bi-person-check"></i> Firma Cliente</h6>
          </div>
          
          <div class="col-md-6 mb-3">
            <label for="nombre_cliente" class="form-label">Nombre del Cliente</label>
            <input type="text" class="form-control" id="nombre_cliente" 
                   value="{{ $embarcacion->nombre_responsblecliente }} {{ $embarcacion->apellido_responsablecliente }}" readonly>
          </div>

          <div class="col-md-12">
            <label class="form-label">Firma del Cliente <span class="text-danger">*</span></label>
            <div class="border rounded p-2 mb-2" style="background-color: #f8f9fa;">
              <canvas id="canvasCliente" width="600" height="200" style="border: 2px solid #000; background-color: white; cursor: crosshair; display: block; margin: 0 auto;"></canvas>
            </div>
            <div class="text-center">
              <button type="button" class="btn btn-sm btn-warning" onclick="clearCanvas('canvasCliente')">
                <i class="bi bi-eraser"></i> Limpiar Firma Cliente
              </button>
            </div>
            <input type="hidden" name="firma_cliente" id="firma_cliente">
          </div>
        </div>

        <hr>

        <!-- Firma Chofer -->
        <div class="row mb-4">
          <div class="col-md-12">
            <h6 class="text-primary"><i class="bi bi-person-check"></i> Firma Chofer</h6>
          </div>
          
          <div class="col-md-6 mb-3">
            <label for="nombre_chofer" class="form-label">Nombre del Chofer</label>
            <input type="text" class="form-control" id="nombre_chofer" 
                   value="{{ $embarcacion->nombre_responsblechofer }} {{ $embarcacion->apellido_responsablechofer }}" readonly>
          </div>

          <div class="col-md-12">
            <label class="form-label">Firma del Chofer <span class="text-danger">*</span></label>
            <div class="border rounded p-2 mb-2" style="background-color: #f8f9fa;">
              <canvas id="canvasChofer" width="600" height="200" style="border: 2px solid #000; background-color: white; cursor: crosshair; display: block; margin: 0 auto;"></canvas>
            </div>
            <div class="text-center">
              <button type="button" class="btn btn-sm btn-warning" onclick="clearCanvas('canvasChofer')">
                <i class="bi bi-eraser"></i> Limpiar Firma Chofer
              </button>
            </div>
            <input type="hidden" name="firma_chofer" id="firma_chofer">
          </div>
        </div>

        <!-- Botones -->
        <div class="row">
          <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-success">
              <i class="bi bi-save"></i> Guardar Firmas
            </button>
            <a href="{{ route('embarcacion.mostrarid', $embarcacion->id) }}" class="btn btn-secondary">
              <i class="bi bi-x-circle"></i> Cancelar
            </a>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>

<script>
// Configuración de canvas para firmas
const canvases = {
  canvasUsuario: null,
  canvasCliente: null,
  canvasChofer: null
};

const contexts = {
  canvasUsuario: null,
  canvasCliente: null,
  canvasChofer: null
};

let isDrawing = {
  canvasUsuario: false,
  canvasCliente: false,
  canvasChofer: false
};

// Inicializar canvas
function initCanvas(canvasId) {
  const canvas = document.getElementById(canvasId);
  const ctx = canvas.getContext('2d');
  
  canvases[canvasId] = canvas;
  contexts[canvasId] = ctx;
  
  // Configurar estilo de dibujo
  ctx.strokeStyle = '#000';
  ctx.lineWidth = 2;
  ctx.lineCap = 'round';
  ctx.lineJoin = 'round';
  
  // Eventos del mouse
  canvas.addEventListener('mousedown', (e) => startDrawing(e, canvasId));
  canvas.addEventListener('mousemove', (e) => draw(e, canvasId));
  canvas.addEventListener('mouseup', () => stopDrawing(canvasId));
  canvas.addEventListener('mouseout', () => stopDrawing(canvasId));
  
  // Eventos táctiles para dispositivos móviles
  canvas.addEventListener('touchstart', (e) => {
    e.preventDefault();
    const touch = e.touches[0];
    const mouseEvent = new MouseEvent('mousedown', {
      clientX: touch.clientX,
      clientY: touch.clientY
    });
    canvas.dispatchEvent(mouseEvent);
  });
  
  canvas.addEventListener('touchmove', (e) => {
    e.preventDefault();
    const touch = e.touches[0];
    const mouseEvent = new MouseEvent('mousemove', {
      clientX: touch.clientX,
      clientY: touch.clientY
    });
    canvas.dispatchEvent(mouseEvent);
  });
  
  canvas.addEventListener('touchend', (e) => {
    e.preventDefault();
    const mouseEvent = new MouseEvent('mouseup', {});
    canvas.dispatchEvent(mouseEvent);
  });
}

function startDrawing(e, canvasId) {
  isDrawing[canvasId] = true;
  const rect = canvases[canvasId].getBoundingClientRect();
  const x = e.clientX - rect.left;
  const y = e.clientY - rect.top;
  
  contexts[canvasId].beginPath();
  contexts[canvasId].moveTo(x, y);
}

function draw(e, canvasId) {
  if (!isDrawing[canvasId]) return;
  
  const rect = canvases[canvasId].getBoundingClientRect();
  const x = e.clientX - rect.left;
  const y = e.clientY - rect.top;
  
  contexts[canvasId].lineTo(x, y);
  contexts[canvasId].stroke();
}

function stopDrawing(canvasId) {
  isDrawing[canvasId] = false;
  contexts[canvasId].closePath();
}

function clearCanvas(canvasId) {
  const canvas = canvases[canvasId];
  const ctx = contexts[canvasId];
  ctx.clearRect(0, 0, canvas.width, canvas.height);
}

// Validar y guardar firmas antes de enviar el formulario
document.getElementById('firmasForm').addEventListener('submit', function(e) {
  e.preventDefault();
  
  // Verificar que las firmas no estén vacías
  const emptyUsuario = isCanvasEmpty('canvasUsuario');
  const emptyCliente = isCanvasEmpty('canvasCliente');
  const emptyChofer = isCanvasEmpty('canvasChofer');
  
  if (emptyUsuario || emptyCliente || emptyChofer) {
    alert('Por favor, agregue las 3 firmas antes de guardar.');
    return;
  }
  
  // Convertir canvas a base64 con calidad reducida (JPEG 0.7)
  const firmaUsuario = convertCanvasToJPEG('canvasUsuario', 0.7);
  const firmaCliente = convertCanvasToJPEG('canvasCliente', 0.7);
  const firmaChofer = convertCanvasToJPEG('canvasChofer', 0.7);
  
  // Guardar en campos ocultos
  document.getElementById('firma_usuario').value = firmaUsuario;
  document.getElementById('firma_cliente').value = firmaCliente;
  document.getElementById('firma_chofer').value = firmaChofer;
  
  // Enviar formulario
  this.submit();
});

function convertCanvasToJPEG(canvasId, quality) {
  const canvas = canvases[canvasId];
  const tempCanvas = document.createElement('canvas');
  tempCanvas.width = canvas.width;
  tempCanvas.height = canvas.height;
  const tempCtx = tempCanvas.getContext('2d');
  
  // Fondo blanco para JPEG
  tempCtx.fillStyle = '#FFFFFF';
  tempCtx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);
  
  // Copiar firma
  tempCtx.drawImage(canvas, 0, 0);
  
  // Convertir a JPEG con calidad especificada
  return tempCanvas.toDataURL('image/jpeg', quality);
}

function isCanvasEmpty(canvasId) {
  const canvas = canvases[canvasId];
  const ctx = contexts[canvasId];
  const pixelBuffer = new Uint32Array(
    ctx.getImageData(0, 0, canvas.width, canvas.height).data.buffer
  );
  return !pixelBuffer.some(color => color !== 0);
}

// Inicializar los 3 canvas al cargar la página
document.addEventListener('DOMContentLoaded', function() {
  initCanvas('canvasUsuario');
  initCanvas('canvasCliente');
  initCanvas('canvasChofer');
});
</script>
@endsection
