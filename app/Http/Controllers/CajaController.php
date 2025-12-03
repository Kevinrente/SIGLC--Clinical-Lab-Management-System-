<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CajaSesion;
use App\Models\Gasto; // <--- Importante
use Illuminate\Support\Facades\Auth;

class CajaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Buscamos sesión abierta
        $sesionActual = CajaSesion::where('user_id', $user->id)
            ->where('estado', 'Abierta')
            ->first();

        if (!$sesionActual) {
            return view('caja.apertura');
        }

        // CÁLCULOS EN TIEMPO REAL
        
        // 1. Ingresos (Sumamos lo que entró en Efectivo)
        $ingresosEfectivo = $sesionActual->pagos()
            ->where('metodo_pago', 'Efectivo')
            ->sum('monto_total');
            
        $ingresosOtros = $sesionActual->pagos()
            ->where('metodo_pago', '!=', 'Efectivo')
            ->sum('monto_total');

        // 2. Egresos (Sumamos los gastos registrados)
        $totalGastos = $sesionActual->gastos()->sum('monto');

        // 3. Saldo Actual (Base + Entradas - Salidas)
        $saldoActual = $sesionActual->monto_inicial + $ingresosEfectivo - $totalGastos;

        // Obtenemos la lista de gastos para mostrarla en la tabla
        $gastos = $sesionActual->gastos()->latest()->get();

        return view('caja.dashboard', compact('sesionActual', 'saldoActual', 'ingresosEfectivo', 'ingresosOtros', 'totalGastos', 'gastos'));
    }

    public function store(Request $request)
    {
        $request->validate(['monto_inicial' => 'required|numeric|min:0']);

        if (CajaSesion::where('user_id', Auth::id())->where('estado', 'Abierta')->exists()) {
            return back()->with('error', 'Ya tienes una sesión abierta.');
        }

        CajaSesion::create([
            'user_id' => Auth::id(),
            'monto_inicial' => $request->monto_inicial,
            'fecha_apertura' => now(),
            'estado' => 'Abierta'
        ]);

        return redirect()->route('caja.index')->with('success', 'Caja abierta.');
    }

    // NUEVO MÉTODO: REGISTRAR GASTO
    public function storeGasto(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0.01'
        ]);

        $sesion = CajaSesion::where('user_id', Auth::id())->where('estado', 'Abierta')->firstOrFail();

        // Crear el gasto
        Gasto::create([
            'caja_sesion_id' => $sesion->id,
            'descripcion' => $request->descripcion,
            'monto' => $request->monto,
            'user_id' => Auth::id()
        ]);

        // Actualizamos el contador total de egresos en la sesión para el registro histórico
        $sesion->increment('total_egresos', $request->monto);

        return redirect()->route('caja.index')->with('success', 'Gasto registrado correctamente.');
    }

    public function close(Request $request, CajaSesion $sesion)
    {
        $request->validate(['saldo_real' => 'required|numeric|min:0']);

        // Recalcular todo al momento del cierre
        $ingresos = $sesion->pagos()->where('metodo_pago', 'Efectivo')->sum('monto_total');
        $egresos = $sesion->gastos()->sum('monto');
        
        $saldoEsperado = $sesion->monto_inicial + $ingresos - $egresos;
        $diferencia = $request->saldo_real - $saldoEsperado;

        $sesion->update([
            'fecha_cierre' => now(),
            'total_ingresos' => $ingresos,
            'total_egresos' => $egresos,
            'saldo_esperado' => $saldoEsperado,
            'saldo_real' => $request->saldo_real,
            'diferencia' => $diferencia,
            'estado' => 'Cerrada'
        ]);

        return redirect()->route('dashboard')->with('success', "Caja cerrada. Diferencia: $" . number_format($diferencia, 2));
    }
}