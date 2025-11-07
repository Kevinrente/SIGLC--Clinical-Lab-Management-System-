<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrdenExamenRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Autorizar si el usuario tiene permiso de Doctor/Consulta y está logueado
        return $this->user() && $this->user()->can('gestion.consultas');
    }

    public function rules(): array
    {
        return [
            'cita_id' => ['required', 'exists:citas,id'],
            'examenes_solicitados' => ['required', 'string', 'min:10'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'examenes_solicitados.required' => 'Debe especificar qué exámenes se solicitan.',
        ];
    }
}