@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="mb-4">
        <label for="paciente_id" class="block text-gray-700 text-sm font-bold mb-2">Paciente:</label>
        <select name="paciente_id" id="paciente_id" required class="@error('paciente_id') border-red-500 @enderror border rounded w-full py-2 px-3">
            <option value="">-- Seleccione un Paciente --</option>
            @foreach($pacientes as $paciente)
                <option value="{{ $paciente->id }}" {{ old('paciente_id', $cita->paciente_id ?? '') == $paciente->id ? 'selected' : '' }}>
                    {{ $paciente->apellido }}, {{ $paciente->nombre }} ({{ $paciente->identificacion }})
                </option>
            @endforeach
        </select>
        @error('paciente_id') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
    </div>
    
    <div class="mb-4">
        <label for="doctor_id" class="block text-gray-700 text-sm font-bold mb-2">Doctor/Especialista:</label>
        <select name="doctor_id" id="doctor_id" required class="@error('doctor_id') border-red-500 @enderror border rounded w-full py-2 px-3">
            <option value="">-- Seleccione un Doctor --</option>
            @foreach($doctors as $doctor)
                <option value="{{ $doctor->id }}" {{ old('doctor_id', $cita->doctor_id ?? '') == $doctor->id ? 'selected' : '' }}>
                    Dr. {{ $doctor->apellido }} ({{ $doctor->especialidad }})
                </option>
            @endforeach
        </select>
        @error('doctor_id') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="mb-4">
        <label for="fecha_hora" class="block text-gray-700 text-sm font-bold mb-2">Fecha y Hora (YYYY-MM-DD HH:MM:SS):</label>
        <input type="datetime-local" name="fecha_hora" id="fecha_hora" 
               value="{{ old('fecha_hora', isset($cita->fecha_hora) ? \Carbon\Carbon::parse($cita->fecha_hora)->format('Y-m-d\TH:i') : '') }}" 
               required class="@error('fecha_hora') border-red-500 @enderror border rounded w-full py-2 px-3">
        @error('fecha_hora') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
    </div>
    
    <div class="mb-4">
        <label for="estado" class="block text-gray-700 text-sm font-bold mb-2">Estado:</label>
        <select name="estado" id="estado" required class="@error('estado') border-red-500 @enderror border rounded w-full py-2 px-3">
            @php
                $estados = ['Pendiente', 'Confirmada', 'Cancelada', 'Completada'];
            @endphp
            @foreach($estados as $estado)
                <option value="{{ $estado }}" {{ old('estado', $cita->estado ?? 'Pendiente') == $estado ? 'selected' : '' }}>
                    {{ $estado }}
                </option>
            @endforeach
        </select>
        @error('estado') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mb-4">
    <label for="motivo" class="block text-gray-700 text-sm font-bold mb-2">Motivo de la Cita:</label>
    <textarea name="motivo" id="motivo" rows="2" class="@error('motivo') border-red-500 @enderror border rounded w-full py-2 px-3">{{ old('motivo', $cita->motivo ?? '') }}</textarea>
    @error('motivo') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
</div>

<div class="flex items-center justify-end mt-4">
    <a href="{{ route('citas.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">Cancelar</a>
    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
        Guardar Cita
    </button>
</div>