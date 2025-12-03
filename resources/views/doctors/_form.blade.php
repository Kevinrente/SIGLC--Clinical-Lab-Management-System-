@csrf

{{-- 1. DATOS PERSONALES --}}
<h4 class="text-lg font-semibold mb-3 border-b pb-1 text-gray-700">Datos Personales y de Licencia</h4>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
    <div>
        <label for="nombre" class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
        <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $doctor->nombre ?? '') }}" required class="border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('nombre') border-red-500 @enderror">
        @error('nombre') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
    </div>
    
    <div>
        <label for="apellido" class="block text-gray-700 text-sm font-bold mb-2">Apellido:</label>
        <input type="text" name="apellido" id="apellido" value="{{ old('apellido', $doctor->apellido ?? '') }}" required class="border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('apellido') border-red-500 @enderror">
        @error('apellido') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div>
        <label for="licencia_medica" class="block text-gray-700 text-sm font-bold mb-2">Licencia Médica:</label>
        <input type="text" name="licencia_medica" id="licencia_medica" value="{{ old('licencia_medica', $doctor->licencia_medica ?? '') }}" required class="border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('licencia_medica') border-red-500 @enderror">
        @error('licencia_medica') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
    </div>
    
    <div>
        <label for="especialidad" class="block text-gray-700 text-sm font-bold mb-2">Especialidad:</label>
        <input type="text" name="especialidad" id="especialidad" value="{{ old('especialidad', $doctor->especialidad ?? '') }}" required class="border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('especialidad') border-red-500 @enderror">
        @error('especialidad') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
    </div>
</div>

{{-- CONFIGURACIÓN FINANCIERA (Aquí estaba el error) --}}
<div class="mt-6 border-t pt-4 bg-yellow-50 p-4 rounded-lg mb-6">
    <h4 class="text-lg font-semibold mb-4 text-gray-700">Configuración Financiera</h4>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        {{-- CAMPO PRECIO CONSULTA (El que da error) --}}
        <div>
            <label for="precio_consulta" class="block text-sm font-medium text-gray-700 mb-1">Precio Consulta ($)</label>
            <input type="number" step="0.01" name="precio_consulta" id="precio_consulta" 
                   value="{{ old('precio_consulta', $doctor->precio_consulta ?? 30) }}" required 
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('precio_consulta') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
        </div>

        {{-- CAMPO COMISIÓN LAB --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Comisión</label>
            <div class="flex gap-2">
                <select name="comision_lab_tipo" class="w-1/2 rounded-md border-gray-300 text-sm">
                    <option value="porcentaje" {{ (old('comision_lab_tipo', $doctor->comision_lab_tipo ?? '') == 'porcentaje') ? 'selected' : '' }}>Porcentaje (%)</option>
                    <option value="fijo" {{ (old('comision_lab_tipo', $doctor->comision_lab_tipo ?? '') == 'fijo') ? 'selected' : '' }}>Fijo ($)</option>
                </select>
                <input type="number" step="0.01" name="comision_lab_valor" 
                       value="{{ old('comision_lab_valor', $doctor->comision_lab_valor ?? 0) }}" required 
                       class="w-1/2 rounded-md border-gray-300 text-sm" placeholder="Valor">
            </div>
        </div>
    </div>
</div>


{{-- 3. CREDENCIALES (Solo al crear) --}}
@if (!isset($doctor->id))
    <div class="mt-6 border-t pt-4">
        <h4 class="text-lg font-semibold my-3 text-gray-700">Credenciales de Acceso</h4>
        <div class="mb-4">
            <label for="email_usuario" class="block text-gray-700 text-sm font-bold mb-2">Email para Login:</label>
            <input type="email" name="email_usuario" id="email_usuario" value="{{ old('email_usuario') }}" required class="border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email_usuario') border-red-500 @enderror">
            @error('email_usuario') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            <p class="text-xs text-gray-500 mt-1 italic">
                La contraseña inicial será la Licencia Médica ingresada arriba.
            </p>
        </div>
    </div>
@endif

{{-- 4. BOTONES --}}
<div class="flex items-center justify-end mt-8 border-t pt-4">
    <a href="{{ route('doctors.index') }}" class="text-gray-600 hover:text-gray-800 mr-4 font-medium">Cancelar</a>
    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded shadow-lg transform hover:scale-105 transition">
        {{ isset($doctor->id) ? 'Actualizar Doctor' : 'Registrar Doctor' }}
    </button>
</div>