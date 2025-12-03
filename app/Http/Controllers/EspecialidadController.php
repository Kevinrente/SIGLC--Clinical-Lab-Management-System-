<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use Illuminate\Http\Request;

class EspecialidadController extends Controller
{
    // Guardar una nueva especialidad
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'icono' => 'required|string|max:10', // Emojis suelen ser cortos
            'color' => 'required'
        ]);

        Especialidad::create($request->all());

        return back()->with('success', 'Especialidad agregada correctamente.');
    }

    // Eliminar una especialidad
    public function destroy($id)
    {
        Especialidad::destroy($id);
        return back()->with('success', 'Especialidad eliminada.');
    }
}