<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PresentacionController;
use App\Http\Controllers\FrutaController;
use App\Http\Controllers\CoolerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ComercializadoraController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\VariedadController;
use App\Http\Controllers\CamaraController;
use App\Http\Controllers\TipoPalletController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolUsuarioController;
use App\Http\Controllers\DetalleContratoController;
use App\Http\Controllers\RecepcionController;
use App\Http\Controllers\DetalleRecepcionController;
use App\Http\Controllers\TarimaController;
use App\Http\Controllers\TarimaDetarecController;
use App\Http\Controllers\PreenfriadoController;
use App\Http\Controllers\ConservacionController;
use App\Http\Controllers\EmbarcacionController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserPermissionController;
use App\Http\Controllers\CobranzaController;
use App\Http\Controllers\CruceAndenController;

Route::get('/', [UserController::class, 'mostrarLogin'])->name('usuario.login');
Route::post('/validarlogin', [UserController::class, 'login'])->name('usuario.loguear');
Route::post('/logout', [UserController::class, 'logout'])->name('usuario.logout');
Route::middleware(['auth', 'rol:Administrador, Supervisor, Operativo'])->group(function () {
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
Route::middleware(['auth', 'rol:Administrador, Supervisor'])->group(function () {
        //******************************** Metodos corresponde a cooler *****************************************************///
    Route::get('/cooler', [CoolerController::class, 'mostrar'])->name('cooler.mostrar');
    Route::get('/crearcooler', [CoolerController::class, 'crear'])->name('cooler.crear');
    Route::post('/registrarcooler', [CoolerController::class, 'registrarCooler'])->name('cooler.registrar');
    Route::get('/editarcooler/{id}', [CoolerController::class, 'formularioEditar']);
    Route::put('/actualizarcooler/{id}', [CoolerController::class, 'editar']);
    Route::get('/vercooler/{id}', [CoolerController::class, 'buscarId']);
    Route::get('/buscarcooler', [CoolerController::class, 'buscarNomb']);
    Route::get('/exportarcooler', [CoolerController::class, 'exportar']);
    Route::get('/editarcoolers/{cooler}', [CoolerController::class, 'edit'])->name('cooler.editar');
    Route::post('/edicioncoolers/{cooler}', [CoolerController::class, 'update'])->name('cooler.update');
    Route::get('/eliminarcoolers/{cooler}', [CoolerController::class, 'destroy'])->name('cooler.eliminar');

    /*********************************************Mestodos que corresponden a Camara *********************************************/
    Route::get('/camaras', [CamaraController::class, 'index'])->name('camara.mostrar');
    Route::get('/crearcamara', [CamaraController::class, 'create'])->name('camara.crear');
    Route::post('/guardarcamara', [CamaraController::class, 'store'])->name('camara.guardar');
    Route::get('/editarcamara/{camara}', [CamaraController::class, 'edit'])->name('camara.editar');
    Route::put('/edicioncamara/{camara}', [CamaraController::class, 'update'])->name('camara.update');
    Route::get('/eliminarcamara/{camara}', [CamaraController::class, 'destroy'])->name('camara.eliminar');


    //*******************************************Metodos corresponde a Comercializadoras ************************************/
    Route::get('/comercializadoras', [ComercializadoraController::class, 'mostrar'])->name('comercializadora.mostrar');
    Route::get('/comercializadoras/crear', [ComercializadoraController::class, 'crear'])->name('comercializadora.crear');
    Route::post('/comercializadoras/registrar', [ComercializadoraController::class, 'registrarComercializadora'])->name('comercializadora.registrar');
    Route::get('/comercializadoras/editar/{id}', [ComercializadoraController::class, 'formularioEditar']);
    Route::post('/comercializadoras/editar/{id}', [ComercializadoraController::class, 'editar']);
    Route::get('/comercializadoras/ver/{id}', [ComercializadoraController::class, 'buscarId']);
    Route::get('/comercializadoras/buscar/{nombre}', [ComercializadoraController::class, 'buscarNombre']);
    Route::get('/comercializadoras/exportar', [ComercializadoraController::class, 'exportar']);
    Route::get('/editarcomercializadoras/{comercializadora}', [ComercializadoraController::class, 'edit'])->name('comercializadora.editar');
    Route::post('/edicioncomercializadoras/{comercializadora}', [ComercializadoraController::class, 'update'])->name('comercializadora.update');
    Route::get('/eliminarcomercializadoras/{comercializadora}', [ComercializadoraController::class, 'destroy'])->name('comercializadora.eliminar');

    /*****************************************************  MODELO DE PRESENTACIÓN ***********************************************/

    Route::get('/presentaciones', [PresentacionController::class, 'index'])->name('presentacion.mostrar');
    Route::get('/crearpresentacion', [PresentacionController::class, 'create'])->name('presentacion.crear');
    Route::post('/guardarpresentacion', [PresentacionController::class, 'store'])->name('presentacion.guardar');
    Route::get('/crearedicion/{presentacion}', [PresentacionController::class, 'edit'])->name('presentacion.editar');
    Route::post('/guardaredicion/{presentacion}', [PresentacionController::class, 'update'])->name('presentacion.update');
    Route::get('/eliminarpreentacion/{presentacion}', [PresentacionController::class, 'destroy'])->name('presentacion.eliminar');

    //******************************** Metodos corresponde a frutas *****************************************************///

    Route::get('/frutas', [FrutaController::class, 'index'])->name('fruta.mostrar');
    Route::get('/crearfrutas', [FrutaController::class, 'create'])->name('fruta.crear');
    Route::post('/guardarfrutas', [FrutaController::class, 'store'])->name('fruta.guardar');
    Route::get('/editarfrutas/{fruta}', [FrutaController::class, 'edit'])->name('fruta.editar');
    Route::put('/edicionfrutas/{fruta}', [FrutaController::class, 'update'])->name('fruta.update');
    Route::get('/eliminarfrutas/{fruta}', [FrutaController::class, 'destroy'])->name('fruta.eliminar');

    //******************************** Metodos corresponde a variedades ********************************************************///

    Route::get('/variedades', [VariedadController::class, 'index'])->name('variedad.mostrar');
    Route::get('/crearvariedad', [VariedadController::class, 'create'])->name('variedad.crear');
    Route::post('/guardarvariedad', [VariedadController::class, 'store'])->name('variedad.guardar');
    Route::get('/editarvariedad/{variedad}', [VariedadController::class, 'edit'])->name('variedad.editar');
    Route::post('/edicionvariedad/{variedad}', [VariedadController::class, 'update'])->name('variedad.update');
    Route::get('/eliminarvariedad/{variedad}', [VariedadController::class, 'destroy'])->name('variedad.eliminar');

    //******************************** Metodos corresponde a tipo pallets *****************************************************///

    Route::get('/tipopallets', [TipoPalletController::class, 'index'])->name('tipopallets.mostrar');
    Route::get('/creartipopa', [TipoPalletController::class, 'create'])->name('tipopallets.crear');
    Route::post('/guardartipopa', [TipoPalletController::class, 'store'])->name('tipopallets.guardar');
    Route::get('/editartipopa/{tipopallet}', [TipoPalletController::class, 'edit'])->name('tipopallets.editar');
    Route::post('/ediciontipopa/{tipopallet}', [TipoPalletController::class, 'update'])->name('tipopallets.update');
    Route::get('/eliminartipopa/{tipopallet}', [TipoPalletController::class, 'destroy'])->name('tipopallets.eliminar');


    //******************************** Metodos corresponde a usuarios *****************************************************///

    Route::get('/usuarios', [UserController::class, 'index'])->name('usuario.mostrar');
    Route::get('/registro', [UserController::class, 'create'])->name('usuario.crear');
    Route::post('/crear-usuario', [UserController::class, 'store'])->name('usuario.guardar');
    Route::get('/editarusuarios/{usuario}', [UserController::class, 'edit'])->name('usuario.editar');
    Route::post('/edicionusuarios/{usuario}', [UserController::class, 'update'])->name('usuario.update');
    Route::get('/eliminarusuarios/{usuario}', [UserController::class, 'destroy'])->name('usuario.eliminar');

    //******************************** Metodos corresponde a roles *****************************************************///

    Route::get('/roles', [RolUsuarioController::class, 'index'])->name('rolusuario.mostrar');
    Route::get('/crearroles', [RolUsuarioController::class, 'create'])->name('rolusuario.crear');
    Route::post('/guardarroles', [RolUsuarioController::class, 'store'])->name('rolusuario.guardar');
    Route::get('/editarroles/{rol}', [RolUsuarioController::class, 'edit'])->name('rolusuario.editar');
    Route::post('/edicionroles/{rol}', [RolUsuarioController::class, 'update'])->name('rolusuario.update');
    Route::get('/eliminarroles/{rol}', [RolUsuarioController::class, 'destroy'])->name('rolusuario.eliminar');

    //******************************** Metodos corresponde a permisos *****************************************************///

    Route::get('/permisos', [PermissionController::class, 'index'])->name('permisos.index');
    Route::get('/permisos/{rol}/editar', [PermissionController::class, 'edit'])->name('permisos.editar');
    Route::put('/permisos/{rol}', [PermissionController::class, 'update'])->name('permisos.actualizar');
    Route::post('/permisos/{rol}/asignar-todos', [PermissionController::class, 'assignAll'])->name('permisos.asignar-todos');
    Route::post('/permisos/{rol}/remover-todos', [PermissionController::class, 'removeAll'])->name('permisos.remover-todos');

    //******************************** Metodos corresponde a permisos de usuarios *****************************************************///

    Route::get('/usuarios/{usuario}/permisos', [UserPermissionController::class, 'edit'])->name('usuario.permisos.editar');
    Route::put('/usuarios/{usuario}/permisos', [UserPermissionController::class, 'update'])->name('usuario.permisos.actualizar');
    Route::post('/usuarios/{usuario}/permisos/asignar-todos', [UserPermissionController::class, 'assignAll'])->name('usuario.permisos.asignar-todos');
    Route::post('/usuarios/{usuario}/permisos/remover-todos', [UserPermissionController::class, 'removeAll'])->name('usuario.permisos.remover-todos');

  });
  Route::middleware(['auth', 'rol:Administrador, Supervisor'])->group(function () {
    //*********************************************Metodos corresponde a Cobranza *****************************************/
    Route::get('/cobranza',[CobranzaController::class, 'index'])->name('cobranza');
    Route::get('/cobranza/{idcontrato}',[CobranzaController::class, 'cobrarPendiente'])->name('cobranza.pendiente');
    Route::get('/generado/{idcontrato}',[CobranzaController::class, 'mostrar'])->name('cobranza.mostrar');
    Route::post('/cobranza/crear-multiple', [CobranzaController::class, 'crearMultiple'])->name('cobranza.crear.multiple');
    Route::get('/cobranza/comercializadora/{idComercializadora}',[DashboardController::class, 'verCobranzaPorComercializadora'])->name('cobranza.porcomercializadora');
    Route::get('/cobranza/comercializadora/{idComercializadora}/pdf',[DashboardController::class, 'verCobranzaPorComercializadoraPdf'])->name('cobranza.porcomercializadora.pdf');
    
    // Route::get('/cobranza',[DashboardController::class, 'verCobranza'])->name('cobranza');
    Route::get('/cobranza/detalle/{id}',[CobranzaController::class, 'verDetalle'])->name('cobranza.verdetalle');
    Route::get('/cobranza/detalle-consolidado',[CobranzaController::class, 'verDetalleConsolidado'])->name('cobranza.detalleconsolidado');
    Route::get('/cobranza/pdf-consolidado',[CobranzaController::class, 'verPdfConsolidado'])->name('cobranza.pdf.consolidado');
    Route::get('/cobranza/cambiarestatus/{id}',[CobranzaController::class, 'cambiarEstatus'])->name('cobranza.cambiarestatus');
    Route::post('/cobranza/cambiar-estatus-masivo',[CobranzaController::class, 'cambiarEstatusMasivo'])->name('cobranza.cambiarEstatusMasivo');
    Route::post('/cobranza/convertir-moneda',[CobranzaController::class, 'convertirMoneda'])->name('cobranza.convertir');
    
    //*********************************************Metodos corresponde a Contratos *****************************************/
    Route::get('/contratos', [ContratoController::class, 'mostrar'])->name('contrato.mostrar');
    Route::get('/contratos/crear', [ContratoController::class, 'crear'])->name('contrato.crear');
    Route::post('/contratos/registrar', [ContratoController::class, 'registrarContrato'])->name('contrato.registrar');
    Route::get('/contratos/ver/{id}', [ContratoController::class, 'buscarId']);
    Route::get('/contratos/exportar', [ContratoController::class, 'exportar']);
    Route::post('/contratos/generar-factura', [ContratoController::class, 'generarFactura']);
    Route::post('/contratos/generar-contrato', [ContratoController::class, 'generarContrato']);
    Route::get('/contratos/asignar-cooler/{id}', [ContratoController::class, 'asignarCooler']);
    Route::get('/contratos/asignar-fruta/{id}', [ContratoController::class, 'asignarFruta']);
    Route::get('/editarcontratos/{contrato}', [ContratoController::class, 'formularioEditar'])->name('contrato.editar');
    Route::post('/edicioncontratos/{contrato}', [ContratoController::class, 'editar'])->name('contrato.update');
    Route::get('/eliminarcontratos/{contrato}', [ContratoController::class, 'destroy'])->name('contrato.eliminar');

});

Route::middleware(['auth', 'rol:Administrador, Operativo, Supervisor'])->group(function () {
    
    Route::get('/recepcionar', [ContratoController::class, 'mostrarRecepcion'])->name('contrato.recepcionar');
    Route::get('/recepciones', [RecepcionController::class, 'index'])->name('recepcion.mostrar');
    Route::get('/recepciones/comercializadora/{idComercializadora}', [RecepcionController::class, 'porComercializadora'])->name('recepcion.porcomercializadora');
    Route::get('/crearrecepcion/{idcontrato}', [RecepcionController::class, 'create'])->name('recepcion.crear');
    Route::post('/guardarrecepcion', [RecepcionController::class, 'store'])->name('recepcion.guardar');
    Route::get('/recepcion/{recepcion}', [RecepcionController::class, 'show'])->name('recepcion.show');
    Route::get('/crearedicionrec/{recepcion}', [RecepcionController::class, 'edit'])->name('recepcion.editar');
    Route::put('/guardaredicionrec/{recepcion}', [RecepcionController::class, 'update'])->name('recepcion.update');
    Route::delete('/eliminarrecepcionrec/{recepcion}', [RecepcionController::class, 'destroy'])->name('recepcion.eliminar');
    Route::get('/recepcion/{id}/descargar', [RecepcionExportController::class, 'export'])->name('recepcion.descargar');
    Route::get('/recepcion/{id}/info', [RecepcionController::class, 'getInfo'])->name('recepcion.info');
    Route::get('/recepcion/pdf/{id}', [RecepcionController::class, 'verPdf'])->name('recepcion.pdf');
    Route::get('/recepcion/{id}/firmas', [RecepcionController::class, 'firmas'])->name('recepcion.firmas');
    Route::post('/recepcion/{id}/guardar-firmas', [RecepcionController::class, 'guardarFirmas'])->name('recepcion.guardar_firmas');

    //*********************************Metodos corresponde a detalles de recepciones ******************************************************///
    Route::get('/detallerecepciones', [DetalleRecepcionController::class, 'index'])->name('detallerecepcion.mostrar');
    Route::get('/creardetallerecepcion/{idrecepcion}', [DetalleRecepcionController::class, 'create'])->name('detallerecepcion.crear');
    Route::post('/guardardetallerecepcion', [DetalleRecepcionController::class, 'store'])->name('detallerecepcion.guardar');
    Route::get('/crearediciondet/{detallerecepcion}', [DetalleRecepcionController::class, 'edit'])->name('detallerecepcion.editar');
    Route::put('/guardarediciondet/{detallerecepcion}', [DetalleRecepcionController::class, 'update'])->name('detallerecepcion.update');
    Route::delete('/eliminardetalle/{detallerecepcion}', [DetalleRecepcionController::class, 'destroy'])->name('detallerecepcion.eliminar');


    //******************************** Metodos corresponde a Tarimas *****************************************************///

    Route::get('/tarimas', [TarimaController::class, 'index'])->name('tarima.mostrar');
    Route::get('/creartarima', [TarimaController::class, 'create'])->name('tarima.crear');
    Route::post('/guardartrima', [TarimaController::class, 'store'])->name('tarima.guardar');
    Route::post('/guardarautomatico', [TarimaController::class, 'storeAutomatic'])->name('tarima.guardarautomatico');
    Route::get('/editartarima/{tarima}', [TarimaController::class, 'edit'])->name('tarima.editar');
    Route::post('/ediciontarima/{tarima}', [TarimaController::class, 'update'])->name('tarima.update');
    Route::delete('/eliminartarima/{tarima}', [TarimaController::class, 'destroy'])->name('tarima.eliminar');
    Route::get('/etiqueta/{id}', [TarimaController::class, 'etiqueta'])->name('tarima.etiqueta');
    Route::get('/mostrartarima/{id}', [TarimaController::class, 'mostrarId'])->name('tarima.mostrarid');
    Route::get('/tarima/pdf/{id}', [TarimaController::class, 'verPdf'])->name('tarima.pdf');
    //******************************** Metodos corresponde a Tarimas *****************************************************///

    Route::get('/asignartarimas', [TarimaDetarecController::class, 'index'])->name('asignartarima.mostrar');
    Route::get('/asginarcreartarima', [TarimaDetarecController::class, 'create'])->name('asignartarima.crear');
    Route::post('/asignarguardartarima', [TarimaDetarecController::class, 'store'])->name('asignartarima.guardar');
    Route::get('/asignareditartarima/{tarima}', [TarimaDetarecController::class, 'edit'])->name('asignartarima.editar');
    Route::post('/asignarediciontarima/{tarima}', [TarimaDetarecController::class, 'update'])->name('asignartarima.update');
    Route::delete('/asignareliminartarima/{tarima}', [TarimaDetarecController::class, 'destroy'])->name('asignartarima.eliminar');


    //******************************** Metodos corresponde a Pre-Enfriado *****************************************************///

    Route::get('/esenfrio', [PreenfriadoController::class, 'index'])->name('enfrio.mostrar');
    Route::post('/guardarentradasalidae/{id}', [PreenfriadoController::class, 'store'])->name('enfrio.guardar');
    Route::post('/guardar-multiple-preenfriado', [PreenfriadoController::class, 'storeMultiple'])->name('enfrio.guardar.multiple');
    Route::get('/crearentradasalidae/{id}', [PreenfriadoController::class, 'create'])->name('enfrio.crear');
    Route::post('/guardaresdetalle', [PreenfriadoController::class, 'storedetalle'])->name('enfrio.guardardetalle');
    Route::get('/editarentradasalidae/{id}', [PreenfriadoController::class, 'edit'])->name('enfrio.editar');
    Route::post('/edicionesdetalle', [PreenfriadoController::class, 'updatedetalle'])->name('enfrio.updatedetalle');
    Route::get('/mostrarides/{id}', [PreenfriadoController::class, 'show'])->name('enfrio.mostrarid');
    Route::delete('/eliminarentradasalidae/{preenfriado}', [PreenfriadoController::class, 'destroy'])->name('enfrio.eliminar');
    Route::get('/elegir-destino/{idTarima}', [PreenfriadoController::class, 'elegirDestino'])->name('preenfriado.elegir_destino');
    Route::post('/procesar-destino/{idTarima}', [PreenfriadoController::class, 'procesarDestino'])->name('preenfriado.procesar_destino');
    Route::get('/preenfriado/pdf/{id}', [PreenfriadoController::class, 'verPdf'])->name('preenfriado.pdf');
    Route::get('/preenfriado/{id}/firmas', [PreenfriadoController::class, 'firmas'])->name('preenfriado.firmas');
    Route::post('/preenfriado/{id}/guardar-firmas', [PreenfriadoController::class, 'guardarFirmas'])->name('preenfriado.guardar_firmas');

    //******************************** Metodos corresponde a Conservación *****************************************************///

    Route::get('/esconservacion', [ConservacionController::class, 'index'])->name('conservacion.mostrar');
    Route::post('/guardaresconservacion/{id}', [ConservacionController::class, 'store'])->name('conservacion.guardar');
    Route::get('/crearentradasalidac/{id}', [ConservacionController::class, 'create'])->name('conservacion.crear');
    Route::post('/guardaresdetallec', [ConservacionController::class, 'storedetalle'])->name('conservacion.guardardetalle');
    Route::get('/editaresdetallec/{id}', [ConservacionController::class, 'edit'])->name('conservacion.editar');
    Route::post('/edicionesdetallec', [ConservacionController::class, 'updatedetalle'])->name('conservacion.updatedetalle');
    Route::get('/mostraridesc/{id}', [ConservacionController::class, 'show'])->name('conservacion.mostrarid');
    Route::delete('/eliminaresdetallec/{conservacion}', [ConservacionController::class, 'destroy'])->name('conservacion.eliminar');
    Route::get('/conservacion/pdf/{id}', [ConservacionController::class, 'verPdf'])->name('conservacion.pdf');
    Route::get('/conservacion/{id}/firmas', [ConservacionController::class, 'firmas'])->name('conservacion.firmas');
    Route::post('/conservacion/{id}/guardar-firmas', [ConservacionController::class, 'guardarFirmas'])->name('conservacion.guardar_firmas');

    //******************************** Metodos corresponde a Embarcación *****************************************************///

    Route::get('/embarcacion', [EmbarcacionController::class, 'index'])->name('embarcacion.mostrar');
    Route::get('/crearembarcacion/{id}', [EmbarcacionController::class, 'create'])->name('embarcacion.crear');
    Route::post('/guardarembarcacion', [EmbarcacionController::class, 'store'])->name('embarcacion.guardar');
    Route::post('/guardar-embarcacion-multiple', [EmbarcacionController::class, 'storeMultiple'])->name('embarcacion.guardar.multiple');
    Route::get('/crear-embarcacion-multiple', [EmbarcacionController::class, 'createMultiple'])->name('embarcacion.crear.multiple.form');
    Route::post('/procesar-embarcacion-multiple', [EmbarcacionController::class, 'processMultiple'])->name('embarcacion.procesar.multiple');
    Route::get('/editareembarcacion/{id}', [EmbarcacionController::class, 'edit'])->name('embarcacion.editar');
    Route::put('/edicionembarque/{id}', [EmbarcacionController::class, 'update'])->name('embarcacion.update');
    Route::get('/mostrarembarque/{id}', [EmbarcacionController::class, 'show'])->name('embarcacion.mostrarid');
    Route::delete('/eliminarembarque/{id}', [EmbarcacionController::class, 'destroy'])->name('embarcacion.eliminar');
    Route::get('/embarcacion/pdf/{id}', [EmbarcacionController::class, 'verPdf'])->name('embarcacion.pdf');
    Route::get('/embarcacion/{id}/firmas', [EmbarcacionController::class, 'firmas'])->name('embarcacion.firmas');
    Route::post('/embarcacion/{id}/guardar-firmas', [EmbarcacionController::class, 'guardarFirmas'])->name('embarcacion.guardar_firmas');

    //******************************** Metodos corresponde a Cruce de Andén *****************************************************///

    Route::get('/cruce-anden', [CruceAndenController::class, 'index'])->name('cruce_anden.mostrar');
    Route::get('/crear-cruce-anden/{idTarima}', [CruceAndenController::class, 'create'])->name('cruce_anden.crear');
    Route::post('/guardar-cruce-anden/{idTarima}', [CruceAndenController::class, 'store'])->name('cruce_anden.guardar');
    Route::get('/editar-cruce-anden/{id}', [CruceAndenController::class, 'edit'])->name('cruce_anden.editar');
    Route::post('/actualizar-detalle-cruce-anden', [CruceAndenController::class, 'updateDetalle'])->name('cruce_anden.updatedetalle');
    Route::get('/ver-cruce-anden/{id}', [CruceAndenController::class, 'show'])->name('cruce_anden.mostrarid');
    Route::delete('/eliminar-cruce-anden/{id}', [CruceAndenController::class, 'destroy'])->name('cruce_anden.eliminar');
    Route::get('/cruce-anden/pdf/{id}', [CruceAndenController::class, 'verPdf'])->name('cruce_anden.pdf');
    Route::get('/cruce-anden/{id}/firmas', [CruceAndenController::class, 'firmas'])->name('cruce_anden.firmas');
    Route::post('/cruce-anden/{id}/guardar-firmas', [CruceAndenController::class, 'guardarFirmas'])->name('cruce_anden.guardar_firmas');
});
Route::fallback(function () {
    return redirect('/'); // Redirige al inicio
});