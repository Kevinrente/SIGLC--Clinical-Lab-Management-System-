<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
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
        $doctors = Doctor::with('usuario')->orderBy('apellido')->paginate(15);
        return view('doctors.index', compact('doctors'));
    }

    public function create()
    {
        return view('doctors.create');
    }

    /**
     * Crea el registro del Doctor y su usuario asociado.
     */
    public function store(StoreDoctorRequest $request)
    {
        DB::beginTransaction();

        try {
            // 1. Crear el Usuario para el Login
            $user = User::create([
                'name' => $request->nombre . ' ' . $request->apellido,
                'email' => $request->email_usuario, 
                'password' => Hash::make($request->licencia_medica), // Contraseña temporal
            ]);
            
            $user->assignRole('Doctor');

            // 2. Crear el Registro del Doctor con TODOS los datos financieros
            Doctor::create([
                'user_id' => $user->id,
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'licencia_medica' => $request->licencia_medica,
                'especialidad' => $request->especialidad,
                
                // CAMPOS FINANCIEROS (Nuevos)
                'precio_consulta' => $request->precio_consulta ?? 30.00, // Valor por defecto si viene vacío
                'comision_lab_tipo' => $request->comision_lab_tipo ?? 'porcentaje',
                'comision_lab_valor' => $request->comision_lab_valor ?? 0,
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('doctors.index')->with('error', 'Error al registrar doctor: ' . $e->getMessage());
        }

        return redirect()->route('doctors.index')
            ->with('success', 'Doctor registrado exitosamente. (Contraseña: Licencia Médica).');
    }
    
    public function show(Doctor $doctor)
    {
        return view('doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor)
    {
        return view('doctors.edit', compact('doctor'));
    }

    /**
     * Actualiza el registro del Doctor.
     */
    public function update(UpdateDoctorRequest $request, Doctor $doctor)
    {
        // Actualizamos todos los campos validados (incluyendo los financieros)
        $doctor->update($request->validated());

        // Actualizar el nombre del usuario asociado
        if ($doctor->usuario) {
            $doctor->usuario->update([
                'name' => $request->nombre . ' ' . $request->apellido,
                // Si quisieras permitir cambiar el email, aquí iría también
            ]);
        }
        
        return redirect()->route('doctors.index')
            ->with('success', 'Datos del doctor actualizados exitosamente.');
    }

    public function destroy(Doctor $doctor)
    {
        // Validación de integridad referencial
        if ($doctor->citas()->whereIn('estado', ['Pendiente', 'Confirmada'])->count() > 0) {
            return redirect()->route('doctors.index')
                ->with('error', 'No se puede eliminar: tiene citas activas.');
        }

        if ($doctor->usuario) {
            $doctor->usuario->delete();
        }
        
        $doctor->delete(); 
        
        return redirect()->route('doctors.index')
            ->with('success', 'Doctor eliminado correctamente.');
    }
}