<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrdenExamenRequest extends FormRequest
{
    public function authorize(): bool
    {
        //return $this->user() && $this->user()->can('gestion.consultas');
        return true;
    }

    public function rules(): array
    {
        return [
            // Ahora la cita es opcional (nullable) porque el paciente puede venir de la calle
            'cita_id' => 'nullable|exists:citas,id',
            
            // El doctor es opcional (nullable)
            'doctor_id' => 'nullable|exists:doctors,id',
            
            // El paciente SI es obligatorio
            'paciente_id' => 'required|exists:pacientes,id',
            
            // Los exámenes siguen siendo obligatorios
            'examenes' => 'required|array|min:1',
            'examenes.*' => 'exists:examens,id',
        ];
    }
    
    public function messages(): array
    {
        return [
            'examenes.required' => 'Debe seleccionar al menos un examen.',
            'examenes.min' => 'Seleccione por lo menos un examen del catálogo.',
        ];
    }
}