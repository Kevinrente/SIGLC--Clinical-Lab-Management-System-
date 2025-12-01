<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;
use App\Models\Cita;
use App\Models\Pago;
use App\Models\OrdenExamen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. REDIRECCIÓN DE PACIENTES (Mantenemos tu lógica anterior)
        if ($user->paciente) {
            return redirect()->route('pacientes.portal');
        }

        // 2. KPIS GENERALES (Tarjetas de arriba)
        $totalPacientes = Paciente::count();
        $totalCitasHoy = Cita::whereDate('fecha_hora', now()->toDateString())
                             ->where('estado', 'Pendiente')
                             ->count();
        $ordenesPendientes = OrdenExamen::whereIn('estado', ['Solicitado', 'Muestra Tomada'])->count();
        
        // 3. GRÁFICO 1: INGRESOS MENSUALES (Últimos 6 meses)
        // Usamos TO_CHAR para PostgreSQL. Si usaras MySQL sería DATE_FORMAT.
        $ingresosData = Pago::select(
                DB::raw("TO_CHAR(created_at, 'YYYY-MM') as mes"), 
                DB::raw('SUM(monto_total) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes', 'asc')
            ->get();

        // 4. GRÁFICO 2: EXÁMENES MÁS VENDIDOS (Top 5)
        $topExamenes = DB::table('orden_examen_examen')
            ->join('examens', 'orden_examen_examen.examen_id', '=', 'examens.id')
            ->select('examens.nombre', DB::raw('count(*) as total'))
            ->groupBy('examens.nombre')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // 5. GRÁFICO 3: ESTADO DE CITAS (Pastel)
        $estadoCitas = Cita::select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get();

        return view('dashboard', compact(
            'totalPacientes', 
            'totalCitasHoy', 
            'ordenesPendientes',
            'ingresosData',
            'topExamenes',
            'estadoCitas'
        ));
    }
}