<?php

namespace App\Http\Controllers;

use App\Models\Examen;
use Illuminate\Http\Request;

class ExamenController extends Controller
{
    // Solo el Admin o Laboratorio pueden gestionar el catálogo
    public static function middleware(): array
    {
        return [
            'role:admin|gestion.laboratorio',
        ];
    }

    public function index()
    {
        // Listamos exámenes ordenados por nombre
        $examenes = Examen::orderBy('nombre')->paginate(10);
        return view('examenes.index', compact('examenes'));
    }

    public function create()
    {
        return view('examenes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|string|max:100',
            'precio' => 'required|numeric|min:0',
            'unidades' => 'nullable|string|max:50',
            'valor_referencia' => 'nullable|string|max:255',
        ]);

        Examen::create($validated);

        return redirect()->route('examenes.index')
            ->with('success', 'Examen creado exitosamente.');
    }

    public function edit(Examen $examene) // Laravel a veces usa 'examene' por singularización automática
    {
        // Por consistencia, lo renombramos a $examen en la vista
        return view('examenes.edit', ['examen' => $examene]);
    }

    public function update(Request $request, Examen $examene)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|string|max:100',
            'precio' => 'required|numeric|min:0',
            'unidades' => 'nullable|string|max:50',
            'valor_referencia' => 'nullable|string|max:255',
        ]);

        $examene->update($validated);

        return redirect()->route('examenes.index')
            ->with('success', 'Examen actualizado correctamente.');
    }

    public function destroy(Examen $examene)
    {
        try {
            $examene->delete();
            return redirect()->route('examenes.index')
                ->with('success', 'Examen eliminado del catálogo.');
        } catch (\Exception $e) {
            return back()->with('error', 'No se puede eliminar este examen porque ya ha sido utilizado en órdenes anteriores.');
        }
    }

    /**
     * Asignar un insumo (reactivo) a un examen.
     */
    public function storeInsumo(Request $request, Examen $examene)
    {
        $request->validate([
            'insumo_id' => 'required|exists:insumos,id',
            'cantidad' => 'required|numeric|min:0.01'
        ]);

        // Guardamos en la tabla pivote
        $examene->insumos()->attach($request->insumo_id, ['cantidad_necesaria' => $request->cantidad]);
        
        return back()->with('success', 'Insumo asignado correctamente.');
    }

    /**
     * Quitar un insumo de un examen.
     */
    public function destroyInsumo(Examen $examene, $insumoId)
    {
        $examene->insumos()->detach($insumoId);
        return back()->with('success', 'Insumo removido del examen.');
    }
}