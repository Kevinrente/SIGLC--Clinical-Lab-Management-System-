<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Gestión de Doctores Especialistas') }}</h2>
    </x-slot>

    <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8"><div class="bg-white overflow-hidden shadow-sm sm:rounded-lg"><div class="p-6 text-gray-900">
        @if (session('success'))<div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">{{ session('success') }}</div>@endif
        @if (session('error'))<div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">{{ session('error') }}</div>@endif

        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('doctors.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Registrar Nuevo Doctor</a>
        </div>

        <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200">
            <thead><tr>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre Completo</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Especialidad</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Licencia Médica</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cuenta de Usuario</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr></thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($doctors as $doctor)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $doctor->nombre }} {{ $doctor->apellido }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $doctor->especialidad }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $doctor->licencia_medica }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($doctor->usuario)
                                <span class="text-green-600">{{ $doctor->usuario->email }}</span>
                            @else
                                <span class="text-red-600">Sin Cuenta</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('doctors.edit', $doctor) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                            <form action="{{ route('doctors.destroy', $doctor) }}" method="POST" class="inline ml-3" onsubmit="return confirm('¿Está seguro de eliminar al doctor y su cuenta?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">No se encontraron doctores registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table></div>
        <div class="mt-4">{{ $doctors->links() }}</div>
    </div></div></div></div>
</x-app-layout>