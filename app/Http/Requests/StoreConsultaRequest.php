<?php

namespace App\Http\Requests;

// app/Http/Requests/StoreConsultaRequest.php

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
            'cita_id' => ['nullable', 'exists:citas,id'],
            'paciente_id' => ['required', 'exists:pacientes,id'],
            // ... (sintomas, diagnostico, tratamiento) ...
            
            // Reglas para los exámenes solicitados
            'examenes_solicitados' => ['nullable', 'array'],
            'examenes_solicitados.*' => ['integer', 'exists:examens,id'], // Asegura que los IDs existen
        ];
    }
}
