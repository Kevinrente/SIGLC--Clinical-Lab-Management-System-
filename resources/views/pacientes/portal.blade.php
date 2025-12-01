<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Resultados de Laboratorio') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Tarjeta de Bienvenida --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-blue-50 border-l-4 border-blue-500">
                    <h3 class="text-lg font-bold text-blue-900">Hola, {{ Auth::user()->name }}</h3>
                    <p class="text-blue-700">Aquí puedes consultar el estado de tus exámenes y descargar los resultados finalizados.</p>
                </div>
            </div>

            {{-- Tabla de Resultados --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if($ordenes->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                                <tr>
                                    <th class="py-3 px-6 text-left">Fecha</th>
                                    <th class="py-3 px-6 text-left">Exámenes Solicitados</th>
                                    <th class="py-3 px-6 text-center">Estado</th>
                                    <th class="py-3 px-6 text-center">Resultado</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm font-light">
                                @foreach($ordenes as $orden)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                                        <td class="py-3 px-6 text-left whitespace-nowrap">
                                            <span class="font-bold">{{ $orden->created_at->format('d/m/Y') }}</span>
                                            <span class="block text-xs text-gray-400">Orden #{{ $orden->id }}</span>
                                        </td>
                                        <td class="py-3 px-6 text-left">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($orden->examenes as $examen)
                                                    <span class="bg-indigo-100 text-indigo-700 py-1 px-2 rounded-full text-xs">
                                                        {{ $examen->nombre }}
                                                    </span>
                                                @endforeach
                                            </div>
                                            <div class="mt-1 text-xs text-gray-500">
                                                Dr. {{ $orden->doctor->usuario->name ?? 'Laboratorio' }}
                                            </div>
                                        </td>
                                        <td class="py-3 px-6 text-center">
                                            @if($orden->estado == 'Finalizado')
                                                <span class="bg-green-200 text-green-700 py-1 px-3 rounded-full text-xs font-bold">Listo</span>
                                            @elseif($orden->estado == 'En Análisis')
                                                <span class="bg-blue-200 text-blue-700 py-1 px-3 rounded-full text-xs">Analizando</span>
                                            @else
                                                <span class="bg-yellow-200 text-yellow-700 py-1 px-3 rounded-full text-xs">Pendiente</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-6 text-center">
                                            @if($orden->estado == 'Finalizado')
                                                {{-- Reutilizamos la ruta de descarga que ya creamos --}}
                                                <a href="{{ route('laboratorio.downloadResultado', $orden->id) }}" target="_blank" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700 transition flex items-center justify-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                    </svg>
                                                    Descargar PDF
                                                </a>
                                            @else
                                                <span class="text-gray-400 text-xs italic">No disponible</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4">
                        {{ $ordenes->links() }}
                    </div>
                @else
                    <div class="p-10 text-center text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-lg">No tienes órdenes de exámenes registradas aún.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>