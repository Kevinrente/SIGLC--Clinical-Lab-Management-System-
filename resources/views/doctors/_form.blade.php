@csrf

<h4 class="text-lg font-semibold mb-3 border-b pb-1">Datos Personales y de Licencia</h4>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="mb-4">
        <label for="nombre" class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
        <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $doctor->nombre ?? '') }}" required class="@error('nombre') border-red-500 @enderror border rounded w-full py-2 px-3">
        @error('nombre') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
    </div>
    
    <div class="mb-4">
        <label for="apellido" class="block text-gray-700 text-sm font-bold mb-2">Apellido:</label>
        <input type="text" name="apellido" id="apellido" value="{{ old('apellido', $doctor->apellido ?? '') }}" required class="@error('apellido') border-red-500 @enderror border rounded w-full py-2 px-3">
        @error('apellido') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="mb-4">
        <label for="licencia_medica" class="block text-gray-700 text-sm font-bold mb-2">Licencia Médica:</label>
        <input type="text" name="licencia_medica" id="licencia_medica" value="{{ old('licencia_medica', $doctor->licencia_medica ?? '') }}" required class="@error('licencia_medica') border-red-500 @enderror border rounded w-full py-2 px-3">
        @error('licencia_medica') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
    </div>
    
    <div class="mb-4">
        <label for="especialidad" class="block text-gray-700 text-sm font-bold mb-2">Especialidad:</label>
        <input type="text" name="especialidad" id="especialidad" value="{{ old('especialidad', $doctor->especialidad ?? '') }}" required class="@error('especialidad') border-red-500 @enderror border rounded w-full py-2 px-3">
        @error('especialidad') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
    </div>
</div>

@if (!isset($doctor->id))
<h4 class="text-lg font-semibold my-3 border-b pb-1">Credenciales de Acceso</h4>

<div class="mb-4">
    <label for="email_usuario" class="block text-gray-700 text-sm font-bold mb-2">Email para Login (Usuario):</label>
    <input type="email" name="email_usuario" id="email_usuario" value="{{ old('email_usuario') }}" required class="@error('email_usuario') border-red-500 @enderror border rounded w-full py-2 px-3">
    @error('email_usuario') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
    <p class="text-xs text-gray-500 mt-1">
        **Nota:** La contraseña inicial será la Licencia Médica.
    </p>
</div>
@endif

<div class="flex items-center justify-end mt-6">
    <a href="{{ route('doctors.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">Cancelar</a>
    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
        {{ isset($doctor->id) ? 'Actualizar Doctor' : 'Registrar Doctor' }}
    </button>
</div>