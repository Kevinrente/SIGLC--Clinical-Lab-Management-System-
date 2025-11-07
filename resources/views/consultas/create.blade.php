<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registro de Nota Médica') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-xl sm:rounded-lg">
                
                <h3 class="text-xl font-bold mb-4 border-b pb-2">Cita con {{ $cita->paciente->nombre ?? 'N/A' }} - {{ $cita->fecha_hora->format('d/m/Y H:i A') }}</h3>
                
                <form action="{{ route('consultas.store') }}" method="POST">
                    @csrf
                    
                    {{-- Campos Ocultos --}}
                    <input type="hidden" name="cita_id" value="{{ $cita->id }}">
                    <input type="hidden" name="paciente_id" value="{{ $cita->paciente_id }}">
                    
                    <div class="space-y-6">
                        
                        {{-- SÍNTOMAS REPORTADOS --}}
                        <div>
                            <label for="sintomas" class="block text-sm font-medium text-gray-700">Síntomas Reportados:</label>
                            <textarea name="sintomas" id="sintomas" rows="4" required
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('sintomas') border-red-500 @enderror">{{ old('sintomas') }}</textarea>
                            @error('sintomas')<p class="text-red-500 text-xs italic">{{ $message }}</p>@enderror
                        </div>
                        
                        {{-- DIAGNÓSTICO --}}
                        <div>
                            <label for="diagnostico" class="block text-sm font-medium text-gray-700">Diagnóstico (CIE-10 o descripción):</label>
                            <input type="text" name="diagnostico" id="diagnostico" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('diagnostico') border-red-500 @enderror" value="{{ old('diagnostico') }}">
                            @error('diagnostico')<p class="text-red-500 text-xs italic">{{ $message }}</p>@enderror
                        </div>

                        {{-- TRATAMIENTO --}}
                        <div>
                            <label for="tratamiento" class="block text-sm font-medium text-gray-700">Tratamiento / Plan Terapéutico:</label>
                            <textarea name="tratamiento" id="tratamiento" rows="5" required
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('tratamiento') border-red-500 @enderror">{{ old('tratamiento') }}</textarea>
                            @error('tratamiento')<p class="text-red-500 text-xs italic">{{ $message }}</p>@enderror
                        </div>

                        {{-- SOLICITUD DE EXÁMENES (Opción avanzada que requiere catálogo de Exámenes) --}}
                        {{-- Esto es el botón que lleva a generar la OrdenExamen si fuera necesario --}}
                        <p class="text-sm font-semibold pt-4">Nota: Para ordenar exámenes de laboratorio, cierre esta consulta y use el botón 'Generar Orden de Examen' en el detalle de la cita.</p>
                        
                    </div>

                    <div class="mt-8 pt-4 border-t flex justify-end space-x-3">
                        <a href="{{ route('citas.show', $cita) }}" class="inline-block align-baseline font-bold py-2 px-4 text-sm text-gray-600 hover:text-gray-800">
                            Volver / Cancelar
                        </a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">
                            Guardar Consulta y Finalizar
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>