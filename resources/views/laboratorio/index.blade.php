<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Módulo de Laboratorio - Órdenes Pendientes') }}</h2>
    </x-slot>

    <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8"><div class="bg-white overflow-hidden shadow-sm sm:rounded-lg"><div class="p-6 text-gray-900">
        @if (session('success'))<div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">{{ session('success') }}</div>@endif
        @if (session('error'))<div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">{{ session('error') }}</div>@endif

        <h4 class="text-lg font-semibold mb-3">Órdenes de Examen Pendientes de Resultado</h4>

        <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200">
            <thead><tr>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Orden</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Examen Solicitado</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paciente</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor Solicitante</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr></thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($ordenes as $orden)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $orden->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $orden->examen->nombre }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $orden->paciente->apellido }}, {{ $orden->paciente->nombre }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Dr. {{ $orden->doctor->apellido }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($orden->estado == 'Finalizado') bg-green-100 text-green-800
                                @elseif($orden->estado == 'Solicitado') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ $orden->estado }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if($orden->estado != 'Finalizado')
                            <a href="{{ route('laboratorio.subirResultado', $orden) }}" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-1 px-3 rounded text-xs">
                                Subir Resultado
                            </a>
                            @else
                                <span class="text-gray-500">Completada</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500 italic">No hay órdenes pendientes de análisis.</td></tr>
                @endforelse
            </tbody>
        </table></div>
        <div class="mt-4">{{ $ordenes->links() }}</div>
    </div></div></div></div>
</x-app-layout>