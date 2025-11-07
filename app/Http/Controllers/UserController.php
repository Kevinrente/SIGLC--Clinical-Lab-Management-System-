<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Aplica el middleware para asegurar que solo el Admin pueda acceder.
     */
    public static function middleware(): array
    {
        return [
            'permission:gestion.administracion',
        ];
    }

    /**
     * Muestra una lista paginada de usuarios de personal.
     */
    public function index()
    {
        // Solo mostramos usuarios que no sean Doctores ni Pacientes (ej. Admin, Recepción, Laboratorio)
        // Excluimos usuarios que son Doctores por simplicidad, ya que se gestionan en DoctorController
        $users = User::whereDoesntHave('doctor') 
                     ->with('roles')
                     ->paginate(10);
        
        return view('users.index', compact('users'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario (personal).
     */
    public function create()
    {
        // Solo mostramos roles que no sean 'Admin' o 'Doctor' para la creación de personal
        $roles = Role::whereNotIn('name', ['Admin', 'Doctor'])->get(); 
        return view('users.create', compact('roles'));
    }

    /**
     * Almacena un nuevo usuario (personal) con su contraseña.
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $role = Role::findById($validated['role_id']);

        // 1. Crear el usuario con la contraseña hasheada
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // 2. Asignar el rol
        $user->assignRole($role);

        return redirect()->route('users.index')
            ->with('success', "Usuario {$user->name} creado y rol de {$role->name} asignado.");
    }
    
    /**
     * Muestra el formulario para editar el usuario.
     */
    public function edit(User $user)
    {
        // Aseguramos que no se pueda editar un Admin o Doctor desde aquí (opcional)
        if ($user->hasRole(['Admin', 'Doctor'])) {
             abort(403, 'La gestión de este rol debe hacerse en su módulo correspondiente.');
        }

        $roles = Role::whereNotIn('name', ['Admin', 'Doctor'])->get(); 
        return view('users.edit', compact('user', 'roles'));
    }
    
    // ----------------------------------------------------
    // ELIMINAR LOS SIGUIENTES MÉTODOS DEL ARCHIVO:
    // public function show(User $user) { ... } 
    // public function update(Request $request, User $user) { ... }
    // public function destroy(User $user) { ... }
    // ----------------------------------------------------
}