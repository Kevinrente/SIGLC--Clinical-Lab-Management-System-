<?php

namespace App\Http\Controllers;

use App\Models\OrdenExamen;
use Illuminate\Http\Request; // Importante: Clase correcta
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\ResultadoExamenListo;
use App\Services\AIService;

class LaboratorioController extends Controller
{
    public function index()
    {
        $ordenes = OrdenExamen::with(['paciente', 'doctor.usuario', 'examenes']) 
            ->whereIn('estado', ['Solicitado', 'Muestra Tomada', 'En Análisis'])
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'pendientes_page');

        $historial = OrdenExamen::with(['paciente', 'doctor.usuario', 'examenes'])
            ->where('estado', 'Finalizado')
            ->orderBy('updated_at', 'desc') 
            ->paginate(10, ['*'], 'historial_page');
        
        return view('laboratorio.index', compact('ordenes', 'historial'));
    }

    public function editResultado(OrdenExamen $ordenExamen) 
    {
        if ($ordenExamen->estado == 'Finalizado') {
            return redirect()->route('laboratorio.index')->with('error', 'Esta orden ya fue finalizada.');
        }
        return view('laboratorio.subir_resultado', ['orden' => $ordenExamen]);
    }

    /**
     * MÉTODO UPDATE (Modificado para Descarga Directa del PDF)
     */
    public function update(\Illuminate\Http\Request $request, $id)
    {
        $ordenExamen = OrdenExamen::findOrFail($id);

        $request->validate([
            'resultados' => 'required|array',
            'observaciones' => 'nullable|array',
            'observacion_general' => 'nullable|string',
        ]);

        // Variables externas para capturar la ruta del PDF dentro de la transacción
        $rutaParaDescarga = null;
        $nombreParaDescarga = null;

        try {
            // Usamos 'use (..., &$rutaParaDescarga)' para poder modificar la variable desde dentro
            DB::transaction(function () use ($request, $ordenExamen, &$rutaParaDescarga, &$nombreParaDescarga) {
                
                // 1. Guardar Resultados
                foreach ($request->input('resultados') as $examenId => $valores) {
                    $observacion = $request->input("observaciones.$examenId");
                    $ordenExamen->examenes()->updateExistingPivot($examenId, [
                        'resultado' => json_encode($valores),
                        'observaciones' => $observacion,
                        'updated_at' => now()
                    ]);
                }

                // 2. Descuento de Inventario (Solo si no estaba finalizada antes)
                if ($ordenExamen->estado !== 'Finalizado') {
                    foreach ($ordenExamen->examenes as $examen) {
                        $examen->load('insumos');
                        foreach ($examen->insumos as $insumo) {
                            $cantidad = (float) $insumo->pivot->cantidad_necesaria;
                            if ($cantidad > 0) {
                                $insumo->decrement('stock_actual', $cantidad);
                            }
                        }
                    }
                }

                // 3. IA / Observación General
                if ($request->filled('observacion_general')) {
                    $ordenExamen->analisis_ia = $request->input('observacion_general');
                    $ordenExamen->save();
                } else {
                    // Intento automático (opcional)
                    try {
                        $aiService = new \App\Services\AIService();
                        $ordenExamen->refresh();
                        $ordenExamen->load(['examenes', 'paciente']);
                        $analisis = $aiService->analizarResultados($ordenExamen);
                        if ($analisis) {
                            $ordenExamen->analisis_ia = $analisis;
                            $ordenExamen->save();
                        }
                    } catch (\Exception $e) {}
                }

                // 4. Generación y Guardado del PDF
                $ordenExamen->refresh(); 
                $ordenExamen->load(['examenes', 'paciente', 'doctor']); // Recargar datos frescos
                
                $pdf = Pdf::loadView('pdf.resultado', ['orden' => $ordenExamen]);
                
                // Definimos nombre y ruta
                $nombreParaDescarga = 'Resultado_' . $ordenExamen->paciente->apellido . '_' . $ordenExamen->id . '.pdf';
                $rutaParaDescarga = 'laboratorio/resultados/' . $nombreParaDescarga;
                
                // Guardamos en disco
                Storage::disk('local')->put($rutaParaDescarga, $pdf->output());
                
                // Calculamos hash
                $hashIntegridad = hash('sha256', $pdf->output());

                // 5. Finalizar Orden en BD
                $ordenExamen->update([
                    'estado' => 'Finalizado',
                    'ruta_resultado_pdf' => $rutaParaDescarga,
                    'hash_integridad' => $hashIntegridad
                ]);
            });

            // 6. Enviar Correo (Fuera de la transacción para no bloquear)
            if ($ordenExamen->paciente->email) {
                try {
                    Mail::to($ordenExamen->paciente->email)->send(new ResultadoExamenListo($ordenExamen));
                } catch (\Exception $e) {}
            }

            // 7. DESCARGAR PDF (Aquí está la magia)
            if ($rutaParaDescarga && Storage::disk('local')->exists($rutaParaDescarga)) {
                return Storage::download($rutaParaDescarga, $nombreParaDescarga);
            }

            // Si falla la descarga, volvemos al index
            return redirect()->route('laboratorio.index')->with('success', 'Orden finalizada correctamente.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    public function downloadResultado(OrdenExamen $ordenExamen)
    {
        $user = Auth::user();
        $esPersonalMedico = $user->hasPermissionTo('gestion.laboratorio') || $user->hasPermissionTo('gestion.administracion');
        $esElPaciente = $ordenExamen->paciente && ($ordenExamen->paciente->user_id === $user->id);

        if (!$esPersonalMedico && !$esElPaciente) abort(403);
        if (!$ordenExamen->ruta_resultado_pdf || !Storage::disk('local')->exists($ordenExamen->ruta_resultado_pdf)) return back()->with('error', 'Archivo no encontrado.');

        $nombreDescarga = 'Resultado_' . $ordenExamen->paciente->apellido . '.pdf';
        return Storage::download($ordenExamen->ruta_resultado_pdf, $nombreDescarga);
    }

    // === VERSIÓN BLINDADA (Interpretar Resultados IA) ===
    public function interpretarResultados(\Illuminate\Http\Request $request) 
    {
        try {
            // 1. Validar datos
            $request->validate([
                'resultados' => 'required|array', 
                'paciente_info' => 'required|string'
            ]);

            // 2. Verificar si la clase existe antes de usarla
            if (!class_exists(\App\Services\AIService::class)) {
                throw new \Exception("No encuentro la clase App\Services\AIService. Revisa el nombre del archivo.");
            }

            // 3. Instanciar servicio
            $ai = new \App\Services\AIService();

            // 4. Verificar si el método existe
            if (!method_exists($ai, 'generarConclusionTecnica')) {
                throw new \Exception("El método 'generarConclusionTecnica' no está en AIService.php.");
            }

            // 5. Ejecutar
            $conclusion = $ai->generarConclusionTecnica(
                $request->resultados, 
                $request->paciente_info
            );

            // Si la IA devolvió error de string (ej: "API Key no configurada")
            if (str_starts_with($conclusion, 'Error')) {
                throw new \Exception($conclusion);
            }

            return response()->json(['conclusion' => $conclusion]);

        } catch (\Exception $e) {
            // Esto enviará el mensaje exacto del error al navegador
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}