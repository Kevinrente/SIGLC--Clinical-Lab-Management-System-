<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Doctor;

class MedicalRolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // --- 1. Definición de Permisos ---
        Permission::firstOrCreate(['name' => 'gestion.citas']);         
        Permission::firstOrCreate(['name' => 'gestion.pacientes']);    
        Permission::firstOrCreate(['name' => 'gestion.consultas']);    
        Permission::firstOrCreate(['name' => 'lectura.historial']);    
        Permission::firstOrCreate(['name' => 'gestion.laboratorio']);  
        Permission::firstOrCreate(['name' => 'gestion.administracion']);

        // --- 2. Creación de Roles y Asignación ---

        // A. Administrador (Acceso total)
        $roleAdmin = Role::firstOrCreate(['name' => 'Admin']);
        $roleAdmin->givePermissionTo(Permission::all());

        // B. Especialista/Doctor
        $roleDoctor = Role::firstOrCreate(['name' => 'Doctor']);
        $roleDoctor->givePermissionTo([
            'gestion.citas',
            'gestion.pacientes',
            'gestion.consultas', 
            'lectura.historial',
        ]);
        
        // C. Laboratorio (ROL CORREGIDO)
        $roleLaboratorio = Role::firstOrCreate(['name' => 'Laboratorio']);
        $roleLaboratorio->givePermissionTo([
            'gestion.laboratorio',
        ]);

        // D. Recepción/Citas
        $roleRecepcion = Role::firstOrCreate(['name' => 'Recepcion']);
        $roleRecepcion->givePermissionTo([
            'gestion.citas',
            'gestion.pacientes',
        ]);

        // --- 3. Asignar Roles y Crear Entidades ---
        
        // Usuario 1: Admin
        $userAdmin = User::firstOrCreate(['email' => 'admin@siglc.com'], ['name' => 'Jefe de Administración', 'password' => bcrypt('password')])->assignRole($roleAdmin);
        
        // Usuario 2: Doctor (Crear el registro de Doctor asociado)
        $userDoctor = User::firstOrCreate(['email' => 'doctor@siglc.com'], ['name' => 'Dr. Especialista', 'password' => bcrypt('password')])->assignRole($roleDoctor);
        Doctor::firstOrCreate(['user_id' => $userDoctor->id], [
            'nombre' => 'Dr.', 
            'apellido' => 'Especialista', 
            'licencia_medica' => 'DR-12345',
            'especialidad' => 'Medicina Interna',
        ]);

        // Usuario 3: Recepción
        $userRecepcion = User::firstOrCreate(['email' => 'recepcion@siglc.com'], ['name' => 'Asistente Recepción', 'password' => bcrypt('password')])->assignRole($roleRecepcion);
        
        // Usuario 4: Laboratorio (NUEVA CUENTA)
        $userLaboratorio = User::firstOrCreate(['email' => 'laboratorio@siglc.com'], ['name' => 'Técnico de Laboratorio', 'password' => bcrypt('password')])->assignRole($roleLaboratorio);
    }
}