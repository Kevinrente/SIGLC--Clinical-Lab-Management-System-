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
            'identificacion' => ['required', 'string', 'max:20', 'unique:pacientes,identificacion'],
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'fecha_nacimiento' => ['required', 'date', 'before_or_equal:' . now()->toDateString()],
            'sexo' => ['required', 'in:M,F,Otro'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255', 'unique:pacientes,email'],
        ];
    }
    // ... Puedes añadir messages() aquí si lo deseas ...
}
