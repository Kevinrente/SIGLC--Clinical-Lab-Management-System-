<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\OrdenExamen;
use App\Models\Examen; // <-- Importar el modelo del catálogo
use App\Http\Requests\StoreOrdenExamenRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrdenExamenController extends Controller
{
    public static function middleware(): array
    {
        return [
            'can:gestion.consultas', 
        ];
    }
    
    public function create(Cita $cita)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Validaciones de seguridad (Igual que antes)
        if (!$user->doctor || $user->doctor->id !== $cita->doctor_id) {
             abort(403, 'No tienes permiso para generar órdenes para esta cita.');
        }
        
        $cita->load('paciente', 'doctor');

        // 2. CARGAR EL CATÁLOGO DE EXÁMENES
        // Obtenemos todos y los agrupamos por categoría para la vista
        // Esto genera una colección tipo: ['Hematología' => [Examen1, Examen2], 'Química' => [...]]
        $examenesPorCategoria = Examen::orderBy('nombre')->get()->groupBy('categoria');
        return view('ordenes.create', compact('cita', 'examenesPorCategoria'));    
    }

    public function createDirecto(\App\Models\Paciente $paciente)
    {
        // Cargamos categorías para el formulario
        $examenesPorCategoria = \App\Models\Examen::all()->groupBy('categoria');
        
        // Cargamos lista de doctores por si quieren referir a alguien (opcional)
        $doctores = \App\Models\Doctor::with('usuario')->get();

        return view('ordenes.create_directo', compact('paciente', 'examenesPorCategoria', 'doctores'));
    }

    public function store(StoreOrdenExamenRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();

        // 1. LÓGICA DE DOCTOR INTELIGENTE
        $doctorId = null;

        // Caso A: El usuario logueado ES un doctor (está en su consultorio)
        if ($user->doctor) {
            $doctorId = $user->doctor->id;
        } 
        // Caso B: El usuario es Laboratorista/Admin y seleccionó un doctor en el select
        elseif ($request->filled('doctor_id')) {
            $doctorId = $request->doctor_id;
        }
        // Caso C: No hay doctor (Paciente particular), $doctorId se queda NULL.

        DB::beginTransaction();

        try {
            // Obtenemos nombres para el resumen de texto
            $nombresExamenes = \App\Models\Examen::whereIn('id', $validated['examenes'])->pluck('nombre')->join(', ');

            // Crear la Orden
            $orden = \App\Models\OrdenExamen::create([
                'cita_id' => $validated['cita_id'] ?? null, // Puede ser null
                'doctor_id' => $doctorId,                   // Puede ser null
                'paciente_id' => $validated['paciente_id'], // Obligatorio
                'examenes_solicitados' => $nombresExamenes, 
                'estado' => 'Solicitado',
                'pagado' => false // Por defecto no está pagado
            ]);

            // Guardar en tabla pivote
            $orden->examenes()->sync($validated['examenes']);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al guardar: ' . $e->getMessage());
        }

        // Redirección inteligente
        if(auth()->user()->hasPermissionTo('gestion.laboratorio')) {
            return redirect()->route('laboratorio.index')->with('success', 'Orden creada exitosamente.');
        }

        // Si es doctor, volvemos a la cita (si existe) o al dashboard
        return redirect()->back()->with('success', 'Orden generada correctamente.');
    }
    
    // ... otros métodos (index, show) ...
}