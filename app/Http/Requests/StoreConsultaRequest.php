<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConsultaRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Solo los Doctores pueden registrar consultas
        return $this->user()->can('gestion.consultas');
    }

    public function rules(): array
    {
        return [
            // --------------------------------------------------------
            // REGLAS CRÍTICAS PARA EVITAR EL ERROR "NOT NULL VIOLATION"
            // --------------------------------------------------------
            
            // Campos de texto de la consulta (Requeridos según la estructura del formulario)
            'sintomas' => ['required', 'string', 'max:1000'],
            'diagnostico' => ['required', 'string', 'max:500'], // <-- CORRECCIÓN: DEBE SER REQUIRED
            'tratamiento' => ['required', 'string', 'max:1000'],

            // Relaciones
            'cita_id' => ['nullable', 'exists:citas,id'],
            'paciente_id' => ['required', 'exists:pacientes,id'],
            
            // Opcional: Solicitud de Exámenes (asumiendo que solo se envía una lista de IDs)
            'examenes_solicitados' => ['nullable', 'array'],
            'examenes_solicitados.*' => ['integer', 'exists:examens,id'], 
        ];
    }
    
    public function messages(): array
    {
        return [
            'diagnostico.required' => 'El campo Diagnóstico (descripción o CIE-10) es obligatorio.',
            'sintomas.required' => 'El campo Síntomas es obligatorio.',
            'tratamiento.required' => 'El campo Tratamiento / Plan Terapéutico es obligatorio.',
        ];
    }
}