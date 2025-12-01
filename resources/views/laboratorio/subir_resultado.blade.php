<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cargar Resultados de Laboratorio') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    {{-- Encabezado con datos del Paciente --}}
                    <div class="mb-8 bg-blue-50 p-4 rounded-lg border border-blue-100">
                        <h3 class="text-lg font-bold text-blue-800 mb-2">Orden #{{ $orden->id }} - {{ $orden->paciente->nombre }} {{ $orden->paciente->apellido }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-700">
                            <p><strong>Doctor:</strong> {{ $orden->doctor->usuario->name ?? 'N/A' }}</p>
                            <p><strong>Fecha Solicitud:</strong> {{ $orden->created_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Edad:</strong> {{ $orden->paciente->edad }} años</p>
                        </div>
                    </div>

                    <form action="{{ route('laboratorio.update', $orden->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Iteramos sobre los exámenes de la orden --}}
                        <div class="space-y-8">
                            @foreach($orden->examenes as $examen)
                                <div class="border rounded-lg p-5 shadow-sm hover:shadow-md transition">
                                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                                        <h4 class="text-xl font-bold text-indigo-700">{{ $examen->nombre }}</h4>
                                        <span class="text-xs font-mono bg-gray-100 px-2 py-1 rounded text-gray-500">
                                            Ref: {{ $examen->valor_referencia ?? 'N/A' }}
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        {{-- CASO 1: Examen Complejo (Tiene campos dinámicos en JSON) --}}
                                        @if(!empty($examen->campos_dinamicos))
                                            @foreach($examen->campos_dinamicos as $campo)
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                                        {{ $campo }}
                                                    </label>
                                                    <input type="text" 
                                                           {{-- Guardamos como array asociativo: resultados[examen_id][nombre_campo] --}}
                                                           name="resultados[{{ $examen->id }}][{{ $campo }}]"
                                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                           placeholder="Ingrese valor..."
                                                           required>
                                                </div>
                                            @endforeach

                                        {{-- CASO 2: Examen Simple (Solo un valor) --}}
                                        @else
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    Resultado
                                                </label>
                                                <div class="flex rounded-md shadow-sm">
                                                    <input type="text" 
                                                           name="resultados[{{ $examen->id }}][valor]"
                                                           class="flex-1 rounded-none rounded-l-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                           placeholder="Ej: 95"
                                                           required>
                                                    <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                                        {{ $examen->unidades ?? '-' }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Campo de Observaciones (Opcional por cada examen) --}}
                                        <div class="md:col-span-2 mt-2">
                                            <label class="block text-xs text-gray-500">Observaciones (Opcional)</label>
                                            <input type="text" 
                                                   name="observaciones[{{ $examen->id }}]" 
                                                   class="w-full mt-1 text-sm border-gray-200 rounded focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                   placeholder="Notas internas...">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Acciones Finales --}}
                        <div class="mt-8 flex justify-end items-center gap-4 border-t pt-6">
                            <a href="{{ route('laboratorio.index') }}" class="text-gray-600 hover:text-gray-900">Cancelar</a>
                            
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded shadow-lg flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Guardar Resultados y Generar PDF
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>