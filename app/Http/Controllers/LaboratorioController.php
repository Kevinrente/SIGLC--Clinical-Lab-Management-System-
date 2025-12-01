<?php

namespace App\Http\Controllers;

use App\Models\OrdenExamen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResultadoExamenListo;

class LaboratorioController extends Controller
{
    /**
     * Dashboard del laboratorio (Bandeja de entrada)
     */
    public function index()
    {
        // Lista 1: Pendientes
        $ordenes = OrdenExamen::with(['paciente', 'doctor.usuario', 'examenes']) 
            ->whereIn('estado', ['Solicitado', 'Muestra Tomada', 'En Análisis'])
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'pendientes_page');

        // Lista 2: Historial (Finalizados)
        $historial = OrdenExamen::with(['paciente', 'doctor.usuario', 'examenes'])
            ->where('estado', 'Finalizado')
            ->orderBy('updated_at', 'desc') 
            ->paginate(10, ['*'], 'historial_page');
        
        return view('laboratorio.index', compact('ordenes', 'historial'));
    }

    /**
     * Muestra el formulario "Inteligente" para cargar resultados
     */
    public function editResultado(OrdenExamen $ordenExamen) 
    {
        if ($ordenExamen->estado == 'Finalizado') {
            return redirect()->route('laboratorio.index')->with('error', 'Esta orden ya fue finalizada.');
        }
        
        return view('laboratorio.subir_resultado', ['orden' => $ordenExamen]);
    }

    /**
     * PROCESO CORE: Guarda datos JSON -> Genera PDF -> Finaliza Orden -> ENVIA EMAIL
     */
    public function update(Request $request, $id)
    {
        $ordenExamen = OrdenExamen::findOrFail($id);

        // 1. Validación
        $request->validate([
            'resultados' => 'required|array',
            'observaciones' => 'nullable|array',
        ]);

        try {
            // 2. Guardar los datos crudos en la Base de Datos (JSON)
            foreach ($request->input('resultados') as $examenId => $valores) {
                $observacion = $request->input("observaciones.$examenId");

                $ordenExamen->examenes()->updateExistingPivot($examenId, [
                    'resultado' => json_encode($valores),
                    'observaciones' => $observacion,
                    'updated_at' => now()
                ]);
            }

            // 3. GENERACIÓN DEL PDF AUTOMÁTICO
            $ordenExamen->load(['examenes', 'paciente', 'doctor']);

            $pdf = Pdf::loadView('pdf.resultado', ['orden' => $ordenExamen]);
            
            $nombreArchivo = 'resultado_orden_' . $ordenExamen->id . '_' . time() . '.pdf';
            $rutaGuardado = 'laboratorio/resultados/' . $nombreArchivo;

            Storage::disk('local')->put($rutaGuardado, $pdf->output());

            $hashIntegridad = hash('sha256', $pdf->output());

            // 4. Finalizar la Orden en BD
            $ordenExamen->update([
                'estado' => 'Finalizado',
                'ruta_resultado_pdf' => $rutaGuardado,
                'hash_integridad' => $hashIntegridad
            ]);

            // 5. ENVÍO DE CORREO AUTOMÁTICO
            if ($ordenExamen->paciente->email) {
                try {
                    Mail::to($ordenExamen->paciente->email)->send(new ResultadoExamenListo($ordenExamen));
                } catch (\Exception $e) {
                    // Si falla el correo, no detenemos el proceso, solo avisamos (opcional loguear el error)
                    // Log::error('Fallo envío correo: ' . $e->getMessage());
                }
            }

            return redirect()->route('laboratorio.index')
                ->with('success', 'Resultados guardados, PDF generado y notificación enviada.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar resultados: ' . $e->getMessage());
        }
    }

    /**
     * Descarga del PDF
     */
    public function downloadResultado(OrdenExamen $ordenExamen)
    {
        $user = Auth::user();

        $esPersonalMedico = $user->hasPermissionTo('gestion.laboratorio') || $user->hasPermissionTo('gestion.administracion');
        $esElPaciente = $ordenExamen->paciente && ($ordenExamen->paciente->user_id === $user->id);

        if (!$esPersonalMedico && !$esElPaciente) {
            abort(403, 'No tienes permiso para ver este resultado.');
        }

        if (!$ordenExamen->ruta_resultado_pdf || !Storage::disk('local')->exists($ordenExamen->ruta_resultado_pdf)) {
            return back()->with('error', 'El archivo no se encuentra en el servidor.');
        }

        $nombreDescarga = 'Resultado_' . $ordenExamen->paciente->apellido . '_' . $ordenExamen->created_at->format('Ymd') . '.pdf';
        return Storage::download($ordenExamen->ruta_resultado_pdf, $nombreDescarga);
    }
}