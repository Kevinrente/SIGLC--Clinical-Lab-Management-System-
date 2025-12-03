<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Catálogo de Exámenes') }}
            </h2>
            <a href="{{ route('examenes.create') }}" class="w-full md:w-auto text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-sm shadow transition">
                + Nuevo Examen
            </a>
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Mensajes de Alerta --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 shadow-sm" role="alert">
                    <p class="font-bold">Éxito</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 shadow-sm" role="alert">
                    <p class="font-bold">Error</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    {{-- CORRECCIÓN RESPONSIVE: Contenedor con scroll horizontal --}}
                    <div class="overflow-x-auto relative">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                                <tr>
                                    <th scope="col" class="px-6 py-3 whitespace-nowrap">Nombre</th>
                                    <th scope="col" class="px-6 py-3 whitespace-nowrap">Categoría</th>
                                    <th scope="col" class="px-6 py-3 whitespace-nowrap">Ref / Unidades</th>
                                    <th scope="col" class="px-6 py-3 text-right whitespace-nowrap">Precio</th>
                                    <th scope="col" class="px-6 py-3 text-right whitespace-nowrap">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($examenes as $examen)
                                    <tr class="bg-white hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $examen->nombre }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                                {{ $examen->categoria }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <span class="text-gray-900">{{ $examen->valor_referencia ?? '-' }}</span>
                                                <span class="text-xs text-gray-500">{{ $examen->unidades }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold text-gray-900 whitespace-nowrap">
                                            ${{ number_format($examen->precio, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-right whitespace-nowrap space-x-2">
                                            <a href="{{ route('examenes.edit', $examen->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Editar</a>
                                            
                                            <form action="{{ route('examenes.destroy', $examen->id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que deseas eliminar este examen? Esta acción no se puede deshacer.');">
                                                @csrf 
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium ml-2">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación Responsive --}}
                    <div class="mt-4">
                        {{ $examenes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>