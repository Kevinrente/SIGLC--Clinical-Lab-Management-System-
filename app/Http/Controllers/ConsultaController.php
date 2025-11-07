<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\Cita; 
use App\Http\Requests\StoreConsultaRequest;
use App\Http\Requests\UpdateConsultaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Examen;
use App\Models\OrdenExamen; 
use Illuminate\Support\Facades\DB; // AGREGAR: Necesario para transacciones

class ConsultaController extends Controller
{
    // ... (middleware, index, createFromCita son CORRECTOS, pero necesitan el load) ...

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
    
    // ... (index method) ...

    // Ruta personalizada para iniciar la consulta desde una Cita
    public function createFromCita(Cita $cita)
    {
        // 1. Verificación de seguridad
        if (!Auth::user()->doctor || Auth::user()->doctor->id !== $cita->doctor_id) {
             abort(403, 'No estás autorizado para registrar consultas de esta cita.');
        }

        // 2. Verificar que la cita no tenga ya una consulta asociada
        if ($cita->consulta) {
            return redirect()->route('consultas.edit', $cita->consulta)
                             ->with('error', 'Esta cita ya tiene una consulta registrada. Se redirigió a edición.');
        }

        // 3. Marcar la cita como Completada
        // Esto debería hacerse DESPUÉS de guardar la consulta, pero mantenemos la lógica aquí.
        $cita->update(['estado' => 'Completada']); 

        $examenes = Examen::orderBy('nombre')->get();
        
        return view('consultas.create', compact('cita', 'examenes'));
    }

    public function store(StoreConsultaRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();
        
        // CORRECCIÓN 1: Evitar el error de user->doctor->id si no hay doctor asociado
        if (!$user->doctor) {
            abort(403, 'Error de usuario: Su cuenta no está vinculada a un perfil de doctor.');
        }
        
        // Usamos una transacción por si la creación de la consulta pasa, pero la de órdenes falla.
        DB::beginTransaction();

        try {
            // 1. Crear la consulta
            $consulta = Consulta::create($validated + [
                'doctor_id' => $user->doctor->id,
            ]);

            // 2. Procesar y crear las Órdenes de Examen (Si existen)
            if (!empty($request->examenes_solicitados)) {
                $ordenesData = [];
                $doctorId = $user->doctor->id;
                $pacienteId = $request->paciente_id;
                $citaId = $request->cita_id; // Obtenemos la cita_id del request

                // CORRECCIÓN 2: El formulario de create.blade.php no envía un array de IDs, sino un string de texto
                // Esta lógica DEBE ser movida al OrdenExamenController::store o simplificada.
                // Si el Doctor usa el botón "Generar Orden de Examen" DEBE ser después de registrar la consulta.

                // Lógica de Órdenes a eliminar si se usa el botón de "Generar Orden de Examen"
                // Si se usa el botón, NO es necesario crear la orden aquí. 

                // Si el formulario de consulta incluye el campo 'examenes_solicitados' como TEXTO:
                if (!empty($validated['examenes_solicitados'])) {
                    OrdenExamen::create([
                        'cita_id' => $citaId,
                        'doctor_id' => $doctorId,
                        'paciente_id' => $pacienteId,
                        'examenes_solicitados' => $validated['examenes_solicitados'],
                        'estado' => 'Solicitado',
                    ]);
                }
            }
            
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al guardar la consulta y órdenes: ' . $e->getMessage());
        }


        return redirect()->route('consultas.index')
            ->with('success', 'Consulta registrada exitosamente.');
    }
    
    // ... (show, edit, update, destroy methods) ...
}