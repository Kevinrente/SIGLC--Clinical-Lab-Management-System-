<?php

namespace App\Http\Controllers;

use App\Models\OrdenExamen;
use App\Models\Examen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaboratorioController extends Controller
{
    /**
     * Aplica el middleware de permisos.
     */
    public static function middleware(): array
    {
        return [
            // Técnico y Admin acceden al laboratorio
            'permission:gestion.laboratorio|gestion.administracion',
        ];
    }
    
    public function index()
    {
        // Muestra todas las órdenes pendientes para el personal de laboratorio
        $ordenes = OrdenExamen::with(['paciente', 'examen'])
            ->whereIn('estado', ['Solicitado', 'Muestra Tomada', 'En Análisis'])
            ->latest()
            ->paginate(20);
        
        return view('laboratorio.index', compact('ordenes'));
    }

    // Muestra el formulario para que el técnico suba el resultado
    public function editResultado(OrdenExamen $ordenExamen)
    {
        if ($ordenExamen->estado == 'Finalizado') {
            return redirect()->route('laboratorio.index')->with('error', 'El resultado ya fue subido.');
        }
        return view('laboratorio.subir-resultado', compact('ordenExamen'));
    }

    // Lógica para la carga segura del resultado (PDF)
    public function storeResultado(Request $request, OrdenExamen $ordenExamen)
    {
        $request->validate([
            'resultado_pdf' => ['required', 'file', 'mimes:pdf', 'max:10240'], // Solo PDF, 10MB máx.
        ]);

        $file = $request->file('resultado_pdf');
        $nombreAlmacenado = 'resultado_' . $ordenExamen->id . '_' . uniqid() . '.pdf';
        $rutaCarpeta = "laboratorio/resultados";

        // 1. Almacenamiento SEGURO y PRIVADO
        $rutaArchivo = $file->storeAs($rutaCarpeta, $nombreAlmacenado, 'local'); 

        // 2. Cálculo del Hash de Integridad
        $rutaFisica = Storage::disk('local')->path($rutaArchivo);
        $hashIntegridad = hash_file('sha256', $rutaFisica);

        // 3. Actualizar la Orden
        $ordenExamen->update([
            'estado' => 'Finalizado',
            'ruta_resultado_pdf' => $rutaArchivo,
            'hash_integridad' => $hashIntegridad,
        ]);

        return redirect()->route('laboratorio.index')
            ->with('success', 'Resultado del examen subido y protegido exitosamente. Hash registrado.');
    }

    // Permite la DESCARGA SEGURA del resultado
    public function downloadResultado(OrdenExamen $ordenExamen)
    {
        // 1. **CORRECCIÓN VISUAL:** Tipar la variable $user para que el IDE reconozca los métodos de Spatie.
        /** @var \App\Models\User $user */
        $user = Auth::user(); 

        // Lógica de acceso: Doctor/Admin, Técnico O el Paciente (si su cuenta de User está vinculada)
        if (
            !$user->hasPermissionTo('lectura.historial') && // Doctores y Admin
            !$user->hasPermissionTo('gestion.laboratorio') && // Técnicos
            // Lógica Paciente: Si el usuario es un paciente, verifica si la ID coincide con la orden
            !($user->paciente && $user->paciente->id === $ordenExamen->paciente_id)
        ) {
            abort(403, 'No tienes permiso para ver este resultado clínico.');
        }

        $rutaArchivo = $ordenExamen->ruta_resultado_pdf;
        
        if (!Storage::disk('local')->exists($rutaArchivo)) {
            return back()->with('error', 'El archivo de resultado no fue encontrado.');
        }

        // 2. Servir el archivo de forma segura con un nombre legible
        $nombreDescarga = 'Resultado_' . $ordenExamen->examen->nombre . '_' . $ordenExamen->paciente->apellido . '.pdf';
        return Storage::download($rutaArchivo, $nombreDescarga);
    }
}