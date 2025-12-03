<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insumo;

class InventarioController extends Controller
{
    public function index()
    {
        $insumos = Insumo::orderBy('nombre')->get();
        return view('inventario.index', compact('insumos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'unidad_medida' => 'required|string',
            'stock_minimo' => 'required|numeric'
        ]);

        Insumo::create($request->all());
        return back()->with('success', 'Insumo creado.');
    }

    public function addStock(Request $request, Insumo $insumo)
    {
        $request->validate(['cantidad' => 'required|numeric|min:0.1']);
        
        $insumo->increment('stock_actual', $request->cantidad);
        
        return back()->with('success', 'Stock actualizado correctamente.');
    }
}