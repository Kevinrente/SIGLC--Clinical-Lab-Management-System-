<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Subir Resultado Orden #') . $ordenExamen->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-xl sm:rounded-lg">
                
                @error('general')
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ $message }}</span>
                    </div>
                @enderror

                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Detalle: {{ $ordenExamen->paciente->nombre_completo ?? 'N/A' }}</h3>
                <p class="mb-4 text-sm text-gray-600">Exámenes Solicitados: **{{ $ordenExamen->examenes_solicitados }}**</p>

                <form action="{{ route('laboratorio.storeResultado', $ordenExamen) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="estado_actual" class="block text-gray-700 text-sm font-bold mb-2">Cambiar Estado:</label>
                        <select name="estado_actual" id="estado_actual" required 
                                class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('estado_actual') border-red-500 @enderror">
                            <option value="Muestra Tomada" @if($ordenExamen->estado == 'Muestra Tomada') selected @endif>Muestra Tomada</option>
                            <option value="En Análisis" @if($ordenExamen->estado == 'En Análisis') selected @endif>En Análisis</option>
                            <option value="Finalizado">Finalizado (Adjuntar Resultado)</option>
                        </select>
                        @error('estado_actual')<p class="text-red-500 text-xs italic">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="resultado_file" class="block text-gray-700 text-sm font-bold mb-2">
                            Adjuntar Archivo de Resultado (PDF/JPG/PNG):
                        </label>
                        <input type="file" name="resultado_file" id="resultado_file" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('resultado_file') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">Solo requerido al cambiar a estado 'Finalizado'. Máx 5MB.</p>
                        @error('resultado_file')<p class="text-red-500 text-xs italic">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex items-center justify-between mt-6">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Guardar Cambios y Archivo
                        </button>
                        <a href="{{ route('laboratorio.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-600 hover:text-gray-800">
                            Volver
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>