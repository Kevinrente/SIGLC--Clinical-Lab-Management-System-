<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Http\Requests\StorePacienteRequest;
use App\Http\Requests\UpdatePacienteRequest;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    /**
     * Define los middlewares para este controlador.
     */
    public static function middleware(): array
    {
        return [
            // Solo los usuarios con el permiso 'gestion.pacientes' pueden acceder.
            'permission:gestion.pacientes',
        ];
    }
    
    public function index()
    {
        $pacientes = Paciente::orderBy('apellido')->paginate(15);
        
        return view('pacientes.index', compact('pacientes'));
    }

    public function create()
    {
        return view('pacientes.create');
    }

    public function store(StorePacienteRequest $request)
    {
        Paciente::create($request->validated());

        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente registrado exitosamente.');
    }
    
    public function show(Paciente $paciente)
    {
        // Cargamos las citas y consultas para la vista de historial médico
        $paciente->load(['citas', 'consultas']); 
        return view('pacientes.show', compact('paciente'));
    }

    public function edit(Paciente $paciente)
    {
        return view('pacientes.edit', compact('paciente'));
    }

    public function update(UpdatePacienteRequest $request, Paciente $paciente)
    {
        $paciente->update($request->validated());
        
        return redirect()->route('pacientes.index')
            ->with('success', 'Datos del paciente actualizados exitosamente.');
    }

    public function destroy(Paciente $paciente)
    {
        // La restricción de eliminación ya se manejó en la migración:
        // Las citas y consultas asociadas serán eliminadas (onDelete('cascade')).
        $paciente->delete(); 
        
        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente y su historial de citas/consultas eliminados correctamente.');
    }
}
