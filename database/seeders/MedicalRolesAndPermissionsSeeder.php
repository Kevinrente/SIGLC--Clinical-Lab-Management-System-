<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Doctor; // Necesario para asociar al Doctor

class MedicalRolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // --- 1. Definición de Permisos ---
        Permission::create(['name' => 'gestion.citas']);         // Recepción
        Permission::create(['name' => 'gestion.pacientes']);    // Recepción, Admin
        
        Permission::create(['name' => 'gestion.consultas']);    // Doctor
        Permission::create(['name' => 'lectura.historial']);    // Doctor, Admin
        
        Permission::create(['name' => 'gestion.laboratorio']);  // Técnico, Admin
        
        Permission::create(['name' => 'gestion.administracion']);// Admin
        
        // --- 2. Creación de Roles y Asignación ---

        // A. Administrador (Acceso total y seguridad)
        $roleAdmin = Role::create(['name' => 'Admin']);
        $roleAdmin->givePermissionTo(Permission::all());

        // B. Especialista/Doctor
        $roleDoctor = Role::create(['name' => 'Doctor']);
        $roleDoctor->givePermissionTo([
            'gestion.citas',
            'gestion.pacientes',
            'gestion.consultas', 
            'lectura.historial',
        ]);
        
        // C. Técnico de Laboratorio
        $roleTecnico = Role::create(['name' => 'Tecnico']);
        $roleTecnico->givePermissionTo([
            'gestion.laboratorio',
        ]);

        // D. Recepción/Citas
        $roleRecepcion = Role::create(['name' => 'Recepcion']);
        $roleRecepcion->givePermissionTo([
            'gestion.citas',
            'gestion.pacientes',
        ]);

        // --- 3. Asignar Roles y Crear Entidades ---
        
        // Usuario 1: Admin
        $userAdmin = User::factory()->create(['name' => 'Jefe de Administración', 'email' => 'admin@siglc.com', 'password' => bcrypt('password')]);
        $userAdmin->assignRole($roleAdmin);
        
        // Usuario 2: Doctor (Crear el registro de Doctor asociado)
        $userDoctor = User::factory()->create(['name' => 'Dr. Especialista', 'email' => 'doctor@siglc.com', 'password' => bcrypt('password')]);
        $userDoctor->assignRole($roleDoctor);
        Doctor::create([
            'nombre' => 'Doctor', 
            'apellido' => 'Especialista', 
            'licencia_medica' => 'DR-12345',
            'especialidad' => 'Medicina Interna',
            'user_id' => $userDoctor->id, // CLAVE: Relación con el usuario
        ]);

        // Usuario 3: Recepción
        $userRecepcion = User::factory()->create(['name' => 'Asistente Recepción', 'email' => 'recepcion@siglc.com', 'password' => bcrypt('password')]);
        $userRecepcion->assignRole($roleRecepcion);
    }
}