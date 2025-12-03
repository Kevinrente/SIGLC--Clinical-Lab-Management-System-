<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Consulta;
use App\Models\OrdenExamen;
use App\Models\Pago;  
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    /**
     * REPORTE 1: Dashboard Financiero General (Ingresos vs Gastos)
     * CORRECCIÓN 1: Agregamos el método index que faltaba.
     */
    public function index(Request $request)
    {
        $inicio = $request->input('inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fin = $request->input('fin', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // CORRECCIÓN 2: Usamos 'monto_total' en lugar de 'monto' (Error SQL solucionado)
        $totalIngresos = Pago::whereBetween('created_at', [$inicio . ' 00:00:00', $fin . ' 23:59:59'])
                             ->sum('monto_total');

        $totalGastos = 0;
        if (class_exists(\App\Models\Gasto::class)) {
            // Asumimos que Gasto sí tiene 'monto'. Si falla, cámbialo a 'monto_total' también.
            $totalGastos = \App\Models\Gasto::whereBetween('created_at', [$inicio . ' 00:00:00', $fin . ' 23:59:59'])
                                ->sum('monto');
        }

        $balance = $totalIngresos - $totalGastos;

        // Gráfico
        $ingresosPorDia = Pago::select(DB::raw('DATE(created_at) as fecha'), DB::raw('SUM(monto_total) as total'))
            ->whereBetween('created_at', [$inicio . ' 00:00:00', $fin . ' 23:59:59'])
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        return view('reportes.index', compact('totalIngresos', 'totalGastos', 'balance', 'inicio', 'fin', 'ingresosPorDia'));
    }

    /**
     * REPORTE 2: Honorarios Médicos
     */
    public function honorarios(Request $request)
    {
        $inicio = $request->input('inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fin = $request->input('fin', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $doctores = Doctor::with('usuario')->get();
        $reporte = [];

        foreach ($doctores as $doctor) {
            
            // A. CONSULTAS (Usamos whereHas('pago') para asegurar que está pagada)
            $consultas = Consulta::where('doctor_id', $doctor->id)
                ->whereHas('pago') // CORRECCIÓN 3: Verificamos relación en vez de columna 'pagado'
                ->whereBetween('created_at', [$inicio . ' 00:00:00', $fin . ' 23:59:59'])
                ->with('pago')
                ->get();

            $totalVentaConsultas = $consultas->sum(function($c) {
                return $c->pago ? $c->pago->monto_total : 0;
            });

            $pagoPorConsultas = $totalVentaConsultas * 0.70; 

            // B. LABORATORIO
            $ordenes = OrdenExamen::where('doctor_id', $doctor->id)
                ->whereHas('pago') // CORRECCIÓN 3: Verificamos relación en vez de columna 'pagado'
                ->whereBetween('created_at', [$inicio . ' 00:00:00', $fin . ' 23:59:59'])
                ->with('pago')
                ->get();

            $totalVentaLab = $ordenes->sum(function($o) {
                return $o->pago ? $o->pago->monto_total : 0;
            });

            $comisionLab = 0;
            if ($doctor->comision_lab_tipo == 'porcentaje') {
                $comisionLab = $totalVentaLab * ($doctor->comision_lab_valor / 100);
            } else {
                $comisionLab = $ordenes->count() * $doctor->comision_lab_valor;
            }

            // C. TOTALES
            $reporte[] = [
                'doctor' => $doctor->usuario->name,
                'especialidad' => $doctor->especialidad,
                'consultas_count' => $consultas->count(),
                'pago_consultas' => $pagoPorConsultas,
                'ordenes_count' => $ordenes->count(),
                'pago_laboratorio' => $comisionLab,
                'config_lab' => $doctor->comision_lab_tipo == 'porcentaje' ? $doctor->comision_lab_valor.'%' : '$'.$doctor->comision_lab_valor,
                'total_a_pagar' => $pagoPorConsultas + $comisionLab,
            ];
        }

        return view('reportes.honorarios', compact('reporte', 'inicio', 'fin'));
    }
}