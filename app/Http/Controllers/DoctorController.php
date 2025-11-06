<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User; // Necesario para crear el usuario asociado
use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    /**
     * Define los middlewares para este controlador.
     */
    public static function middleware(): array
    {
        return [
            // Solo el Administrador debe tener acceso a crear y editar doctores.
            'permission:gestion.administracion',
        ];
    }
    
    public function index()
    {
        // Eager load the user relation
        $doctors = Doctor::with('usuario')->orderBy('apellido')->paginate(15);
        
        return view('doctors.index', compact('doctors'));
    }

    public function create()
    {
        // En un sistema real, el Admin podría asignar un User existente, pero simplificamos.
        return view('doctors.create');
    }

    /**
     * Crea el registro del Doctor y su usuario asociado.
     */
    public function store(StoreDoctorRequest $request)
    {
        // Usamos una transacción para asegurar que ambos registros (User y Doctor) se creen o ninguno lo haga.
        DB::beginTransaction();

        try {
            // 1. Crear el Usuario para el Login
            $user = User::create([
                'name' => $request->nombre . ' ' . $request->apellido,
                'email' => $request->email_usuario, // Campo extra en el request para el email del login
                'password' => Hash::make($request->licencia_medica), // Usamos la licencia como contraseña temporal
            ]);
            
            // Asignar el rol de 'Doctor' al usuario
            $user->assignRole('Doctor');

            // 2. Crear el Registro del Doctor y vincularlo
            Doctor::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'licencia_medica' => $request->licencia_medica,
                'especialidad' => $request->especialidad,
                'user_id' => $user->id, // Vinculación clave
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('doctors.index')->with('error', 'Error al registrar doctor y usuario asociado.');
        }

        return redirect()->route('doctors.index')
            ->with('success', 'Doctor registrado y cuenta de usuario creada exitosamente. (Contraseña temporal: Licencia Médica).');
    }
    
    // ... show y edit se implementan de forma estándar ...
    
    public function show(Doctor $doctor)
    {
        return view('doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor)
    {
        return view('doctors.edit', compact('doctor'));
    }

    /**
     * Actualiza el registro del Doctor y sincroniza el nombre del usuario asociado.
     */
    public function update(UpdateDoctorRequest $request, Doctor $doctor)
    {
        $doctor->update($request->validated());

        // Opcional: Actualizar el nombre del usuario si el nombre del doctor cambia
        if ($doctor->usuario) {
            $doctor->usuario->update([
                'name' => $request->nombre . ' ' . $request->apellido,
            ]);
        }
        
        return redirect()->route('doctors.index')
            ->with('success', 'Datos del doctor actualizados exitosamente.');
    }

    /**
     * Elimina el registro del Doctor y anula la cuenta de usuario.
     */
    public function destroy(Doctor $doctor)
    {
        // Requisito: Un doctor con citas activas NO debe ser eliminado.
        if ($doctor->citas()->whereIn('estado', ['Pendiente', 'Confirmada'])->count() > 0) {
            return redirect()->route('doctors.index')
                ->with('error', 'No se puede eliminar el doctor: tiene citas pendientes o confirmadas.');
        }

        // Si el doctor tiene un usuario, lo eliminamos (o lo desvinculamos/desactivamos si hubiera un campo 'activo')
        if ($doctor->usuario) {
            $doctor->usuario->delete(); // Elimina la cuenta de usuario del login
        }
        
        $doctor->delete(); 
        
        return redirect()->route('doctors.index')
            ->with('success', 'Doctor y cuenta de usuario eliminados correctamente.');
    }
}