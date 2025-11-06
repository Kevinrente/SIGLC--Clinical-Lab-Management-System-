<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Carga de Resultados de Laboratorio') }}</h2>
    </x-slot>

    <div class="py-12"><div class="max-w-xl mx-auto sm:px-6 lg:px-8"><div class="bg-white p-6 shadow-sm sm:rounded-lg">
        
        <h3 class="text-2xl font-bold mb-4">Orden #{{ $ordenExamen->id }} - {{ $ordenExamen->examen->nombre }}</h3>
        <p class="mb-6 text-gray-600">Paciente: {{ $ordenExamen->paciente->nombre }} {{ $ordenExamen->paciente->apellido }} | Doctor: Dr. {{ $ordenExamen->doctor->apellido }}</p>

        <form action="{{ route('laboratorio.storeResultado', $ordenExamen) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <p class="mb-4 p-3 bg-yellow-100 border-l-4 border-yellow-500 text-sm">
                ⚠️ El archivo debe estar en formato **PDF**. El sistema registrará un Hash de Seguridad para garantizar su integridad.
            </p>

            <div class="mb-4">
                <label for="resultado_pdf" class="block text-gray-700 text-sm font-bold mb-2">Archivo PDF del Resultado:</label>
                <input type="file" name="resultado_pdf" id="resultado_pdf" required 
                       class="@error('resultado_pdf') border-red-500 @enderror border rounded w-full py-2 px-3">
                @error('resultado_pdf') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end mt-6">
                <a href="{{ route('laboratorio.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">Cancelar</a>
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">
                    Subir Resultado y Finalizar Orden
                </button>
            </div>
        </form>

    </div></div></div></div>
</x-app-layout>