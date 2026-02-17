@extends('layouts.app')

@section('title', 'Agregar Firmas')

@section('content')
<div class="pagetitle">
  <h1>Firmas de Conservación</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('conservacion.mostrar') }}">Conservación</a></li>
      <li class="breadcrumb-item"><a href="{{ route('conservacion.mostrarid', $conservacion->id) }}">Ver Conservación</a></li>
      <li class="breadcrumb-item active">Agregar Firmas</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Agregar Firmas Digitales - Tarima: {{ $conservacion->tarima->codigo }}</h5>

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

      <form action="{{ route('conservacion.guardar_firmas', $conservacion->id) }}" method="POST" id="firmasForm">
        @csrf
        
        <!-- Responsable 1 -->
        <div class="row mb-4">
          <div class="col-md-12">
            <h6 class="text-primary"><i class="bi bi-person-check"></i> Responsable 1</h6>
          </div>
          
          <div class="col-md-6 mb-3">
            <label for="nombre_responsable1" class="form-label">Nombre del Responsable 1 <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nombre_responsable1" name="nombre_responsable1" 
                   value="{{ old('nombre_responsable1', $conservacion->nombre_responsable1) }}" required>
          </div>

          <div class="col-md-12">
            <label class="form-label">Firma del Responsable 1 <span class="text-danger">*</span></label>
            <div class="border rounded p-2 mb-2" style="background-color: #f8f9fa;">
              <canvas id="canvas1" width="600" height="200" style="border: 2px solid #000; background-color: white; cursor: crosshair; display: block; margin: 0 auto;"></canvas>
            </div>
            <div class="text-center">
              <button type="button" class="btn btn-sm btn-warning" onclick="clearCanvas('canvas1')">
                <i class="bi bi-eraser"></i> Limpiar Firma 1
              </button>
            </div>
            <input type="hidden" name="firma_responsable1" id="firma_responsable1">
          </div>
        </div>

        <hr>

        <!-- Responsable 2 -->
        <div class="row mb-4">
          <div class="col-md-12">
            <h6 class="text-primary"><i class="bi bi-person-check"></i> Responsable 2</h6>
          </div>
          
          <div class="col-md-6 mb-3">
            <label for="nombre_responsable2" class="form-label">Nombre del Responsable 2 <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nombre_responsable2" name="nombre_responsable2" 
                   value="{{ old('nombre_responsable2', $conservacion->nombre_responsable2) }}" required>
          </div>

          <div class="col-md-12">
            <label class="form-label">Firma del Responsable 2 <span class="text-danger">*</span></label>
            <div class="border rounded p-2 mb-2" style="background-color: #f8f9fa;">
              <canvas id="canvas2" width="600" height="200" style="border: 2px solid #000; background-color: white; cursor: crosshair; display: block; margin: 0 auto;"></canvas>
            </div>
            <div class="text-center">
              <button type="button" class="btn btn-sm btn-warning" onclick="clearCanvas('canvas2')">
                <i class="bi bi-eraser"></i> Limpiar Firma 2
              </button>
            </div>
            <input type="hidden" name="firma_responsable2" id="firma_responsable2">
          </div>
        </div>

        <hr>

        <!-- Nota -->
        <div class="row mb-4">
          <div class="col-md-12">
            <label for="nota_firmas" class="form-label">Nota (Opcional)</label>
            <textarea class="form-control" id="nota_firmas" name="nota_firmas" rows="4" maxlength="500">{{ old('nota_firmas', $conservacion->nota_firmas) }}</textarea>
            <small class="text-muted">Máximo 500 caracteres</small>
          </div>
        </div>

        <!-- Botones -->
        <div class="row">
          <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-success">
              <i class="bi bi-save"></i> Guardar Firmas
            </button>
            <a href="{{ route('conservacion.mostrarid', $conservacion->id) }}" class="btn btn-secondary">
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
  canvas1: null,
  canvas2: null
};

const contexts = {
  canvas1: null,
  canvas2: null
};

let isDrawing = {
  canvas1: false,
  canvas2: false
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
  const emptyCanvas1 = isCanvasEmpty('canvas1');
  const emptyCanvas2 = isCanvasEmpty('canvas2');
  
  if (emptyCanvas1 || emptyCanvas2) {
    alert('Por favor, agregue ambas firmas antes de guardar.');
    return;
  }
  
  // Convertir canvas a base64 con calidad reducida (JPEG 0.7)
  const firma1 = convertCanvasToJPEG('canvas1', 0.7);
  const firma2 = convertCanvasToJPEG('canvas2', 0.7);
  
  // Guardar en campos ocultos
  document.getElementById('firma_responsable1').value = firma1;
  document.getElementById('firma_responsable2').value = firma2;
  
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

// Inicializar ambos canvas al cargar la página
document.addEventListener('DOMContentLoaded', function() {
  initCanvas('canvas1');
  initCanvas('canvas2');
});
</script>
@endsection
