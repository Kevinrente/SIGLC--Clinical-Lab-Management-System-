<?php

namespace App\Http\Requests;



use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePacienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('gestion.pacientes');
    }

    public function rules(): array
    {
        $pacienteId = $this->route('paciente')->id;

        return [
            // Identificación debe ser única, ignorando el paciente actual
            'identificacion' => ['required', 'string', 'max:20', Rule::unique('pacientes', 'identificacion')->ignore($pacienteId)],
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'fecha_nacimiento' => ['required', 'date', 'before_or_equal:' . now()->toDateString()],
            'sexo' => ['required', 'in:M,F,Otro'],
            'telefono' => ['nullable', 'string', 'max:50'],
            // Email debe ser único, ignorando el paciente actual
            'email' => ['required', 'email', 'max:255', Rule::unique('pacientes', 'email')->ignore($pacienteId)],
        ];
    }
    // ... Puedes añadir messages() aquí si lo deseas ...
}
