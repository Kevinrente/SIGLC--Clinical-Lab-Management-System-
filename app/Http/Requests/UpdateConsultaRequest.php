<?php

namespace App\Http\Requests;

// app/Http/Requests/UpdateConsultaRequest.php

use Illuminate\Foundation\Http\FormRequest;

class UpdateConsultaRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Solo los Doctores pueden actualizar consultas
        return $this->user()->can('gestion.consultas');
    }

    public function rules(): array
    {
        return [
            'sintomas' => ['nullable', 'string'],
            'diagnostico' => ['required', 'string'],
            'tratamiento' => ['nullable', 'string'],
        ];
    }
}
