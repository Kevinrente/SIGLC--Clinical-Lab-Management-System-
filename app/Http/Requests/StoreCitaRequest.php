<?php

namespace App\Http\Requests;

// app/Http/Requests/StoreCitaRequest.php

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCitaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('gestion.citas');
    }

    public function rules(): array
    {
        // La fecha y hora de la cita
        $fechaHora = $this->input('fecha_hora');
        
        return [
            'paciente_id' => ['required', 'exists:pacientes,id'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'fecha_hora' => [
                'required', 
                'date_format:Y-m-d H:i:s', 
                'after_or_equal:now',
                // Validación de unicidad: asegura que el doctor_id sea único para esa fecha_hora
                // Asumiendo que la duración de la cita es un SLOT fijo (e.g., 30 minutos)
                Rule::unique('citas')->where(function ($query) use ($fechaHora) {
                    return $query->where('doctor_id', $this->input('doctor_id'))
                                 ->where('fecha_hora', $fechaHora);
                })
            ],
            'motivo' => ['nullable', 'string', 'max:255'],
            'estado' => ['required', 'in:Pendiente,Confirmada,Cancelada,Completada'],
        ];
    }
    
    public function messages()
    {
        return [
            'fecha_hora.unique' => 'El doctor ya tiene una cita agendada a esta hora. Por favor, seleccione otro horario.',
            'fecha_hora.after_or_equal' => 'No se puede agendar una cita en el pasado.',
        ];
    }
}
