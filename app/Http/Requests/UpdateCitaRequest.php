<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class UpdateCitaRequest extends FormRequest
{
    /**
     * Prepara los datos para la validación (sanitización).
     */
    protected function prepareForValidation(): void
    {
        $fechaHoraInput = $this->input('fecha_hora');
        
        if ($fechaHoraInput) {
            $sanitizedDate = $fechaHoraInput;

            // 1. Limpieza de indicadores meridianos (p. m. -> PM)
            $sanitizedDate = str_ireplace([' p. m.', ' a. m.', 'p.m.', 'a.m.'], [' PM', ' AM', 'PM', 'AM'], $sanitizedDate);

            // 2. Eliminar espacios alrededor de separadores problemáticos
            $sanitizedDate = str_replace([' / ', ' : ', ' , '], ['/', ':', ','], $sanitizedDate);

            // 3. Eliminar cualquier espacio restante y normalizar la cadena.
            $sanitizedDate = str_replace(' ', '', $sanitizedDate); 

            // 4. Reintroducir los separadores que Carbon::parse necesita para el formato local.
            // Esto es crucial si vas a usar Carbon::parse() que es más flexible.
            $sanitizedDate = preg_replace('/(\d{1,2}\/\d{1,2}\/\d{4}),(\d{1,2}:\d{2})(AM|PM)/i', '$1, $2 $3', $sanitizedDate);


            $this->merge([
                'fecha_hora' => $sanitizedDate,
            ]);
        }
        
        parent::prepareForValidation();
    }

    public function authorize(): bool
    {
        return $this->user()->can('gestion.citas');
    }

    public function rules(): array
    {
        $citaId = $this->route('cita')->id;
        $fechaHoraInput = $this->input('fecha_hora'); // Ya sanitizado
        
        // 1. CONVERSIÓN para la Consulta: Usamos el formato que Carbon::parse() puede interpretar.
        // NOTA: Usamos 'd/m/Y, h:i A' para la conversión ya que es lo más cercano a lo que el datepicker envía.
        $fechaHoraDBFormat = null;
        try {
            // Intentamos interpretar el formato sanitizado con Carbon::createFromFormat o parse()
            // Usamos createFromFormat para garantizar que la conversión sea correcta antes de la DB.
            $fechaHoraDBFormat = Carbon::createFromFormat('d/m/Y, h:i A', $fechaHoraInput)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            // Si falla, la variable queda null, y las reglas 'date' o 'required' lo atraparán.
        }

        return [
            'paciente_id' => ['required', 'exists:pacientes,id'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            
            // CORRECCIÓN SINTAXIS: Array de reglas simple para fecha_hora
            'fecha_hora' => [ 
                'required', 
                // Usamos la regla 'date' para mayor flexibilidad (deja que Carbon::parse haga el trabajo)
                'date', 
                // Si la regla 'date' falla, la conversión a DB fallará. Si pasa, usamos la hora actual.
                'after_or_equal:now',
                        
                // Validación de unicidad
                Rule::unique('citas')->where(function ($query) use ($fechaHoraDBFormat) {
                    
                    if (is_null($fechaHoraDBFormat)) {
                        return $query->whereRaw('1 = 0');
                    }
                    
                    return $query->where('doctor_id', $this->input('doctor_id'))
                                 // Usamos el formato convertido para la consulta.
                                 ->where('fecha_hora', $fechaHoraDBFormat);
                })->ignore($citaId),
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
            // El mensaje de 'date' es más genérico, pero ya no falla el compilador.
        ];
    }
}
