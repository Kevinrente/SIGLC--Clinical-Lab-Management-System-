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
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|string|max:100',
            'precio' => 'required|numeric|min:0',
            // NUEVOS CAMPOS (Nullables = Opcionales)
            'unidades' => 'nullable|string|max:50',       // Ej: mg/dL
            'valor_referencia' => 'nullable|string|max:100', // Ej: 70 - 110
        ]);

        \App\Models\Examen::create($validated);

        return redirect()->route('examenes.index')->with('success', 'Examen creado correctamente.');
    }
    
    public function edit(Examen $examen)
    {
        return view('examenes.edit', compact('examen'));
    }

    public function update(Request $request, \App\Models\Examen $examene) // O $examen según tu ruta
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|string|max:100',
            'precio' => 'required|numeric|min:0',
            // NUEVOS CAMPOS
            'unidades' => 'nullable|string|max:50',
            'valor_referencia' => 'nullable|string|max:100',
        ]);

        $examene->update($validated);

        return redirect()->route('examenes.index')->with('success', 'Examen actualizado correctamente.');
    }

    // El método destroy se omite en las rutas (.only) para forzar la desactivación en un sistema real
    // Si se necesitara, se implementaría aquí con chequeo de dependencias.
}
