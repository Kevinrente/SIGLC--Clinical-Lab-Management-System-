<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Generar Orden de Examen - Selección de Estudios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                {{-- BLOQUE DE MENSAJES DE ÉXITO --}}
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">¡Éxito!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                {{-- BLOQUE DE ERRORES GENERALES --}}
                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Error:</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                {{-- ERRORES DE VALIDACIÓN (Si faltó marcar algo) --}}
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <strong class="font-bold">Por favor corrige los siguientes errores:</strong>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- AQUÍ EMPIEZA TU FORMULARIO ACTUAL --}}
                <form action="{{ route('ordenes.store') }}" method="POST">
                    {{-- ... resto de tu código ... --}}

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-xl sm:rounded-lg">
                
                {{-- Resumen de la Cita --}}
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 mb-6 text-sm">
                    <h3 class="font-bold text-blue-800 mb-2">Datos del Paciente y Cita</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Usamos ?? '' para evitar errores si falta algún dato --}}
                        <p><strong>Paciente:</strong> {{ $cita->paciente->nombre ?? '' }} {{ $cita->paciente->apellido ?? '' }}</p>
                        <p><strong>Doctor:</strong> Dr. {{ $cita->doctor->apellido ?? '' }}</p>
                        <p><strong>Motivo:</strong> {{ $cita->motivo }}</p>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <strong class="font-bold">¡Ups!</strong>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                {{-- Formulario que apunta a ordenes.store --}}
                <form action="{{ route('ordenes.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="cita_id" value="{{ $cita->id }}">
    
                    {{-- CORRECCIÓN: Agregamos el ID del paciente, que es obligatorio --}}
                    <input type="hidden" name="paciente_id" value="{{ $cita->paciente_id }}">
                    
                    <h3 class="text-lg font-bold mb-4 text-gray-800 border-b pb-2">Catálogo de Exámenes Disponibles</h3>

                    {{-- Mensaje de Error de Validación --}}
                    @error('examenes')
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            <span class="block sm:inline">{{ $message }}</span>
                        </div>
                    @enderror

                    {{-- Iteración de Categorías y Exámenes --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if(isset($examenesPorCategoria) && $examenesPorCategoria->count() > 0)
                            @foreach($examenesPorCategoria as $categoria => $examenes)
                                <div class="border rounded-lg p-4 shadow-sm bg-gray-50">
                                    <h4 class="font-bold text-indigo-700 mb-3 border-b border-gray-200 pb-1">
                                        {{ $categoria }}
                                    </h4>
                                    <div class="space-y-2">
                                        @foreach($examenes as $examen)
                                            <label class="flex items-center space-x-3 cursor-pointer hover:bg-white p-1 rounded transition">
                                                {{-- Checkbox que envía el ID del examen --}}
                                                <input type="checkbox" 
                                                       name="examenes[]" 
                                                       value="{{ $examen->id }}" 
                                                       class="form-checkbox h-5 w-5 text-indigo-600 rounded focus:ring-indigo-500 transition duration-150 ease-in-out"
                                                       {{ (is_array(old('examenes')) && in_array($examen->id, old('examenes'))) ? 'checked' : '' }}>
                                                <span class="text-gray-700 text-sm">{{ $examen->nombre }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-span-2 p-4 bg-yellow-50 text-yellow-700 border border-yellow-200 rounded">
                                No hay exámenes registrados en el catálogo. Por favor ejecute el seeder de exámenes.
                            </div>
                        @endif
                    </div>

                    <div class="mt-8 flex justify-end items-center border-t pt-4">
                        <a href="{{ route('citas.show', $cita) }}" class="text-gray-600 hover:text-gray-800 mr-4 font-medium">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded shadow-lg transform hover:scale-105 transition duration-150">
                            Confirmar y Enviar Orden
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>