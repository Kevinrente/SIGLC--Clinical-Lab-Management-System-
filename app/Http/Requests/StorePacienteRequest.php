<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class StorePacienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Autorización basada en RBAC
        return $this->user()->can('gestion.pacientes');
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'cedula' => 'required|string|unique:pacientes,cedula',
            'email' => 'required|email|unique:users,email',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|string',   // <--- Verifica que esté aquí
            'telefono' => 'nullable|string', // <--- Verifica que esté aquí
        ];
    }
    // ... Puedes añadir messages() aquí si lo deseas ...
}
