<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Doctor;
use App\Models\Paciente;
use App\Http\Requests\StoreCitaRequest;
use App\Http\Requests\UpdateCitaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; 

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
    
    /**
     * Muestra el listado de citas en formato tabla.
     */
    public function index()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        
        // Iniciamos la consulta
        $query = \App\Models\Cita::with(['paciente', 'doctor.usuario'])
            ->orderBy('fecha_hora', 'asc'); // Las más próximas primero

        // Si es Doctor, solo ve sus citas
        if ($user->doctor) {
            $query->where('doctor_id', $user->doctor->id);
        }
        // Si es Paciente, solo ve las suyas
        elseif ($user->paciente) {
            $query->where('paciente_id', $user->paciente->id);
        }
        // (El Admin ve todas)

        $citas = $query->paginate(15);

        return view('citas.index', compact('citas'));
    }

    public function create()
    {
        $pacientes = Paciente::orderBy('apellido')->get();
        $doctors = Doctor::orderBy('apellido')->get();
        
        return view('citas.create', compact('pacientes', 'doctors'));
    }

    public function store(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        // 1. Validación de Datos
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'fecha_hora' => 'required|date', 
            'motivo' => 'nullable|string|max:255',
            'estado' => 'nullable|string|in:Pendiente,Confirmada', // Validamos el estado
        ]);

        try {
            // 2. VALIDACIÓN DE DISPONIBILIDAD (Anti-Choques)
            $fechaHora = \Carbon\Carbon::parse($request->fecha_hora);
            
            $citaExistente = \App\Models\Cita::where('doctor_id', $request->doctor_id)
                ->where('estado', '!=', 'Cancelada')
                ->where(function ($query) use ($fechaHora) {
                    $query->whereBetween('fecha_hora', [
                        $fechaHora->copy()->subMinutes(29), 
                        $fechaHora->copy()->addMinutes(29)
                    ]);
                })
                ->exists();

            if ($citaExistente) {
                return back()->with('error', 'Lo sentimos, este horario ya ha sido ocupado.');
            }

            // 3. Determinar Paciente y Estado
            $pacienteId = null;
            $estado = 'Pendiente'; // Por defecto

            if ($user->paciente) {
                // Si es paciente: Forzamos SU id y estado Pendiente
                $pacienteId = $user->paciente->id;
                $estado = 'Pendiente'; 
            } else {
                // Si es Doctor/Admin: Usamos el paciente del select y el estado seleccionado
                $request->validate(['paciente_id' => 'required|exists:pacientes,id']);
                $pacienteId = $request->paciente_id;
                $estado = $request->estado ?? 'Pendiente';
            }

            // 4. Crear la Cita
            \App\Models\Cita::create([
                'paciente_id' => $pacienteId,
                'doctor_id' => $request->doctor_id,
                'fecha_hora' => $fechaHora,
                'motivo' => $request->motivo,
                'estado' => $estado, 
            ]);

            return redirect()->back()->with('success', '¡Cita agendada correctamente!');

        } catch (\Exception $e) {
            return back()->with('error', 'Ocurrió un error al reservar: ' . $e->getMessage());
        }
    }

    
    public function show(Cita $cita)
    {
        // CORRECCIÓN: Cargamos todas las relaciones necesarias para la vista show
        // 'consulta' se agrega para verificar si existe una nota médica
        // 'ordenesExamen.examenes' carga el catálogo de exámenes dentro de las órdenes
        $cita->load(['paciente', 'doctor', 'ordenesExamen.examenes', 'consulta']); 

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
        
        // CORRECCIÓN: Conversión robusta de fecha
        $validated['fecha_hora'] = Carbon::parse($validated['fecha_hora'])->format('Y-m-d H:i:s');
        
        $cita->update($validated);
        
        return redirect()->route('citas.index', ['fecha' => Carbon::parse($validated['fecha_hora'])->toDateString()])
            ->with('success', 'Cita actualizada exitosamente.');
    }

    public function destroy(Cita $cita)
    {
        $cita->delete(); 
        
        return redirect()->route('citas.index')
            ->with('success', 'Cita cancelada y eliminada correctamente.');
    }

    /**
     * Muestra la vista del calendario visual.
     */
    public function calendario()
    {
        return view('citas.calendario');
    }

    /**
     * Devuelve las citas en formato JSON para FullCalendar.
     */
    public function getEvents(Request $request)
    {
        $citas = \App\Models\Cita::with(['paciente', 'doctor.usuario'])
            ->where('estado', '!=', 'Cancelada')
            ->get();

        $user = \Illuminate\Support\Facades\Auth::user();
        $miPacienteId = $user->paciente ? $user->paciente->id : null;

        $eventos = [];

        foreach ($citas as $cita) {
            if (!$cita->paciente) continue;

            if ($miPacienteId) {
                // VISTA PACIENTE
                if ($cita->paciente_id == $miPacienteId) {
                    $titulo = 'Mi Cita - Dr. ' . ($cita->doctor->usuario->name ?? '?');
                    $color = '#10b981'; // Verde
                    $display = 'block'; 
                } else {
                    $titulo = 'Ocupado';
                    $color = '#d1d5db'; // Gris
                    $display = 'background'; 
                }
            } else {
                // VISTA DOCTOR
                $titulo = $cita->paciente->nombre . ' ' . $cita->paciente->apellido;
                $color = ($cita->estado == 'Completada') ? '#10b981' : '#3788d8';
                $display = 'block';
            }

            $eventos[] = [
                'id' => $cita->id,
                'title' => $titulo,
                // CORRECCIÓN DE HORA: Usamos format simple para evitar restas de zona horaria
                'start' => $cita->fecha_hora->format('Y-m-d H:i:s'),
                'end' => $cita->fecha_hora->copy()->addMinutes(30)->format('Y-m-d H:i:s'),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'display' => $display, 
            ];
        }

        return response()->json($eventos);
    }
}