<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\Cita; // Necesario para la función createFromCita
use App\Http\Requests\StoreConsultaRequest;
use App\Http\Requests\UpdateConsultaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Examen;
use App\Models\OrdenExamen; // Importar modelo OrdenExamen

class ConsultaController extends Controller
{
    /**
     * Define los middlewares para este controlador.
     */
    public static function middleware(): array
    {
        return [
            // Solo los usuarios con el permiso 'gestion.consultas' (Doctor) pueden acceder.
            'permission:gestion.consultas',
        ];
    }
    
    // El índice solo muestra las consultas registradas por el Doctor logueado
    public function index()
    {
        $doctorId = Auth::user()->doctor->id;
        
        $consultas = Consulta::with(['paciente', 'doctor'])
                             ->where('doctor_id', $doctorId)
                             ->latest()
                             ->paginate(15);
        
        return view('consultas.index', compact('consultas'));
    }

    // Ruta personalizada para iniciar la consulta desde una Cita
    public function createFromCita(Cita $cita)
    {
        // 1. Verificar si el Doctor logueado es el asignado a la cita
        if (Auth::user()->doctor->id !== $cita->doctor_id) {
             abort(403, 'No estás autorizado para registrar consultas de esta cita.');
        }

        // 2. Verificar que la cita no tenga ya una consulta asociada
        if ($cita->consulta) {
            return redirect()->route('consultas.edit', $cita->consulta)
                             ->with('error', 'Esta cita ya tiene una consulta registrada. Se redirigió a edición.');
        }

        // 3. Marcar la cita como Completada
        $cita->update(['estado' => 'Completada']);

        $examenes = Examen::orderBy('nombre')->get();
        
        // El formulario recibirá los IDs necesarios
        return view('consultas.create', compact('cita', 'examenes')); // Pasar $examenes    }}
    }

    public function store(StoreConsultaRequest $request)
    {
        // 1. Crear la consulta
        $consulta = Consulta::create($request->validated() + [
            'doctor_id' => Auth::user()->doctor->id,
        ]);

        // 2. Procesar y crear las Órdenes de Examen (Si existen)
        if (!empty($request->examenes_solicitados)) {
            $ordenesData = [];
            $doctorId = Auth::user()->doctor->id;
            $pacienteId = $request->paciente_id;
            
            foreach ($request->examenes_solicitados as $examenId) {
                $ordenesData[] = [
                    'paciente_id' => $pacienteId,
                    'doctor_id' => $doctorId,
                    'consulta_id' => $consulta->id,
                    'examen_id' => $examenId,
                    'estado' => 'Solicitado',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            OrdenExamen::insert($ordenesData); // Inserción masiva para eficiencia
        }

        return redirect()->route('consultas.index')
            ->with('success', 'Consulta registrada y órdenes de laboratorio generadas exitosamente.');
    }
    
    // El método create() no se usa directamente; usamos createFromCita()

    public function show(Consulta $consulta)
    {
        // **INICIO DE LA CORRECCIÓN VISUAL**
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Solo puede verla el doctor que la creó O el Admin (gestion.administracion)
        if ($user->doctor->id !== $consulta->doctor_id && !$user->hasPermissionTo('gestion.administracion')) {
            abort(403, 'No tienes permiso para ver esta consulta.');
        }

        return view('consultas.show', compact('consulta'));
    }

    public function edit(Consulta $consulta)
    {
        // Solo puede editarla el doctor que la creó
        if (Auth::user()->doctor->id !== $consulta->doctor_id) {
            abort(403, 'No estás autorizado para editar esta consulta.');
        }
        
        // Si tiene una cita, la pasamos para mantener la referencia
        $cita = $consulta->cita;
        $examenes = Examen::orderBy('nombre')->get();
        return view('consultas.edit', compact('consulta', 'cita', 'examenes'));

    }

    public function update(UpdateConsultaRequest $request, Consulta $consulta)
    {
        // Solo puede editarla el doctor que la creó
        if (Auth::user()->doctor->id !== $consulta->doctor_id) {
            abort(403, 'No estás autorizado para actualizar esta consulta.');
        }
        
        $consulta->update($request->validated());
        
        return redirect()->route('consultas.index')
            ->with('success', 'Consulta actualizada exitosamente.');
    }

    // Eliminación de la consulta (solo para el Admin o el doctor que la creó)
    public function destroy(Consulta $consulta)
    {
        // **INICIO DE LA CORRECCIÓN VISUAL**
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Verificación doble: solo Admin o Doctor creador.
        if ($user->doctor->id !== $consulta->doctor_id && !$user->hasPermissionTo('gestion.administracion')) {
            abort(403, 'No tienes permiso para eliminar esta consulta.');
        }
        
        // Opcional: Revertir el estado de la cita si existe
        if ($consulta->cita) {
            $consulta->cita->update(['estado' => 'Cancelada']);
        }
        
        $consulta->delete(); 
        
        return redirect()->route('consultas.index')
            ->with('success', 'Consulta eliminada correctamente.');
    }
}
