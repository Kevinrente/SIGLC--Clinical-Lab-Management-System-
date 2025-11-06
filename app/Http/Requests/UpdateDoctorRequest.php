<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('gestion.administracion');
    }

    public function rules(): array
    {
        $doctorId = $this->route('doctor')->id;

        return [
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            // Ignorar la licencia médica del registro actual
            'licencia_medica' => ['required', 'string', 'max:50', Rule::unique('doctors', 'licencia_medica')->ignore($doctorId)],
            'especialidad' => ['required', 'string', 'max:100'],
            
            // Nota: El email del usuario asociado debe actualizarse a través de la gestión de usuarios,
            // pero el nombre y apellido se actualizan desde aquí.
        ];
    }
}
