<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Laboratorio - Órdenes Pendientes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">{{ session('success') }}</div>
                @endif
                
                <h3 class="text-xl font-bold mb-4 border-b pb-2">Órdenes Activas (Pendientes/En Análisis)</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orden ID</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paciente</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor Solicitante</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Solicitud</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($ordenes as $orden)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $orden->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $orden->paciente->nombre_completo ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $orden->doctor->usuario->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-normal text-xs max-w-xs">{{ Str::limit($orden->examenes_solicitados, 80) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                            @if($orden->estado == 'En Análisis') bg-blue-100 text-blue-800
                                            @elseif($orden->estado == 'Muestra Tomada') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ $orden->estado }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('laboratorio.subirResultado', $orden) }}" class="text-indigo-600 hover:text-indigo-900">
                                            Gestionar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500 italic">No hay órdenes pendientes de procesar.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>