<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\User; // <--- IMPORTANTE: Importar User
use App\Models\OrdenExamen; // <--- NUEVO: Para consultar las órdenes
use App\Http\Requests\StorePacienteRequest;
use App\Http\Requests\UpdatePacienteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <--- NUEVO: Para saber quién está logueado
use Illuminate\Support\Facades\DB; // <--- IMPORTANTE: Importar DB
use Illuminate\Routing\Controllers\Middleware; // <--- NUEVO: Para usar 'except'

class PacienteController extends Controller
{
    /**
     * Define los middlewares para este controlador.
     */
    public static function middleware(): array
    {
        return [
            // CAMBIO IMPORTANTE:
            // Aplicamos el permiso 'gestion.pacientes' a TODO el controlador,
            // EXCEPTO al método 'misResultados', para que el paciente pueda entrar.
            new Middleware('permission:gestion.pacientes', except: ['misResultados']),
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
        try {
            DB::transaction(function () use ($request) {
                // 1. Crear el Usuario de Acceso primero
                $user = User::create([
                    'name' => $request->nombre . ' ' . $request->apellido,
                    'email' => $request->email,
                    'password' => bcrypt($request->cedula), // La contraseña es la cédula
                ]);

                // 2. Preparar los datos del Paciente
                $pacienteData = $request->validated();
                $pacienteData['user_id'] = $user->id; // Vinculamos con el usuario creado

                // 3. Crear la Ficha del Paciente
                Paciente::create($pacienteData);
            });

            return redirect()->route('pacientes.index')
                ->with('success', 'Paciente y Usuario de acceso creados correctamente.');

        } catch (\Exception $e) {
            // Si algo falla (ej: email duplicado), volvemos atrás con el error
            return back()->withInput()->with('error', 'Error al crear el paciente: ' . $e->getMessage());
        }
    }
    
    public function show(Paciente $paciente)
    {
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
        $paciente->delete(); 
        
        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente eliminado correctamente.');
    }

    // =========================================================================
    // NUEVO MÉTODO: PORTAL DEL PACIENTE
    // =========================================================================
    public function misResultados()
    {
        $user = Auth::user();
        
        // Verificamos si este usuario tiene un perfil de paciente asociado
        // IMPORTANTE: Asegúrate de tener la relación 'paciente()' en tu modelo User
        $paciente = $user->paciente; 

        if (!$paciente) {
            // Si el usuario no está vinculado a un paciente, mostramos la vista vacía con aviso
            return view('pacientes.portal', ['ordenes' => collect([])])
                ->with('warning', 'Tu usuario no tiene un perfil de paciente asociado.');
        }

        // Buscamos las órdenes DE ESTE PACIENTE
        $ordenes = OrdenExamen::where('paciente_id', $paciente->id)
            ->with(['doctor.usuario', 'examenes'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pacientes.portal', compact('ordenes'));
    }
}