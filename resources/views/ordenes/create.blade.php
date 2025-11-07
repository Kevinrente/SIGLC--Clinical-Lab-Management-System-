<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Generar Orden de Examen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-xl sm:rounded-lg">
                
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Datos de la Cita</h3>
                <div class="mb-6 text-sm">
                    {{-- CORRECCIÓN 1: Acceso al nombre del Paciente --}}
                    <p><strong>Paciente:</strong> 
                        {{ $cita->paciente->nombre ?? 'N/A' }} {{ $cita->paciente->apellido ?? '' }} 
                        (ID: {{ $cita->paciente->id }})
                    </p>
                    
                    {{-- CORRECCIÓN 2: Acceso al nombre del Doctor --}}
                    <p><strong>Doctor:</strong> Dr. {{ $cita->doctor->apellido ?? 'N/A' }} ({{ $cita->doctor->especialidad ?? 'N/A' }})</p>
                    
                    <p><strong>Fecha/Hora:</strong> {{ $cita->fecha_hora->format('d/m/Y H:i A') }}</p>
                    <p><strong>Motivo:</strong> {{ $cita->motivo }}</p>
                </div>
                
                <form action="{{ route('ordenes.store') }}" method="POST">
                    @csrf
                    
                    <input type="hidden" name="cita_id" value="{{ $cita->id }}">
                    
                    <div class="mb-4">
                        <label for="examenes_solicitados" class="block text-gray-700 text-sm font-bold mb-2">
                            Exámenes Solicitados (Separar por comas):
                        </label>
                        <textarea name="examenes_solicitados" id="examenes_solicitados" rows="5"
                                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('examenes_solicitados') border-red-500 @enderror" 
                                  required>{{ old('examenes_solicitados') }}</textarea>
                        @error('examenes_solicitados')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Ejemplo: Hemograma, Glucosa en Ayunas, TSH.</p>
                    </div>

                    <div class="flex items-center justify-between mt-6">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Confirmar y Enviar Orden al Laboratorio
                        </button>
                        <a href="{{ route('citas.show', $cita) }}" class="inline-block align-baseline font-bold text-sm text-gray-600 hover:text-gray-800">
                            Cancelar
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>