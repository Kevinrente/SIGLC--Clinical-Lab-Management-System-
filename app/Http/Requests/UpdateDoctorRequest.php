<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Obtenemos el ID del doctor que estamos editando desde la ruta
        $doctorId = $this->route('doctor') ? $this->route('doctor')->id : null;

        return [
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            
            // Ignoramos el ID actual para que no diga "ya existe" si no la cambiamos
            'licencia_medica' => ['required', 'string', 'max:50', Rule::unique('doctors', 'licencia_medica')->ignore($doctorId)],
            
            'especialidad' => ['required', 'string', 'max:255'],

            // === NUEVOS CAMPOS FINANCIEROS ===
            'precio_consulta' => ['required', 'numeric', 'min:0'],
            'comision_lab_tipo' => ['required', 'in:porcentaje,fijo'],
            'comision_lab_valor' => ['required', 'numeric', 'min:0'],
        ];
    }
}