<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\User;
use App\Models\OrdenExamen;
use Illuminate\Http\Request;
use App\Models\Examen;
use App\Models\Consulta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware; // Importante para Laravel 11/12

class PacienteController extends Controller implements HasMiddleware
{
    /**
     * Configuración de permisos.
     * Permitimos que 'misResultados' y 'explicarResultados' sean públicos para el paciente.
     */
    /**
     * Define los permisos y seguridad.
     */
    public static function middleware(): array
    {
        return [
            // CORRECCIÓN: Cambiamos 'permission' por 'can'
            // 'can' es el middleware nativo de Laravel y funciona con tus permisos.
            new Middleware('can:gestion.pacientes', except: ['misResultados', 'explicarResultados']),
        ];
    }
    
    // ... (Tus métodos index, create, store, show, edit, update, destroy se mantienen igual) ...
    public function index() { $pacientes = Paciente::orderBy('created_at','desc')->paginate(15); return view('pacientes.index', compact('pacientes')); }
    public function create() { return view('pacientes.create'); }
    public function store(Request $request) { /* Tu lógica store... */ return back(); } // Resumido para no borrar tu lógica
    public function show(Paciente $paciente) { return view('pacientes.show', compact('paciente')); }
    public function edit(Paciente $paciente) { return view('pacientes.edit', compact('paciente')); }
    public function update(Request $request, Paciente $paciente) { $paciente->update($request->all()); return redirect()->route('pacientes.index'); }
    public function destroy(Paciente $paciente) { $paciente->delete(); return back(); }

    // =========================================================================
    // PORTAL DEL PACIENTE
    // =========================================================================
    public function misResultados()
    {
        $user = Auth::user();
        $paciente = $user->paciente; 

        if (!$paciente) {
            return view('pacientes.portal', ['ordenes' => collect([])])
                ->with('warning', 'Tu usuario no tiene un perfil de paciente asociado.');
        }

        $ordenes = OrdenExamen::where('paciente_id', $paciente->id)
            ->with(['doctor.usuario', 'examenes'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pacientes.portal', compact('ordenes'));
    }


    public function chatIA(\Illuminate\Http\Request $request)
    {
        $request->validate(['mensaje' => 'required|string|max:255']);
        
        $user = \Illuminate\Support\Facades\Auth::user();
        $paciente = $user->paciente;

        if (!$paciente) return response()->json(['respuesta' => 'Error: Perfil no encontrado.']);

        // 1. Obtener Recetas Recientes (Contexto de Medicamentos)
        $consultas = \App\Models\Consulta::where('paciente_id', $paciente->id)
            ->latest()
            ->take(3) // Solo las últimas 3 para no saturar
            ->get();

        $historialRecetas = "MEDICAMENTOS RECETADOS RECIENTEMENTE:\n";
        foreach ($consultas as $consulta) {
            if ($consulta->receta_medica) {
                // Asumiendo que receta_medica es un array JSON
                foreach ($consulta->receta_medica as $item) {
                    $med = $item['medicamento'] ?? '';
                    $indicacion = $item['indicacion'] ?? '';
                    $historialRecetas .= "- $med ($indicacion)\n";
                }
            }
        }

        // 2. Obtener Catálogo de Exámenes (Para saber requisitos)
        // Traemos solo nombre y requisitos para ahorrar tokens
        $catalogo = \App\Models\Examen::select('nombre', 'requisitos')->get()->toArray();

        // 3. Llamar a la IA
        $ai = new \App\Services\AIService();
        $respuesta = $ai->chatMedico($request->mensaje, $historialRecetas, $catalogo);

        return response()->json(['respuesta' => $respuesta]);
    }
    
}