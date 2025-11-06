<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Gestión de Pacientes') }}</h2>
    </x-slot>

    <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8"><div class="bg-white overflow-hidden shadow-sm sm:rounded-lg"><div class="p-6 text-gray-900">
        @if (session('success'))<div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">{{ session('success') }}</div>@endif
        @if (session('error'))<div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">{{ session('error') }}</div>@endif

        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('pacientes.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Registrar Nuevo Paciente</a>
        </div>

        <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200">
            <thead><tr>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Identificación</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre Completo</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr></thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($pacientes as $paciente)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $paciente->identificacion }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <a href="{{ route('pacientes.show', $paciente) }}" class="text-indigo-600 hover:text-indigo-900">{{ $paciente->nombre }} {{ $paciente->apellido }}</a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $paciente->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('pacientes.edit', $paciente) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                            <form action="{{ route('pacientes.destroy', $paciente) }}" method="POST" class="inline ml-3" onsubmit="return confirm('¿Eliminar paciente? Esto borrará citas y consultas asociadas.');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table></div>
        <div class="mt-4">{{ $pacientes->links() }}</div>
    </div></div></div></div>
</x-app-layout>