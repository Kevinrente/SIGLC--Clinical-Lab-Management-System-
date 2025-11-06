<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Mi Historial de Consultas Médicas') }}</h2>
    </x-slot>

    <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8"><div class="bg-white overflow-hidden shadow-sm sm:rounded-lg"><div class="p-6 text-gray-900">
        @if (session('success'))<div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">{{ session('success') }}</div>@endif
        @if (session('error'))<div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">{{ session('error') }}</div>@endif

        <h4 class="text-lg font-semibold mb-3">Consultas Registradas por Dr. {{ Auth::user()->name }}</h4>

        <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200">
            <thead><tr>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paciente</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diagnóstico Principal</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cita Asociada</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr></thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($consultas as $consulta)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $consulta->created_at->format('d/M/Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $consulta->paciente->apellido }}, {{ $consulta->paciente->nombre }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">{{ Str::limit($consulta->diagnostico, 50) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($consulta->cita)
                                <a href="{{ route('citas.show', $consulta->cita) }}" class="text-blue-600 hover:underline">Ver Cita</a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('consultas.show', $consulta) }}" class="text-teal-600 hover:text-teal-900 mr-3">Ver Detalle</a>
                            <a href="{{ route('consultas.edit', $consulta) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">No has registrado consultas aún.</td></tr>
                @endforelse
            </tbody>
        </table></div>
        <div class="mt-4">{{ $consultas->links() }}</div>
    </div></div></div></div>
</x-app-layout>