<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Doctor;
use App\Models\Paciente;
use App\Http\Requests\StoreCitaRequest;
use App\Http\Requests\UpdateCitaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CitaController extends Controller
{
    /**
     * Define los middlewares para este controlador.
     */
    public static function middleware(): array
    {
        return [
            // Solo los usuarios con el permiso 'gestion.citas' pueden acceder.
            'permission:gestion.citas',
        ];
    }
    
    // app/Http/Controllers/CitaController.php

        public function index(Request $request)
        {
            // Obtener el usuario autenticado
            $user = Auth::user();

            // *PHPDoc para corregir el error visual de hasRole*
            /** @var \App\Models\User $user */
            
            // Obtener el doctor actual para filtrar la agenda si es un Doctor logueado
            $isDoctor = $user->hasRole('Doctor');

            // Inicializar $doctorId
            $doctorId = null;
            
            if ($isDoctor) {
                // Asume que el modelo User tiene la relación 'doctor' configurada
                $doctorId = $user->doctor->id; 
            }
            
            // Si el Admin o Recepción envían un filtro por doctor, se usa ese filtro.
            $doctorId = $request->input('doctor_id', $doctorId);

            $fecha = $request->input('fecha') ?? now()->toDateString();
            
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
        // Se necesitan listas de Pacientes y Doctores para el formulario
        $pacientes = Paciente::orderBy('apellido')->get();
        $doctors = Doctor::orderBy('apellido')->get();
        
        return view('citas.create', compact('pacientes', 'doctors'));
    }

    public function store(StoreCitaRequest $request)
    {
        Cita::create($request->validated());

        return redirect()->route('citas.index', ['fecha' => \Carbon\Carbon::parse($request->fecha_hora)->toDateString()])
            ->with('success', 'Cita agendada exitosamente.');
    }
    
    public function show(Cita $cita)
    {
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
        $cita->update($request->validated());
        
        return redirect()->route('citas.index', ['fecha' => \Carbon\Carbon::parse($request->fecha_hora)->toDateString()])
            ->with('success', 'Cita actualizada exitosamente.');
    }

    public function destroy(Cita $cita)
    {
        // Se puede añadir lógica para enviar notificación al paciente si se cancela
        $cita->delete(); 
        
        return redirect()->route('citas.index')
            ->with('success', 'Cita cancelada y eliminada correctamente.');
    }
}
