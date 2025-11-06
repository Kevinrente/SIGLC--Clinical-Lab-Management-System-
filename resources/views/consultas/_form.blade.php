@csrf

{{-- Campos ocultos para vincular la Consulta --}}
<input type="hidden" name="cita_id" value="{{ $cita->id ?? $consulta->cita_id ?? '' }}">
<input type="hidden" name="paciente_id" value="{{ $cita->paciente_id ?? $consulta->paciente_id ?? '' }}">

<div class="mb-6 p-4 border rounded-lg bg-gray-50">
    <h4 class="text-xl font-bold mb-2 text-indigo-700">Información del Paciente y Cita</h4>
    @php
        // Aseguramos que las variables existan para evitar errores si solo una es pasada
        $paciente = $cita->paciente ?? $consulta->paciente ?? null;
        $doctor = $cita->doctor ?? $consulta->doctor ?? null;
    @endphp
    
    @if($paciente)
        <p><strong>Paciente:</strong> {{ $paciente->nombre }} {{ $paciente->apellido }} ({{ $paciente->identificacion }})</p>
    @endif
    @if($doctor)
        <p><strong>Doctor Asignado:</strong> Dr. {{ $doctor->nombre }} {{ $doctor->apellido }} ({{ $doctor->especialidad }})</p>
    @endif
    @if(isset($cita))
        <p><strong>Cita:</strong> {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/M/Y H:i') }} | Motivo: {{ $cita->motivo }}</p>
    @endif
</div>

<!-- Síntomas -->
<div class="mb-4">
    <label for="sintomas" class="block text-gray-700 text-sm font-bold mb-2">Síntomas Reportados:</label>
    <textarea name="sintomas" id="sintomas" rows="4" required class="@error('sintomas') border-red-500 @enderror border rounded w-full py-2 px-3">{{ old('sintomas', $consulta->sintomas ?? '') }}</textarea>
    @error('sintomas') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
</div>

<!-- Diagnóstico (Campo Crucial) -->
<div class="mb-4">
    <label for="diagnostico" class="block text-gray-700 text-sm font-bold mb-2">Diagnóstico (CIE-10 o descripción):</label>
    <textarea name="diagnostico" id="diagnostico" rows="4" required class="@error('diagnostico') border-red-500 @enderror border rounded w-full py-2 px-3">{{ old('diagnostico', $consulta->diagnostico ?? '') }}</textarea>
    @error('diagnostico') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
</div>

<!-- Tratamiento y Recomendaciones -->
<div class="mb-6">
    <label for="tratamiento" class="block text-gray-700 text-sm font-bold mb-2">Tratamiento / Plan Terapéutico:</label>
    <textarea name="tratamiento" id="tratamiento" rows="4" class="@error('tratamiento') border-red-500 @enderror border rounded w-full py-2 px-3">{{ old('tratamiento', $consulta->tratamiento ?? '') }}</textarea>
    @error('tratamiento') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
</div>

<!-- ** SECCIÓN AÑADIDA: SOLICITUD DE EXÁMENES ** -->
@if(isset($examenes))
<h4 class="text-lg font-semibold mt-8 mb-3 border-b pb-1 text-teal-700">Solicitud de Exámenes de Laboratorio</h4>

<div class="mb-6">
    <p class="text-sm font-semibold text-gray-700 mb-2">Marque los exámenes requeridos:</p>
    <div class="bg-gray-100 p-4 rounded-lg grid grid-cols-2 md:grid-cols-3 gap-3 max-h-60 overflow-y-auto">
        @forelse($examenes as $examen)
            @php
                // Determina si esta orden ya existe para deshabilitar la casilla en edición
                $isDisabled = isset($consulta) && $consulta->ordenesExamen->contains('examen_id', $examen->id);
                $isChecked = old('examenes_solicitados') ? in_array($examen->id, old('examenes_solicitados')) : $isDisabled;
            @endphp
            <label class="inline-flex items-center text-sm">
                <input type="checkbox" name="examenes_solicitados[]" value="{{ $examen->id }}" 
                       class="form-checkbox h-5 w-5 text-teal-600 rounded"
                       {{ $isChecked ? 'checked' : '' }}
                       {{ $isDisabled ? 'disabled' : '' }}>
                <span class="ml-2 text-gray-700">{{ $examen->nombre }} (${{ number_format($examen->precio, 2) }})</span>
            </label>
        @empty
            <p class="text-gray-500 italic col-span-3">No hay exámenes de laboratorio registrados en el catálogo.</p>
        @endforelse
    </div>
    @error('examenes_solicitados') 
        <p class="text-red-500 text-xs italic mt-1">Debe seleccionar al menos un examen si está creando una solicitud.</p> 
    @enderror
</div>
@endif
<!-- ** FIN SECCIÓN EXÁMENES ** -->

<div class="flex items-center justify-end border-t pt-4">
    <a href="{{ route('citas.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">Volver a la Agenda</a>
    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
        {{ isset($consulta->id) ? 'Actualizar Consulta' : 'Guardar Consulta y Finalizar' }}
    </button>
</div>