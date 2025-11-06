<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Catálogo de Exámenes de Laboratorio') }}</h2>
    </x-slot>

    <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8"><div class="bg-white overflow-hidden shadow-sm sm:rounded-lg"><div class="p-6 text-gray-900">
        @if (session('success'))<div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">{{ session('success') }}</div>@endif

        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('examenes.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Crear Nuevo Examen</a>
        </div>

        <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200">
            <thead><tr>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre del Examen</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ETA (Días)</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr></thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($examenes as $examen)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $examen->nombre }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $examen->codigo ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($examen->precio, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $examen->tiempo_entrega_dias }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('examenes.edit', $examen) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                            {{-- La eliminación se omite, solo se permite Edición y Creación --}}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">No hay exámenes registrados.</td></tr>
                @endforelse
            </tbody>
        </table></div>
        <div class="mt-4">{{ $examenes->links() }}</div>
    </div></div></div></div>
</x-app-layout>