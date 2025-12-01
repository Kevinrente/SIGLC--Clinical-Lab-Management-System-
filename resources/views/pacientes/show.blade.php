<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Historial Clínico: ' . $paciente->nombre . ' ' . $paciente->apellido) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
            {{-- 1. INFORMACIÓN DEL PACIENTE --}}
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <h3 class="text-xl font-bold mb-4 border-b pb-2 text-indigo-700">Información Personal</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                    {{-- CORRECCIÓN: Usamos 'cedula' en lugar de 'identificacion' --}}
                    <div><span class="font-semibold text-gray-600">Cédula:</span> {{ $paciente->cedula }}</div>
                    
                    {{-- Formato de fecha seguro --}}
                    <div><span class="font-semibold text-gray-600">Nacimiento:</span> 
                        {{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->format('d/m/Y') }} 
                        ({{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age }} años)
                    </div>
                    
                    <div><span class="font-semibold text-gray-600">Sexo:</span> {{ $paciente->sexo }}</div>
                    <div><span class="font-semibold text-gray-600">Teléfono:</span> {{ $paciente->telefono ?? 'N/A' }}</div>
                    <div><span class="font-semibold text-gray-600">Email:</span> {{ $paciente->email ?? 'N/A' }}</div>
                </div>
                
                <div class="mt-4 border-t pt-3 flex justify-between items-center">
                    <span class="text-xs text-gray-400">Registrado el: {{ $paciente->created_at->format('d/m/Y') }}</span>
                    <a href="{{ route('pacientes.edit', $paciente) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-bold flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Editar Datos
                    </a>
                </div>
            </div>

            {{-- 2. HISTORIAL DE CONSULTAS MÉDICAS (Tabla Detallada) --}}
            <div class="bg-white p-6 shadow-sm sm:rounded-lg border border-gray-200">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="text-lg font-bold text-gray-800">Historial de Consultas ({{ $paciente->consultas->count() }})</h3>
                    
                    {{-- Botón para nueva consulta desde aquí (opcional) --}}
                    {{-- <a href="#" class="bg-indigo-600 text-white text-xs px-3 py-2 rounded hover:bg-indigo-700">Nueva Consulta</a> --}}
                </div>

                @if($paciente->consultas->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doctor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Diagnóstico / Motivo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($paciente->consultas->sortByDesc('created_at') as $consulta)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $consulta->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            Dr. {{ $consulta->doctor->usuario->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            <span class="font-semibold">{{ Str::limit($consulta->diagnostico ?? $consulta->motivo_consulta, 40) }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium">
                                            {{-- ENLACE AL DETALLE QUE CREAMOS --}}
                                            <a href="{{ route('consultas.show', $consulta->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">
                                                Ver Detalle
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4 bg-gray-50 rounded border border-dashed border-gray-300">
                        <p class="text-gray-500 italic">No hay consultas médicas registradas para este paciente.</p>
                    </div>
                @endif
            </div>

            {{-- 3. HISTORIAL DE CITAS (Agenda) --}}
            <div class="bg-white p-6 shadow-sm sm:rounded-lg border border-gray-200">
                <h3 class="text-lg font-bold mb-4 border-b pb-2 text-gray-800">Historial de Citas ({{ $paciente->citas->count() }})</h3>
                
                @if($paciente->citas->count() > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach($paciente->citas->sortByDesc('fecha_hora') as $cita)
                            <li class="py-3 flex justify-between items-center hover:bg-gray-50 px-2 rounded transition">
                                <div>
                                    <span class="font-bold text-gray-700">{{ $cita->fecha_hora->format('d/m/Y') }}</span>
                                    <span class="text-gray-500 text-sm ml-2">{{ $cita->fecha_hora->format('H:i A') }}</span>
                                    <div class="text-sm text-gray-600">
                                        Dr. {{ $cita->doctor->usuario->name ?? 'N/A' }}
                                    </div>
                                </div>
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($cita->estado == 'Completada') bg-green-100 text-green-800 
                                    @elseif($cita->estado == 'Cancelada') bg-red-100 text-red-800 
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ $cita->estado }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500 italic py-2">No hay citas registradas.</p>
                @endif
            </div>
            
        </div>
    </div>
</x-app-layout>