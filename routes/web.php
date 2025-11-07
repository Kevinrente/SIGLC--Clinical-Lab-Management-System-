<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\LaboratorioController;
use App\Http\Controllers\ExamenController;
// NUEVAS IMPORTACIONES
use App\Http\Controllers\OrdenExamenController; 
use App\Models\Paciente;
use App\Models\Cita;

Route::get('/', function () {
    return view('welcome');
});

// DASHBOARD: Lógica de contadores
Route::get('/dashboard', function () {
    // Obtenemos los contadores activos
    $totalPacientes = Paciente::count();
    // Usamos now() para asegurarnos que solo cuenta las de hoy
    $totalCitasHoy = Cita::whereDate('fecha_hora', now()->toDateString())
                         ->where('estado', 'Pendiente')
                         ->count();

    return view('dashboard', [
        'totalPacientes' => $totalPacientes,
        'totalCitasHoy' => $totalCitasHoy,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    // PERFIL
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // PACIENTES Y DOCTORES
    Route::resource('pacientes', PacienteController::class);
    Route::resource('doctors', DoctorController::class);
    
    // CITAS Y CONSULTAS
    Route::resource('citas', CitaController::class);
    Route::resource('consultas', ConsultaController::class);
    Route::get('citas/{cita}/consulta/create', [ConsultaController::class, 'createFromCita'])->name('consultas.createFromCita');
    
    // ====================================================================
    // GESTIÓN DE ÓRDENES DE EXAMEN (FLUJO DOCTOR -> LABORATORIO)
    // ====================================================================

    // RUTA 1: [DOCTOR] Muestra el formulario para crear una orden desde una cita
    Route::get('citas/{cita}/ordenes/create', [OrdenExamenController::class, 'create'])->name('ordenes.create'); 
    
    // RUTA 2: [DOCTOR] Almacena la nueva orden (Acción de POST)
    Route::post('ordenes', [OrdenExamenController::class, 'store'])->name('ordenes.store');
    
    // 3. LABORATORIO: Listado y Gestión de Resultados
    Route::get('laboratorio', [LaboratorioController::class, 'index'])->name('laboratorio.index');
    Route::get('laboratorio/subir/{ordenExamen}', [LaboratorioController::class, 'editResultado'])->name('laboratorio.subirResultado');
    Route::post('laboratorio/subir/{ordenExamen}', [LaboratorioController::class, 'storeResultado'])->name('laboratorio.storeResultado');
    Route::get('laboratorio/resultado/{ordenExamen}/descargar', [LaboratorioController::class, 'downloadResultado'])->name('laboratorio.downloadResultado');
    
    // Catálogo de Exámenes
    Route::resource('examenes', ExamenController::class)->only(['index', 'create', 'store', 'edit', 'update'])->names([
        'index' => 'examenes.index',
        'create' => 'examenes.create',
        'store' => 'examenes.store',
        'edit' => 'examenes.edit',
        'update' => 'examenes.update',
    ]);
});

require __DIR__.'/auth.php';