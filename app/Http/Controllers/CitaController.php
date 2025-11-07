<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Doctor;
use App\Models\Paciente;
use App\Http\Requests\StoreCitaRequest;
use App\Http\Requests\UpdateCitaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // <-- AGREGADO: Necesario para la conversión de fechas en store()

class CitaController extends Controller
{
    /**
     * Define los middlewares para este controlador.
     */
    public static function middleware(): array
    {
        return [
            'permission:gestion.citas',
        ];
    }
    
    // app/Http/Controllers/CitaController.php

    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Asumiendo que hasRole('Doctor') funciona correctamente
        $isDoctor = $user->hasRole('Doctor');

        $doctorId = null;
        
        if ($isDoctor) {
            // CORRECCIÓN 1: Evitar el error si el User no tiene relación 'doctor'.
            // Aquí se requiere que el modelo User tenga una relación hasOne con Doctor.
            $doctorModel = $user->doctor ?? null;
            if ($doctorModel) {
                $doctorId = $doctorModel->id; 
            }
        }
        
        // Si el Admin/Recepción envían un filtro, este tiene prioridad.
        // Si es un Doctor, $doctorId ya tiene su propio ID si no se filtra.
        $doctorId = $request->input('doctor_id', $doctorId);

        $fecha = $request->input('fecha') ?? now()->toDateString();
        
        // CORRECCIÓN 2: Eager Loading para evitar el error N+1 y el RelationNotFoundException.
        // Se asegura que los datos de paciente y doctor se carguen con la consulta principal.
        $citasQuery = Cita::with(['paciente', 'doctor']) 
            ->whereDate('fecha_hora', $fecha)
            ->orderBy('fecha_hora');
        
        if ($doctorId) {
            $citasQuery->where('doctor_id', $doctorId);
        }
        
        // Citas para la vista
        $citas = $citasQuery->paginate(20);
        
        // Datos para filtros
        $doctors = Doctor::orderBy('apellido')->get();
        
        return view('citas.index', compact('citas', 'doctors', 'fecha', 'doctorId'));
    }

    public function create()
    {
        $pacientes = Paciente::orderBy('apellido')->get();
        $doctors = Doctor::orderBy('apellido')->get();
        
        return view('citas.create', compact('pacientes', 'doctors'));
    }

    // app/Http/Controllers/CitaController.php

    public function store(StoreCitaRequest $request)
    {
        $validated = $request->validated();

        // CORRECCIÓN 3: Ajustar la conversión de fecha/hora para coincidir con la sanitización y la DB
        // Usamos el formato FINAL que resulta de la sanitización: d/m/Y h:i A (ej: 10/11/2025 04:30 PM)
        $validated['fecha_hora'] = Carbon::createFromFormat('d/m/Y h:i A', $validated['fecha_hora'])->format('Y-m-d H:i:s');

        Cita::create($validated);

        return redirect()->route('citas.index', ['fecha' => Carbon::parse($validated['fecha_hora'])->toDateString()])
            ->with('success', 'Cita agendada exitosamente.');
    }
    
    public function show(Cita $cita)
    {
        $cita->load(['paciente', 'doctor', 'ordenesExamen']);
        return view('citas.show', compact('cita'));
    }

    public function edit(Cita $cita)
    {
        $pacientes = Paciente::orderBy('apellido')->get();
        $doctors = Doctor::orderBy('apellido')->get();
        
        return view('citas.edit', compact('cita', 'pacientes', 'doctors'));
    }

    public function update(UpdateCitaRequest $request, Cita $cita)
    {
            $validated = $request->validated();
    
        // CONVERSIÓN FINAL: Usamos Carbon::parse(), que puede interpretar '11:00 a. m.'
        $validated['fecha_hora'] = \Carbon\Carbon::parse($validated['fecha_hora'])->format('Y-m-d H:i:s');
        
        $cita->update($validated);
        // ...
        
        return redirect()->route('citas.index', ['fecha' => \Carbon\Carbon::parse($validated['fecha_hora'])->toDateString()])
            ->with('success', 'Cita actualizada exitosamente.');
    }

    public function destroy(Cita $cita)
    {
        $cita->delete(); 
        
        return redirect()->route('citas.index')
            ->with('success', 'Cita cancelada y eliminada correctamente.');
    }
}