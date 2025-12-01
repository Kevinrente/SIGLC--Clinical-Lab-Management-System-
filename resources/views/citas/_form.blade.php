@csrf

{{-- 1. PACIENTE --}}
<div class="mb-4">
    <label for="paciente_id" class="block text-gray-700 text-sm font-bold mb-2">Paciente:</label>
    <select name="paciente_id" id="paciente_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        <option value="">-- Seleccionar Paciente --</option>
        @foreach($pacientes as $paciente)
            <option value="{{ $paciente->id }}" 
                {{ (old('paciente_id') == $paciente->id || (isset($cita) && $cita->paciente_id == $paciente->id)) ? 'selected' : '' }}>
                {{ $paciente->nombre }} {{ $paciente->apellido }}
            </option>
        @endforeach
    </select>
    @error('paciente_id') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
</div>

{{-- 2. DOCTOR --}}
<div class="mb-4">
    <label for="doctor_id" class="block text-gray-700 text-sm font-bold mb-2">Doctor:</label>
    <select name="doctor_id" id="doctor_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        <option value="">-- Seleccionar Doctor --</option>
        @foreach($doctors as $doctor)
            <option value="{{ $doctor->id }}" 
                {{ (old('doctor_id') == $doctor->id || (isset($cita) && $cita->doctor_id == $doctor->id)) ? 'selected' : '' }}>
                Dr. {{ $doctor->apellido }} ({{ $doctor->especialidad }})
            </option>
        @endforeach
    </select>
    @error('doctor_id') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
</div>

{{-- 3. FECHA Y HORA --}}
<div class="mb-4">
    <label for="fecha_hora" class="block text-gray-700 text-sm font-bold mb-2">Fecha y Hora:</label>
    {{-- NOTA: datetime-local necesita el formato Y-m-d\TH:i para mostrar el valor --}}
    <input type="datetime-local" name="fecha_hora" id="fecha_hora" 
           value="{{ old('fecha_hora', isset($cita) && $cita->fecha_hora ? $cita->fecha_hora->format('Y-m-d\TH:i') : '') }}"
           class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
    @error('fecha_hora') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
</div>

{{-- 4. MOTIVO (Faltaba) --}}
<div class="mb-4">
    <label for="motivo" class="block text-gray-700 text-sm font-bold mb-2">Motivo de la Cita:</label>
    <textarea name="motivo" id="motivo" rows="3" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('motivo', $cita->motivo ?? '') }}</textarea>
    @error('motivo') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
</div>

{{-- 5. ESTADO (Faltaba - CRÍTICO PARA EL DOCTOR) --}}
<div class="mb-6">
    <label for="estado" class="block text-gray-700 text-sm font-bold mb-2">Estado:</label>
    <select name="estado" id="estado" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        @foreach(['Pendiente', 'Confirmada', 'Cancelada', 'Completada'] as $estadoOption)
            <option value="{{ $estadoOption }}" 
                {{ (old('estado') == $estadoOption || (isset($cita) && $cita->estado == $estadoOption)) ? 'selected' : '' }}>
                {{ $estadoOption }}
            </option>
        @endforeach
    </select>
    @error('estado') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
</div>

<div class="flex items-center justify-between">
    <a href="{{ route('citas.index') }}" class="text-gray-500 hover:text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
        Cancelar
    </a>
    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
        Guardar Cita
    </button>
</div>