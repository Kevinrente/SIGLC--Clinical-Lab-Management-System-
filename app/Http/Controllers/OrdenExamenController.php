<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\OrdenExamen;
use App\Http\Requests\StoreOrdenExamenRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasOne; // <-- AGREGADO: Para hinting en el user->doctor

class OrdenExamenController extends Controller
{
    /**
     * Aplica el middleware de permisos usando el método estático.
     */
    public static function middleware(): array
    {
        // El permiso 'gestion.consultas' está asignado al Doctor.
        return [
            'can:gestion.consultas', 
        ];
    }
    
    // Muestra el formulario de creación (generalmente desde la vista de la cita)
    public function create(Cita $cita)
    {
        // 1. Obtener el usuario y tipar para evitar errores de IDE
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Carga anticipada de las relaciones necesarias para la vista y la lógica
        $cita->load('paciente', 'doctor'); 
        
        // CORRECCIÓN 1: Unificar la verificación del Doctor logueado
        
        // Verifica si el usuario autenticado tiene un registro Doctor vinculado
        // Y verifica si el ID del doctor logueado es el mismo que el de la cita
        if (!$user->doctor || $user->doctor->id !== $cita->doctor_id) {
             abort(403, 'No tienes permiso para generar órdenes para esta cita o tu cuenta no está vinculada a un perfil de doctor.');
        }
        
        // La vista de creación requiere que se pase el objeto Cita
        return view('ordenes.create', compact('cita'));
    }

    // Lógica para guardar la orden de examen
    public function store(StoreOrdenExamenRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $validated = $request->validated();
        
        // La validación ya asegura que la cita_id existe.
        $cita = Cita::findOrFail($validated['cita_id']);
        
        // CORRECCIÓN 2: Eliminamos la verificación redundante
        // La autorización RBAC ('can:gestion.consultas') y el StoreOrdenExamenRequest::authorize()
        // ya validan el permiso y el doctor asignado, haciendo esta verificación redundante.
        /*
        if ($user->doctor->id !== $cita->doctor_id) {
            return back()->with('error', 'La orden solo puede ser emitida por el doctor asignado a la cita.');
        }
        */

        // 1. Crear la Orden de Examen
        // CORRECCIÓN 3: Usamos el ID del doctor logueado (que ya sabemos que es el correcto)
        $doctorModel = $user->doctor; 

        OrdenExamen::create([
            'cita_id' => $cita->id,
            'doctor_id' => $doctorModel->id, // Usamos el ID del modelo Doctor
            'paciente_id' => $cita->paciente_id,
            'examenes_solicitados' => $validated['examenes_solicitados'],
            'estado' => 'Solicitado', 
        ]);

        // 2. Redirigir al detalle de la cita
        return redirect()->route('citas.show', $cita)->with('success', '¡Orden de examen generada con éxito y enviada al Laboratorio!');
    }
    
    // ... (otros métodos) ...
}