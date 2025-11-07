<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class StoreCitaRequest extends FormRequest
{
    /**
     * Limpia y estandariza la entrada de fecha/hora antes de la validación.
     */
    protected function prepareForValidation(): void
    {
        $fechaHoraInput = $this->input('fecha_hora');
        
        if ($fechaHoraInput) {
            
            // 1. Convertir el string sucio a un timestamp de PHP
            // Usamos strtotime para que PHP intente interpretar la cadena desordenada
            $timestamp = strtotime($fechaHoraInput);
            
            // 2. Si la conversión es exitosa, formatear al string EXACTO que la regla requiere
            if ($timestamp !== false) {
                
                // Reemplaza la cadena de entrada por la cadena estandarizada
                $sanitizedDate = date('d/m/Y h:i A', $timestamp);
                
                $this->merge([
                    // Resultado final: 10/11/2025 03:30 PM (o AM)
                    'fecha_hora' => $sanitizedDate,
                ]);
            }
        }
        
        parent::prepareForValidation(); 
    }

    public function authorize(): bool
    {
        // El usuario debe tener permiso para crear citas
        return $this->user()->can('gestion.citas');
    }

    public function rules(): array
    {
        // El valor aquí ya está SANITIZADO por prepareForValidation()
        $fechaHoraInput = $this->input('fecha_hora');
        
        // 2. Intentamos convertir la fecha de entrada (Frontend) al formato de Base de Datos (Backend).
        $fechaHoraDBFormat = null;
        try {
            // CAMBIO AQUI: Usar el formato con A mayúscula
            $fechaHoraDBFormat = Carbon::createFromFormat('d/m/Y, H:i A', $fechaHoraInput)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            // Si la conversión falla, la variable queda como null.
        }
        
        return [
            'paciente_id' => ['required', 'exists:pacientes,id'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            
            'fecha_hora' => [
            'required', 
            'date_format:d/m/Y h:i A', // <-- Usamos h minúscula para que coincida con date()
            'after_or_equal:now',
                
                // Validación de unicidad
                Rule::unique('citas')->where(function ($query) use ($fechaHoraDBFormat) {
                    
                    if (is_null($fechaHoraDBFormat)) {
                        return $query->whereRaw('1 = 0');
                    }
                    
                    return $query->where('doctor_id', $this->input('doctor_id'))
                                 ->where('fecha_hora', $fechaHoraDBFormat);
                })
            ],
            'motivo' => ['nullable', 'string', 'max:255'],
            'estado' => ['required', 'in:Pendiente,Confirmada,Cancelada,Completada'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'fecha_hora.unique' => 'El doctor ya tiene una cita agendada a esta hora. Por favor, seleccione otro horario.',
            'fecha_hora.after_or_equal' => 'No se puede agendar una cita en el pasado.',
            'fecha_hora.date_format' => 'El formato de fecha y hora debe coincidir con el requerido (DD/MM/AAAA, HH:MM am/pm).',
        ];
    }
}
