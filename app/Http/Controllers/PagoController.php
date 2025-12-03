<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdenExamen;
use App\Models\Consulta;
use App\Models\Pago;
use App\Models\CajaSesion; // <--- IMPORTANTE
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PagoController extends Controller
{
    // ... createForOrden ...
    public function createForOrden(OrdenExamen $orden)
    {
        if ($orden->pagado) {
            return redirect()->route('laboratorio.index')->with('warning', 'Esta orden ya fue pagada.');
        }
        $orden->load(['examenes', 'paciente']);
        $total = $orden->examenes->sum('precio');
        return view('pagos.create_orden', compact('orden', 'total'));
    }

    public function storeOrden(Request $request)
    {
        $request->validate([
            'orden_id' => 'required|exists:orden_examens,id',
            'metodo_pago' => 'required|string',
            'monto_total_final' => 'required|numeric|min:0',
            'precios' => 'required|array',
            'descuento_porcentaje' => 'nullable|numeric|min:0|max:100'
        ]);

        // 1. Verificar Caja Abierta
        $sesion = CajaSesion::where('user_id', Auth::id())->where('estado', 'Abierta')->first();

        $orden = DB::transaction(function () use ($request, $sesion) {
            $orden = OrdenExamen::findOrFail($request->orden_id);
            
            // Actualizar precios
            foreach ($request->precios as $examenId => $precio) {
                $orden->examenes()->updateExistingPivot($examenId, ['precio_cobrado' => $precio]);
            }

            // Crear Pago con Sesión
            $orden->pago()->create([
                'caja_sesion_id' => $sesion ? $sesion->id : null, // <--- AQUÍ ESTÁ LA MAGIA
                'monto_total' => $request->monto_total_final,
                'metodo_pago' => $request->metodo_pago,
                'referencia' => $request->referencia . ($request->descuento_porcentaje > 0 ? " (Desc: {$request->descuento_porcentaje}%)" : ""),
                'cajero_id' => Auth::id(),
            ]);

            $orden->update(['pagado' => true]);
            return $orden;
        });

        $orden->refresh(); 
        $orden->load(['examenes', 'paciente', 'pago']);
        $pdf = Pdf::loadView('pdf.factura', compact('orden'));
        return $pdf->download('Factura_' . $orden->id . '.pdf'); 
    }

    // ... createForConsulta ...
    public function createForConsulta(Consulta $consulta)
    {
        if ($consulta->pagado) {
            return redirect()->route('consultas.index')->with('warning', 'Pagada.');
        }
        $consulta->load(['doctor', 'paciente']);
        $precio = $consulta->doctor->precio_consulta ?? 30.00;
        return view('pagos.create_consulta', compact('consulta', 'precio'));
    }

    public function storeConsulta(Request $request)
    {
        $request->validate([
            'consulta_id' => 'required|exists:consultas,id',
            'metodo_pago' => 'required|string',
            'monto_total' => 'required|numeric|min:0',
        ]);

        // 1. Verificar Caja Abierta
        $sesion = CajaSesion::where('user_id', Auth::id())->where('estado', 'Abierta')->first();

        $consulta = DB::transaction(function () use ($request, $sesion) {
            $consulta = Consulta::findOrFail($request->consulta_id);
            
            $consulta->pago()->create([
                'caja_sesion_id' => $sesion ? $sesion->id : null, // <--- AQUÍ TAMBIÉN
                'monto_total' => $request->monto_total,
                'metodo_pago' => $request->metodo_pago,
                'referencia' => $request->referencia,
                'cajero_id' => Auth::id(),
            ]);

            $consulta->update(['pagado' => true]);
            return $consulta;
        });

        $consulta->load(['doctor.usuario', 'paciente', 'pago']);
        $pdf = Pdf::loadView('pdf.factura_consulta', compact('consulta'));
        return $pdf->download('Recibo_Consulta_' . $consulta->id . '.pdf');
    }
}