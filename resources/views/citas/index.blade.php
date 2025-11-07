<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Agenda de Citas') }}</h2>
    </x-slot>

    <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8"><div class="bg-white overflow-hidden shadow-sm sm:rounded-lg"><div class="p-6 text-gray-900">
        @if (session('success'))<div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">{{ session('success') }}</div>@endif
        @if (session('error'))<div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">{{ session('error') }}</div>@endif

        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('citas.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Agendar Nueva Cita</a>
        </div>

        <form method="GET" action="{{ route('citas.index') }}" class="mb-6 p-4 border rounded-lg bg-gray-50 flex space-x-4 items-end">
            <div>
                <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha:</label>
                <input type="date" name="fecha" id="fecha" value="{{ $fecha }}" required class="mt-1 border rounded-md shadow-sm">
            </div>
            
            <div>
                <label for="doctor_id" class="block text-sm font-medium text-gray-700">Doctor:</label>
                <select name="doctor_id" id="doctor_id" class="mt-1 border rounded-md shadow-sm">
                    <option value="">-- Todos los Doctores --</option>
                    @foreach($doctors as $d)
                        <option value="{{ $d->id }}" {{ $doctorId == $d->id ? 'selected' : '' }}>
                            Dr. {{ $d->apellido }} ({{ $d->especialidad }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md">Filtrar Agenda</button>
        </form>

        <h4 class="text-lg font-semibold mb-3">Citas para el {{ \Carbon\Carbon::parse($fecha)->format('d/M/Y') }}</h4>

        <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200">
            <thead><tr>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paciente</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motivo</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr></thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($citas as $cita)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('h:i A') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cita->paciente->apellido }}, {{ $cita->paciente->nombre }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cita->doctor->apellido }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cita->motivo }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($cita->estado == 'Completada') bg-green-100 text-green-800
                                @elseif($cita->estado == 'Cancelada') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ $cita->estado }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            
                            {{-- 1. Botón de Editar/Ver Detalle --}}
                            <a href="{{ route('citas.edit', $cita) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                            
                            {{-- 2. Botón de Cancelar --}}
                            <form action="{{ route('citas.destroy', $cita) }}" method="POST" class="inline ml-3" onsubmit="return confirm('¿Cancelar esta cita?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Cancelar</button>
                            </form>
                            
                            {{-- 3. Acción de Flujo (Ver Detalle de Consulta/Orden) --}}
                            @if($cita->estado == 'Completada')
                                {{-- CRÍTICO: Redirigimos al SHOW para que se vean las acciones avanzadas (Generar Orden, Ver Nota) --}}
                                <a href="{{ route('citas.show', $cita) }}" class="text-teal-600 hover:text-teal-900 ml-3 font-bold">Gestionar Consulta</a>
                            @else
                                {{-- Si no está completada, solo mostramos el detalle (opcional) --}}
                                <a href="{{ route('citas.show', $cita) }}" class="text-gray-500 hover:text-gray-700 ml-3">Ver Detalle</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500 italic">No hay citas agendadas para esta fecha o doctor.</td></tr>
                @endforelse
            </tbody>
        </table></div>
        <div class="mt-4">{{ $citas->appends(request()->except('page'))->links() }}</div>
    </div></div></div></div>
</x-app-layout>