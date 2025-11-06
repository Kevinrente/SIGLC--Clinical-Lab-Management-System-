<?php

namespace App\Http\Controllers;

use App\Models\Examen;
use Illuminate\Http\Request;

class ExamenController extends Controller
{
    /**
     * Define los middlewares para este controlador.
     */
    public static function middleware(): array
    {
        return [
            // Solo el Administrador debe tener acceso al catálogo (CRUD).
            'permission:gestion.administracion',
        ];
    }
    
    public function index()
    {
        $examenes = Examen::orderBy('nombre')->paginate(15);
        return view('examenes.index', compact('examenes'));
    }

    public function create()
    {
        return view('examenes.create');
    }

    public function store(Request $request)
    {
        // Usamos Request básico, ya que este es un módulo administrativo interno simple.
        $request->validate([
            'nombre' => 'required|string|max:255|unique:examens,nombre',
            'codigo' => 'nullable|string|max:50',
            'precio' => 'required|numeric|min:0',
            'tiempo_entrega_dias' => 'required|integer|min:1',
        ]);
        
        Examen::create($request->all());

        return redirect()->route('examenes.index')
            ->with('success', 'Examen de laboratorio agregado exitosamente.');
    }
    
    public function edit(Examen $examen)
    {
        return view('examenes.edit', compact('examen'));
    }

    public function update(Request $request, Examen $examen)
    {
        // Usamos Request básico con unicidad ignorando el ID actual.
        $request->validate([
            'nombre' => 'required|string|max:255|unique:examens,nombre,' . $examen->id,
            'codigo' => 'nullable|string|max:50',
            'precio' => 'required|numeric|min:0',
            'tiempo_entrega_dias' => 'required|integer|min:1',
        ]);

        $examen->update($request->all());
        
        return redirect()->route('examenes.index')
            ->with('success', 'Examen actualizado exitosamente.');
    }

    // El método destroy se omite en las rutas (.only) para forzar la desactivación en un sistema real
    // Si se necesitara, se implementaría aquí con chequeo de dependencias.
}
