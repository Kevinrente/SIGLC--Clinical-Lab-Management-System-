<?php

namespace App\Http\Requests;



use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCitaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('gestion.citas');
    }

    public function rules(): array
    {
        $citaId = $this->route('cita')->id;
        $fechaHora = $this->input('fecha_hora');

        return [
            'paciente_id' => ['required', 'exists:pacientes,id'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'fecha_hora' => [
                'required', 
                'date_format:Y-m-d H:i:s', 
                'after_or_equal:' . ($this->cita->fecha_hora > now() ? $this->cita->fecha_hora->format('Y-m-d H:i:s') : 'now'), // Permite mantener la hora si ya pasó, sino requiere hora futura
                // Ignorar la cita actual en la validación de unicidad
                Rule::unique('citas')->where(function ($query) use ($fechaHora, $citaId) {
                    return $query->where('doctor_id', $this->input('doctor_id'))
                                 ->where('fecha_hora', $fechaHora);
                })->ignore($citaId),
            ],
            'motivo' => ['nullable', 'string', 'max:255'],
            'estado' => ['required', 'in:Pendiente,Confirmada,Cancelada,Completada'],
        ];
    }
    
    public function messages()
    {
        return [
            'fecha_hora.unique' => 'El doctor ya tiene una cita agendada a esta hora. Por favor, seleccione otro horario.',
        ];
    }
}
