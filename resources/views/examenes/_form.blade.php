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

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
    <div class="md:col-span-2">
        <h4 class="text-sm font-bold text-gray-700 mb-2">Datos Técnicos (Opcional)</h4>
        <p class="text-xs text-gray-500 mb-2">Llene estos campos solo si el examen requiere unidades de medida o rangos (Ej: Glucosa). Déjelos vacíos para exámenes simples.</p>
    </div>

    {{-- UNIDADES --}}
    <div>
        <label for="unidades" class="block text-sm font-medium text-gray-700">Unidades de Medida</label>
        <input type="text" name="unidades" id="unidades" 
               value="{{ old('unidades', $examen->unidades ?? '') }}"
               placeholder="Ej: mg/dL, g/L, %"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>

    {{-- VALOR DE REFERENCIA --}}
    <div>
        <label for="valor_referencia" class="block text-sm font-medium text-gray-700">Rango / Valor de Referencia</label>
        <input type="text" name="valor_referencia" id="valor_referencia" 
               value="{{ old('valor_referencia', $examen->valor_referencia ?? '') }}"
               placeholder="Ej: 70 - 110, Negativo, < 200"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>
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