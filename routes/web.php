<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\LaboratorioController;
use App\Http\Controllers\ExamenController;
use App\Http\Controllers\OrdenExamenController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\ReporteController;

Route::get('/', function () {
    return view('welcome');
});

// ====================================================================
// DASHBOARD
// ====================================================================
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware('auth')->group(function () {
    // PERFIL
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // PACIENTES Y DOCTORES
    Route::get('/mis-resultados', [PacienteController::class, 'misResultados'])->name('pacientes.portal');
    Route::resource('pacientes', PacienteController::class);
    Route::resource('doctors', DoctorController::class);
    
    // CITAS Y AGENDA
    Route::get('/agenda', [CitaController::class, 'calendario'])->name('citas.calendario');
    Route::get('/api/citas-events', [CitaController::class, 'getEvents'])->name('citas.events');
    
    Route::resource('citas', CitaController::class);
    Route::resource('consultas', ConsultaController::class);
    Route::get('citas/{cita}/consulta/create', [ConsultaController::class, 'createFromCita'])->name('consultas.createFromCita');
    

    // ====================================================================
    // MÓDULOS ADMINISTRATIVOS (Inventario, Caja, Reportes)
    // ====================================================================
    
    // Inventario
    Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
    Route::get('/inventario/crear', [InventarioController::class, 'create'])->name('inventario.create');
    Route::post('/inventario', [InventarioController::class, 'store'])->name('inventario.store');
    Route::post('/inventario/{insumo}/stock', [InventarioController::class, 'addStock'])->name('inventario.addStock');

    // Caja (Control de Sesiones y Movimientos)
    Route::get('/caja', [CajaController::class, 'index'])->name('caja.index');
    Route::get('/caja/historial', [CajaController::class, 'historial'])->name('caja.historial');
    Route::post('/caja/cerrar', [CajaController::class, 'cerrarCaja'])->name('caja.cerrar');
    Route::post('/caja/gasto', [CajaController::class, 'storeGasto'])->name('caja.gasto.store');
    
    // Reportes
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    // ====================================================================
    // GESTIÓN DE ÓRDENES (DOCTOR Y LABORATORIO)
    // ====================================================================
    // Ruta para pacientes directos (Laboratorio)
    Route::get('pacientes/{paciente}/orden-rapida', [OrdenExamenController::class, 'createDirecto'])->name('ordenes.createDirecto');
    // Rutas estándar
    Route::get('citas/{cita}/ordenes/create', [OrdenExamenController::class, 'create'])->name('ordenes.create'); 
    Route::post('ordenes', [OrdenExamenController::class, 'store'])->name('ordenes.store');
    
    // ====================================================================
    // LABORATORIO: Listado y Gestión de Resultados
    // ====================================================================
    Route::get('laboratorio', [LaboratorioController::class, 'index'])->name('laboratorio.index');
    
    // 1. Mostrar formulario
    Route::get('laboratorio/subir/{ordenExamen}', [LaboratorioController::class, 'editResultado'])->name('laboratorio.subirResultado');
    
    // 2. Guardar y Generar PDF
    Route::put('laboratorio/subir/{id}', [LaboratorioController::class, 'update'])->name('laboratorio.update');
    
    // 3. Descargar PDF
    Route::get('laboratorio/resultado/{ordenExamen}/descargar', [LaboratorioController::class, 'downloadResultado'])->name('laboratorio.downloadResultado');
    
    // ====================================================================
    // MÓDULO DE CAJA (PAGOS)
    // ====================================================================
    Route::get('pagos/cobrar/orden/{orden}', [PagoController::class, 'createForOrden'])->name('pagos.orden.create');
    Route::post('pagos/orden', [PagoController::class, 'storeOrden'])->name('pagos.orden.store');

    // RUTAS DE COBRO DE CONSULTAS
    Route::get('pagos/cobrar/consulta/{consulta}', [PagoController::class, 'createForConsulta'])->name('pagos.consulta.create');
    Route::post('pagos/consulta', [PagoController::class, 'storeConsulta'])->name('pagos.consulta.store');
    
    // Catálogo de Exámenes
    Route::resource('examenes', ExamenController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])->names([
        'index' => 'examenes.index',
        'create' => 'examenes.create',
        'store' => 'examenes.store',
        'edit' => 'examenes.edit',
        'update' => 'examenes.update',
        'destroy' => 'examenes.destroy', // <--- ¡ESTA FALTABA!
    ]);

    // Ruta para asignar un insumo (reactivo) a un examen
    Route::post('/examenes/{examen}/insumos', [ExamenController::class, 'asignarInsumo'])->name('examenes.insumos.store');
    
    // Y probablemente necesites esta también para quitarlo (desvincular insumo)
    Route::delete('/examenes/{examen}/insumos/{insumo}', [ExamenController::class, 'quitarInsumo'])->name('examenes.insumos.destroy');

    // Ruta para descargar receta médica
    Route::get('consultas/{consulta}/receta', [ConsultaController::class, 'downloadReceta'])->name('consultas.receta.pdf');

    // ====================================================================
    // 🤖 RUTAS DE INTELIGENCIA ARTIFICIAL (SIGLC) - ¡NUEVAS!
    // ====================================================================
    
    // 1. IA para CONSULTAS (Texto y Audio)
    Route::post('/consultas/ia-analisis', [ConsultaController::class, 'consultarIA'])->name('consultas.ia');
    Route::post('/consultas/ia-transcribir', [ConsultaController::class, 'transcribirAudio'])->name('consultas.transcribir');

    // 2. IA para ÓRDENES (Escáner OCR)
    Route::post('/ordenes/escanear', [OrdenExamenController::class, 'escanearOrden'])->name('ordenes.escanear');

    // 3. IA para LABORATORIO (Interpretación de Resultados)
    Route::post('/laboratorio/interpretar', [LaboratorioController::class, 'interpretarResultados'])->name('laboratorio.interpretar');

    Route::post('/pacientes/chat', [PacienteController::class, 'chatIA'])->name('pacientes.chat');
});

require __DIR__.'/auth.php';