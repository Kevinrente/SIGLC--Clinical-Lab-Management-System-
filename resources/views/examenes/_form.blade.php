@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="mb-4">
        <label for="nombre" class="block text-gray-700 text-sm font-bold mb-2">Nombre del Examen:</label>
        <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $examen->nombre ?? '') }}" required class="@error('nombre') border-red-500 @enderror border rounded w-full py-2 px-3">
        @error('nombre') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
    </div>
    
    <div class="mb-4">
        <label for="codigo" class="block text-gray-700 text-sm font-bold mb-2">Código Interno:</label>
        <input type="text" name="codigo" id="codigo" value="{{ old('codigo', $examen->codigo ?? '') }}" class="@error('codigo') border-red-500 @enderror border rounded w-full py-2 px-3">
        @error('codigo') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="mb-4">
        <label for="precio" class="block text-gray-700 text-sm font-bold mb-2">Precio ($):</label>
        <input type="number" step="0.01" min="0" name="precio" id="precio" value="{{ old('precio', $examen->precio ?? '') }}" required class="@error('precio') border-red-500 @enderror border rounded w-full py-2 px-3">
        @error('precio') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
    </div>
    
    <div class="mb-4">
        <label for="tiempo_entrega_dias" class="block text-gray-700 text-sm font-bold mb-2">Tiempo de Entrega (días):</label>
        <input type="number" min="1" name="tiempo_entrega_dias" id="tiempo_entrega_dias" value="{{ old('tiempo_entrega_dias', $examen->tiempo_entrega_dias ?? 1) }}" required class="@error('tiempo_entrega_dias') border-red-500 @enderror border rounded w-full py-2 px-3">
        @error('tiempo_entrega_dias') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
    </div>
</div>

<div class="flex items-center justify-end mt-6">
    <a href="{{ route('examenes.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">Cancelar</a>
    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
        {{ isset($examen->id) ? 'Actualizar Examen' : 'Registrar Examen' }}
    </button>
</div>