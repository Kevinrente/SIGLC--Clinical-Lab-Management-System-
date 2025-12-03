<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Consulta;
use App\Models\OrdenExamen;
use Carbon\Carbon;

class ReporteController extends Controller
{
    /**
     * Genera el reporte de honorarios médicos.
     */
    public function honorarios(Request $request)
    {
        // 1. Filtros de Fecha (Por defecto: Mes actual)
        $inicio = $request->input('inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fin = $request->input('fin', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // 2. Obtenemos todos los doctores
        $doctores = Doctor::with('usuario')->get();

        $reporte = [];

        foreach ($doctores as $doctor) {
            
            // --- A. CÁLCULO DE CONSULTAS MÉDICAS ---
            // Buscamos consultas de este doctor que estén PAGADAS en el rango de fecha
            $consultas = Consulta::where('doctor_id', $doctor->id)
                ->where('pagado', true)
                ->whereBetween('created_at', [$inicio . ' 00:00:00', $fin . ' 23:59:59'])
                ->with('pago')
                ->get();

            // Sumamos el total cobrado en caja por esas consultas
            $totalVentaConsultas = $consultas->sum(function($c) {
                return $c->pago ? $c->pago->monto_total : 0;
            });

            // Regla de Negocio: Doctor gana 70% de la consulta
            $pagoPorConsultas = $totalVentaConsultas * 0.70;


            // --- B. CÁLCULO DE COMISIONES DE LABORATORIO ---
            // Buscamos órdenes referidas por este doctor que estén PAGADAS
            $ordenes = OrdenExamen::where('doctor_id', $doctor->id)
                ->where('pagado', true)
                ->whereBetween('created_at', [$inicio . ' 00:00:00', $fin . ' 23:59:59'])
                ->with('pago')
                ->get();

            $totalVentaLab = $ordenes->sum(function($o) {
                return $o->pago ? $o->pago->monto_total : 0;
            });

            $comisionLab = 0;

            // Aplicamos la fórmula personalizada del doctor (Fijo o Porcentaje)
            if ($doctor->comision_lab_tipo == 'porcentaje') {
                // Ej: (Total Venta * 15) / 100
                $comisionLab = $totalVentaLab * ($doctor->comision_lab_valor / 100);
            } else {
                // Ej: 5 órdenes * $5.00 cada una
                $comisionLab = $ordenes->count() * $doctor->comision_lab_valor;
            }


            // --- C. TOTALES FINALES ---
            $totalGenerado = $totalVentaConsultas + $totalVentaLab; // Dinero que entró a la clínica gracias al doctor
            $totalA_Pagar = $pagoPorConsultas + $comisionLab;       // Dinero que la clínica le debe al doctor

            // Agregamos al reporte
            $reporte[] = [
                'doctor' => $doctor->usuario->name,
                'especialidad' => $doctor->especialidad,
                
                // Datos Consultas
                'consultas_count' => $consultas->count(),
                'pago_consultas' => $pagoPorConsultas,
                
                // Datos Laboratorio
                'ordenes_count' => $ordenes->count(),
                'pago_laboratorio' => $comisionLab,
                'config_lab' => $doctor->comision_lab_tipo == 'porcentaje' ? $doctor->comision_lab_valor.'%' : '$'.$doctor->comision_lab_valor,
                
                // Totales
                'total_generado' => $totalGenerado,
                'total_a_pagar' => $totalA_Pagar,
            ];
        }

        return view('reportes.honorarios', compact('reporte', 'inicio', 'fin'));
    }
}