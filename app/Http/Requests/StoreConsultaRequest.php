<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConsultaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cita_id' => 'required|exists:citas,id',
            'paciente_id' => 'required|exists:pacientes,id',
            
            // 1. Campos de Texto (SOLO ESTOS)
            'motivo_consulta' => 'required|string',
            'exploracion_fisica' => 'nullable|string',
            
            // 2. Diagnósticos Nuevos
            'diagnostico_presuntivo' => 'nullable|string|max:255',
            'diagnostico_confirmado' => 'required|string|max:255',
            
            // 3. Receta (Opcional)
            'receta' => 'nullable|array', 
            'receta.*.medicamento' => 'nullable|string', 
            'receta.*.indicacion' => 'nullable|string',
            
            // 4. Acción
            'action' => 'nullable|string|in:finish,order',
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