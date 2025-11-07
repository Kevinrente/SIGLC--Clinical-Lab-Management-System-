
@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="mb-4">
        <label for="identificacion" class="block text-gray-700 text-sm font-bold mb-2">Identificación:</label>
        <input type="text" name="identificacion" id="identificacion" value="{{ old('identificacion', $paciente->identificacion ?? '') }}" required class="@error('identificacion') border-red-500 @enderror border rounded w-full py-2 px-3">
        @error('identificacion') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
    </div>
    
    <div class="mb-4">
        <label for="fecha_nacimiento" class="block text-gray-700 text-sm font-bold mb-2">F. Nacimiento:</label>
        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="{{ old('fecha_nacimiento', $paciente->fecha_nacimiento ?? '') }}" required class="@error('fecha_nacimiento') border-red-500 @enderror border rounded w-full py-2 px-3">
        @error('fecha_nacimiento') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="mb-4">
        <label for="nombre" class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
        <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $paciente->nombre ?? '') }}" required class="@error('nombre') border-red-500 @enderror border rounded w-full py-2 px-3">
        @error('nombre') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
    </div>
    
    <div class="mb-4">
        <label for="apellido" class="block text-gray-700 text-sm font-bold mb-2">Apellido:</label>
        <input type="text" name="apellido" id="apellido" value="{{ old('apellido', $paciente->apellido ?? '') }}" required class="@error('apellido') border-red-500 @enderror border rounded w-full py-2 px-3">
        @error('apellido') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="mb-4">
        <label for="sexo" class="block text-gray-700 text-sm font-bold mb-2">Sexo:</label>
        <select name="sexo" id="sexo" required class="@error('sexo') border-red-500 @enderror border rounded w-full py-2 px-3">
            <option value="">-- Seleccionar --</option>
            @foreach(['M' => 'Masculino', 'F' => 'Femenino', 'Otro' => 'Otro'] as $key => $label)
                <option value="{{ $key }}" {{ old('sexo', $paciente->sexo ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('sexo') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
    </div>

    <div class="mb-4">
        <label for="telefono" class="block text-gray-700 text-sm font-bold mb-2">Teléfono:</label>
        <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $paciente->telefono ?? '') }}" class="@error('telefono') border-red-500 @enderror border rounded w-full py-2 px-3">
        @error('telefono') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
    </div>

    <div class="mb-4">
        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
        <input type="email" name="email" id="email" value="{{ old('email', $paciente->email ?? '') }}" required class="@error('email') border-red-500 @enderror border rounded w-full py-2 px-3">
        @error('email') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
    </div>
</div>

<div class="flex items-center justify-end mt-4">
    <a href="{{ route('pacientes.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">Cancelar</a>
    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
        Guardar Paciente
    </button>
</div>