<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permitimos que cualquiera con permiso de ruta entre
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'licencia_medica' => ['required', 'string', 'max:50', 'unique:doctors,licencia_medica'],
            'especialidad' => ['required', 'string', 'max:255'],
            
            // Validamos el email en la tabla 'users' para crear el login
            'email_usuario' => ['required', 'email', 'unique:users,email'],

            // === NUEVOS CAMPOS FINANCIEROS ===
            'precio_consulta' => ['required', 'numeric', 'min:0'],
            'comision_lab_tipo' => ['required', 'in:porcentaje,fijo'],
            'comision_lab_valor' => ['required', 'numeric', 'min:0'],
        ];
    }
}