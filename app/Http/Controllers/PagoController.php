<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdenExamen;
use App\Models\Pago;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Consulta;

class PagoController extends Controller
{
    /**
     * Muestra la pantalla de cobro para una Orden de Laboratorio
     */
    public function createForOrden(OrdenExamen $orden)
    {
        if ($orden->pagado) {
            return redirect()->route('laboratorio.index')->with('warning', 'Esta orden ya fue pagada.');
        }

        // Cargamos los exámenes para sumar el total
        $orden->load(['examenes', 'paciente']);
        
        // Calculamos el total sumando el precio de cada examen
        $total = $orden->examenes->sum('precio');

        return view('pagos.create_orden', compact('orden', 'total'));
    }

    /**
     * Procesa el pago de Laboratorio
     */
    public function storeOrden(Request $request)
    {
        $request->validate([
            'orden_id' => 'required|exists:orden_examens,id',
            'metodo_pago' => 'required|string',
            'monto_total_final' => 'required|numeric|min:0',
            'precios' => 'required|array', // Los precios editados
            'descuento_porcentaje' => 'nullable|numeric|min:0|max:100'
        ]);

        $orden = DB::transaction(function () use ($request) {
            $orden = OrdenExamen::findOrFail($request->orden_id);
            
            // 1. Actualizar los precios COBRADOS en la tabla pivote
            foreach ($request->precios as $examenId => $precio) {
                $orden->examenes()->updateExistingPivot($examenId, [
                    'precio_cobrado' => $precio
                ]);
            }

            // 2. Crear el Pago
            $orden->pago()->create([
                'monto_total' => $request->monto_total_final,
                'metodo_pago' => $request->metodo_pago,
                'referencia' => $request->referencia . ($request->descuento_porcentaje > 0 ? " (Desc: {$request->descuento_porcentaje}%)" : ""),
                'cajero_id' => Auth::id(),
            ]);

            // 3. Marcar orden como pagada
            $orden->update(['pagado' => true]);

            return $orden;
        });

        // 4. GENERAR FACTURA PDF
        // CORRECCIÓN: 'refresh()' actualiza los datos de la variable $orden con lo que se acaba de guardar en la BD.
        $orden->refresh(); 
        
        // Cargamos las relaciones necesarias para el PDF
        $orden->load(['examenes', 'paciente', 'pago']);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.factura', compact('orden'));
        
        return $pdf->download('Factura_' . $orden->id . '.pdf'); 
    }

    // ... (Tus métodos anteriores de OrdenExamen siguen aquí) ...

    /**
     * Muestra la pantalla de cobro para una Consulta Médica
     */
    public function createForConsulta(Consulta $consulta)
    {
        if ($consulta->pagado) {
            return redirect()->route('consultas.index')->with('warning', 'Esta consulta ya fue pagada.');
        }

        $consulta->load(['doctor', 'paciente']);
        
        // El precio sugerido es el que tiene configurado el doctor
        $precio = $consulta->doctor->precio_consulta ?? 30.00;

        return view('pagos.create_consulta', compact('consulta', 'precio'));
    }

    /**
     * Procesa el pago de la Consulta
     */
    public function storeConsulta(Request $request)
    {
        $request->validate([
            'consulta_id' => 'required|exists:consultas,id',
            'metodo_pago' => 'required|string',
            'monto_total' => 'required|numeric|min:0',
            'descuento_porcentaje' => 'nullable|numeric|min:0|max:100'
        ]);

        $consulta = DB::transaction(function () use ($request) {
            $consulta = Consulta::findOrFail($request->consulta_id);
            
            // Crear el Pago (Polimórfico)
            $consulta->pago()->create([
                'monto_total' => $request->monto_total,
                'metodo_pago' => $request->metodo_pago,
                'referencia' => $request->referencia,
                'cajero_id' => Auth::id(),
            ]);

            // Marcar consulta como pagada
            $consulta->update(['pagado' => true]);

            return $consulta;
        });

        // Generar Recibo PDF
        $consulta->load(['doctor.usuario', 'paciente', 'pago']);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.factura_consulta', compact('consulta'));
        
        return $pdf->download('Recibo_Consulta_' . $consulta->id . '.pdf');
    }
}