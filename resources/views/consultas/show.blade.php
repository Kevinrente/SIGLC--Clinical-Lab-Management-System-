<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle de Consulta Médica') }} #{{ $consulta->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                
                {{-- Encabezado de la Ficha --}}
                <div class="px-6 py-5 bg-indigo-50 border-b border-indigo-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-bold text-indigo-900">
                            Historia Clínica
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-indigo-600">
                            Realizada el {{ $consulta->created_at->format('d/m/Y') }} a las {{ $consulta->created_at->format('H:i A') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white text-indigo-800 border border-indigo-200 shadow-sm">
                            Dr. {{ $consulta->doctor->usuario->name ?? 'No registrado' }}
                        </span>
                    </div>
                </div>

                {{-- Cuerpo de la Ficha --}}
                <div class="border-t border-gray-200">
                    <dl>
                        {{-- 1. DATOS DEL PACIENTE --}}
                        <div class="bg-white px-6 py-5 sm:grid sm:grid-cols-3 sm:gap-4 border-b border-gray-100">
                            <dt class="text-sm font-bold text-gray-500">Paciente</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <a href="{{ route('pacientes.show', $consulta->paciente_id) }}" class="text-indigo-600 hover:underline font-semibold">
                                    {{ $consulta->paciente->nombre }} {{ $consulta->paciente->apellido }}
                                </a>
                                <div class="text-gray-500 text-xs mt-1">
                                    CI: {{ $consulta->paciente->cedula }} • 
                                    {{ \Carbon\Carbon::parse($consulta->paciente->fecha_nacimiento)->age }} años
                                </div>
                            </dd>
                        </div>

                        {{-- 2. MOTIVO Y EXPLORACIÓN --}}
                        <div class="bg-gray-50 px-6 py-5 sm:grid sm:grid-cols-2 sm:gap-6 border-b border-gray-100">
                            <div>
                                <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Motivo de Consulta</dt>
                                <dd class="text-sm text-gray-900 bg-white p-3 rounded border border-gray-200">
                                    {{ $consulta->motivo_consulta ?? 'No especificado' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Exploración Física</dt>
                                <dd class="text-sm text-gray-900 bg-white p-3 rounded border border-gray-200">
                                    {{ $consulta->exploracion_fisica ?? 'Sin observaciones' }}
                                </dd>
                            </div>
                        </div>

                        {{-- 3. DIAGNÓSTICOS --}}
                        <div class="bg-white px-6 py-5 sm:grid sm:grid-cols-2 sm:gap-6 border-b border-gray-100">
                            <div>
                                <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Diagnóstico Presuntivo</dt>
                                <dd class="text-sm text-gray-600 italic">
                                    {{ $consulta->diagnostico_presuntivo ?? '-' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Diagnóstico Confirmado</dt>
                                <dd class="text-sm font-bold text-gray-900">
                                    {{ $consulta->diagnostico_confirmado ?? 'Sin diagnóstico' }}
                                </dd>
                            </div>
                        </div>

                        {{-- 4. RECETA MÉDICA (Tabla) --}}
                        <div class="bg-gray-50 px-6 py-5">
                            <dt class="text-sm font-bold text-gray-700 mb-3 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Receta Médica / Plan Terapéutico
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if(!empty($consulta->receta_medica))
                                    <div class="overflow-hidden border border-gray-200 rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medicamento</th>
                                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Indicaciones</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($consulta->receta_medica as $item)
                                                    <tr>
                                                        <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                                            {{ $item['medicamento'] ?? '-' }}
                                                        </td>
                                                        <td class="px-4 py-2 text-sm text-gray-600">
                                                            {{ $item['indicacion'] ?? '-' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-gray-500 italic text-sm">No se recetaron medicamentos en esta consulta.</p>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
                
                {{-- Botones de Acción --}}
                <div class="px-6 py-4 bg-gray-100 text-right flex justify-end items-center gap-3 border-t border-gray-200">
                    <a href="{{ route('consultas.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition">
                        Volver al Listado
                    </a>
                    
                    {{-- BOTÓN DE DESCARGA DE RECETA (Activo) --}}
                    @if(!empty($consulta->receta_medica))
                        <a href="{{ route('consultas.receta.pdf', $consulta->id) }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Descargar Receta
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>