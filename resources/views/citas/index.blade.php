<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Listado de Citas Médicas') }}
            </h2>
            {{-- Botón para alternar a vista Calendario --}}
            <a href="{{ route('citas.calendario') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Ver en Calendario
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha y Hora</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paciente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doctor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Motivo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($citas as $cita)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700">
                                            {{ $cita->fecha_hora->format('d/m/Y') }} <br>
                                            <span class="text-indigo-600">{{ $cita->fecha_hora->format('H:i A') }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $cita->paciente->nombre }} {{ $cita->paciente->apellido }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $cita->doctor->usuario->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ Str::limit($cita->motivo, 30) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($cita->estado == 'Pendiente') bg-yellow-100 text-yellow-800 
                                                @elseif($cita->estado == 'Confirmada') bg-blue-100 text-blue-800
                                                @elseif($cita->estado == 'Completada') bg-green-100 text-green-800 
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ $cita->estado }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            
                                            {{-- CASO 1: Cita Abierta (Pendiente o Confirmada) --}}
                                            @if(in_array($cita->estado, ['Pendiente', 'Confirmada']))
                                                
                                                @if(Auth::user()->doctor || Auth::user()->hasRole('admin'))
                                                    {{-- Botón Atender --}}
                                                    <a href="{{ route('consultas.createFromCita', $cita->id) }}" class="text-white bg-green-600 hover:bg-green-700 py-1 px-3 rounded text-xs mr-2 transition">
                                                        Atender
                                                    </a>
                                                @endif
                                                
                                                {{-- Botón Cancelar --}}
                                                <form action="{{ route('citas.destroy', $cita->id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que desea cancelar esta cita?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 text-xs underline">Cancelar</button>
                                                </form>

                                            {{-- CASO 2: Cita Completada --}}
                                            @elseif($cita->estado == 'Completada')
                                                @if($cita->consulta)
                                                    {{-- Si ya hay consulta registrada, mostramos link para verla --}}
                                                    <a href="{{ route('consultas.show', $cita->consulta->id) }}" class="text-indigo-600 hover:text-indigo-900 flex items-center gap-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        Ver Consulta
                                                    </a>
                                                @else
                                                    <span class="text-gray-400 text-xs italic">Finalizada</span>
                                                @endif

                                            {{-- CASO 3: Cancelada --}}
                                            @elseif($cita->estado == 'Cancelada')
                                                <span class="text-red-400 text-xs italic">Cancelada</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 bg-gray-50">
                                            No hay citas programadas.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $citas->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>