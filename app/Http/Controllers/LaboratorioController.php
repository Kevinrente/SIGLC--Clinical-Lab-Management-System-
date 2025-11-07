<?php

namespace App\Http\Controllers;

use App\Models\OrdenExamen;
use App\Models\Examen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException; // Necesario para la excepción

class LaboratorioController extends Controller
{
    /**
     * Aplica el middleware de permisos.
     */
    public static function middleware(): array
    {
        return [
            // CORRECCIÓN 1: Simplificamos el array de middlewares. 
            // El Doctor no necesita este permiso, solo Laboratorio/Admin.
            'permission:gestion.laboratorio|gestion.administracion',
        ];
    }
    
    public function index()
    {
        // El Laboratorio solo ve órdenes que están solicitadas o en proceso.
        // Se carga la relación 'doctor.usuario' para obtener el nombre del solicitante
        $ordenes = OrdenExamen::with(['paciente', 'doctor.usuario']) 
            ->whereIn('estado', ['Solicitado', 'Muestra Tomada', 'En Análisis'])
            ->latest()
            ->paginate(20);
        
        return view('laboratorio.index', compact('ordenes'));
    }

    public function editResultado(OrdenExamen $ordenExamen)
    {
        if ($ordenExamen->estado == 'Finalizado') {
            return redirect()->route('laboratorio.index')->with('error', 'El resultado ya fue subido.');
        }
        
        // CORRECCIÓN 2: El nombre de la vista debe coincidir con el archivo subido anteriormente
        return view('laboratorio.subir_resultado', compact('ordenExamen'));
    }

    // Lógica para la carga segura del resultado (PDF)
    public function storeResultado(Request $request, OrdenExamen $ordenExamen)
    {
        // CORRECCIÓN 3: El campo del formulario es 'resultado_file' (según la vista)
        $request->validate([
            'resultado_file' => ['required_if:estado_actual,Finalizado', 'file', 'mimes:pdf,jpg,png', 'max:5120'], // 5MB máx.
            'estado_actual' => ['required', 'in:Muestra Tomada,En Análisis,Finalizado'],
        ]);
        
        // Si el estado es 'Finalizado', el archivo es obligatorio.
        if ($request->estado_actual === 'Finalizado' && !$request->hasFile('resultado_file')) {
            throw ValidationException::withMessages(['resultado_file' => 'Debe adjuntar el archivo de resultado al finalizar la orden.']);
        }

        $filePath = $ordenExamen->ruta_resultado_pdf;
        $hashIntegridad = $ordenExamen->hash_integridad;

        try {
            if ($request->hasFile('resultado_file')) {
                $file = $request->file('resultado_file');
                $nombreAlmacenado = 'resultado_' . $ordenExamen->id . '_' . time() . '.pdf'; // Usar time() o uniqid()

                // 1. Almacenamiento SEGURO y PRIVADO (usando el disco 'local', que por defecto es storage/app)
                $rutaArchivo = $file->storeAs('laboratorio/resultados', $nombreAlmacenado, 'local'); 

                // 2. Cálculo del Hash de Integridad
                $rutaFisica = Storage::disk('local')->path($rutaArchivo);
                $hashIntegridad = hash_file('sha256', $rutaFisica);
                $filePath = $rutaArchivo;
            }

            // 3. Actualizar la Orden
            $ordenExamen->update([
                'estado' => $request->estado_actual,
                'ruta_resultado_pdf' => $filePath,
                'hash_integridad' => $hashIntegridad,
            ]);

        } catch (\Exception $e) {
            // Si el almacenamiento falló, no debemos continuar
            return redirect()->route('laboratorio.index')->with('error', 'Error crítico al procesar el archivo. Detalle: ' . $e->getMessage());
        }

        return redirect()->route('laboratorio.index')
            ->with('success', "Orden #{$ordenExamen->id} actualizada a '{$request->estado_actual}'.");
    }

    // Permite la DESCARGA SEGURA del resultado
    public function downloadResultado(OrdenExamen $ordenExamen)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user(); 

        // Lógica de acceso: Doctor/Admin, Técnico (todos ellos tienen permisos o roles)
        $isAuthorized = $user->hasPermissionTo('lectura.historial') || // Doctores y Admin
                        $user->hasPermissionTo('gestion.laboratorio'); // Técnicos

        // Si el usuario es el paciente, debe poder descargar su resultado.
        // Asumiendo que el modelo User tiene una relación 'paciente' (user->paciente)
        if ($ordenExamen->paciente && ($ordenExamen->paciente->user_id === $user->id)) {
             $isAuthorized = true;
        }

        if (!$isAuthorized) {
            abort(403, 'Acceso no autorizado al resultado clínico.');
        }

        $rutaArchivo = $ordenExamen->ruta_resultado_pdf;
        
        if (!Storage::disk('local')->exists($rutaArchivo)) {
            return back()->with('error', 'El archivo de resultado no fue encontrado.');
        }

        // 2. Servir el archivo de forma segura con un nombre legible
        // CORRECCIÓN 4: Ya no hay $ordenExamen->examen->nombre (se eliminó la relación)
        $nombreDescarga = 'Resultado_' . $ordenExamen->paciente->apellido . '_Orden' . $ordenExamen->id . '.pdf';
        return Storage::download($rutaArchivo, $nombreDescarga);
    }
}