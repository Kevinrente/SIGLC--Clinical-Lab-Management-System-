<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\Cita; 
use App\Http\Requests\StoreConsultaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Examen;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail; // <--- Importante para el correo
use App\Mail\RecetaMedicaMail;       // <--- Importante para la clase de correo
use Barryvdh\DomPDF\Facade\Pdf;      // <--- Importante para la descarga directa

class ConsultaController extends Controller
{
    public static function middleware(): array
    {
        // Aplicamos permiso de gestión solo a métodos de escritura
        // Excluimos 'downloadReceta' para manejar su seguridad internamente (para que el paciente pueda entrar)
        return [
            new \Illuminate\Routing\Controllers\Middleware('permission:gestion.consultas', except: ['downloadReceta']),
        ];
    }

    public function index()
    {
        $consultas = Consulta::with(['paciente', 'doctor.usuario'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('consultas.index', compact('consultas'));
    }

    public function show($id)
    {
        $consulta = Consulta::with(['paciente', 'doctor.usuario'])->findOrFail($id);
        return view('consultas.show', compact('consulta'));
    }

    public function createFromCita(Cita $cita)
    {
        if (!Auth::user()->doctor || Auth::user()->doctor->id !== $cita->doctor_id) {
             abort(403, 'No autorizado.');
        }

        if ($cita->consulta) {
            return redirect()->route('consultas.edit', $cita->consulta)
                             ->with('error', 'Esta cita ya fue atendida.');
        }

        $examenes = Examen::orderBy('nombre')->get();
        return view('consultas.create', compact('cita', 'examenes'));
    }

    public function store(StoreConsultaRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();

        // Limpieza de Receta (Filtrar filas vacías)
        $recetaLimpia = [];
        if (!empty($validated['receta'])) {
            foreach ($validated['receta'] as $item) {
                if (!empty($item['medicamento'])) {
                    $recetaLimpia[] = $item;
                }
            }
        }

        DB::beginTransaction();

        try {
            // 1. Guardar Consulta
            $consulta = Consulta::create([
                'doctor_id' => $user->doctor->id,
                'pagado' => false,
                'cita_id' => $validated['cita_id'],
                'paciente_id' => $validated['paciente_id'],
                'motivo_consulta' => $validated['motivo_consulta'],
                'exploracion_fisica' => $validated['exploracion_fisica'],
                'diagnostico_presuntivo' => $validated['diagnostico_presuntivo'],
                'diagnostico_confirmado' => $validated['diagnostico_confirmado'],
                'receta_medica' => $recetaLimpia,
            ]);

            // 2. Completar Cita
            $cita = Cita::find($request->cita_id);
            if ($cita) $cita->update(['estado' => 'Completada']);

            DB::commit();

            // === 3. ENVÍO AUTOMÁTICO DE RECETA POR CORREO ===
            // Solo si hay medicamentos recetados y el paciente tiene email
            if (!empty($recetaLimpia) && $consulta->paciente->email) {
                try {
                    Mail::to($consulta->paciente->email)->send(new RecetaMedicaMail($consulta));
                } catch (\Exception $e) {
                    // Si falla el correo, no detenemos el proceso, solo lo registramos si usas logs
                    // Log::error("Error enviando receta: " . $e->getMessage());
                }
            }
            // =================================================

            // Lógica de Redirección
            if ($request->input('action') === 'order') {
                return redirect()->route('ordenes.create', ['cita' => $request->cita_id])
                    ->with('success', 'Consulta guardada y receta enviada por correo. Ahora seleccione los exámenes.');
            }

            return redirect()->route('citas.index')
                ->with('success', 'Consulta finalizada y receta enviada por correo.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // === NUEVO MÉTODO: DESCARGAR RECETA (Para Portal Paciente y Doctor) ===
    public function downloadReceta(Consulta $consulta)
    {
        $user = Auth::user();

        // Seguridad: ¿Quién puede bajar esto?
        $esElDoctor = $user->doctor && $user->doctor->id === $consulta->doctor_id;
        $esElPaciente = $user->paciente && $user->paciente->id === $consulta->paciente_id;
        $esAdmin = $user->hasRole('admin') || $user->can('gestion.administracion');

        if (!$esElDoctor && !$esElPaciente && !$esAdmin) {
            abort(403, 'No tienes permiso para descargar esta receta.');
        }

        // Generar PDF al vuelo
        $pdf = Pdf::loadView('pdf.receta_medica', ['consulta' => $consulta]);
        
        return $pdf->download('Receta_Medica_' . $consulta->id . '.pdf');
    }
}