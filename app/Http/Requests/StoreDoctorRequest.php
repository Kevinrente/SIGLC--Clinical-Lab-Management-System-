<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('gestion.administracion');
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'licencia_medica' => ['required', 'string', 'max:50', 'unique:doctors,licencia_medica'],
            'especialidad' => ['required', 'string', 'max:100'],
            
            // Campo de email para crear el usuario asociado
            'email_usuario' => ['required', 'email', 'max:255', 'unique:users,email'], 
        ];
    }
}
