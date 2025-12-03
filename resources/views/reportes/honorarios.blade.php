<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reporte de Honorarios Médicos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- FILTROS Y NAVEGACIÓN --}}
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6 flex flex-col md:flex-row justify-between items-end gap-4">
                <form action="{{ route('reportes.honorarios') }}" method="GET" class="flex gap-4 items-end w-full md:w-auto">
                    <div>
                        <label class="block text-sm font-bold text-gray-700">Desde</label>
                        <input type="date" name="inicio" value="{{ $inicio }}" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700">Hasta</label>
                        <input type="date" name="fin" value="{{ $fin }}" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md font-bold hover:bg-indigo-700 transition">
                        Filtrar
                    </button>
                </form>
                
                <a href="{{ route('reportes.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver al Dashboard Financiero
                </a>
            </div>

            {{-- TABLA DE RESULTADOS --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-wider">Doctor</th>
                            <th class="px-6 py-4 text-center font-bold uppercase tracking-wider">Actividad</th>
                            <th class="px-6 py-4 text-right font-bold uppercase tracking-wider">Comisión Consultas</th>
                            <th class="px-6 py-4 text-right font-bold uppercase tracking-wider">
                                Comisión Lab 
                                <span class="block font-normal text-xs text-gray-400 normal-case">(Configuración)</span>
                            </th>
                            <th class="px-6 py-4 text-right bg-green-700 font-bold uppercase tracking-wider">TOTAL A PAGAR</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($reporte as $row)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900 text-base">{{ $row['doctor'] }}</div>
                                    <div class="text-xs text-gray-500">{{ $row['especialidad'] }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mb-1">
                                        {{ $row['consultas_count'] }} Consultas
                                    </span>
                                    <br>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $row['ordenes_count'] }} Órdenes
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-gray-700 font-medium">
                                    ${{ number_format($row['pago_consultas'], 2) }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="text-indigo-700 font-bold">${{ number_format($row['pago_laboratorio'], 2) }}</div>
                                    {{-- CORRECCIÓN: Usamos 'config_lab' que es como lo mandamos en el controlador --}}
                                    <div class="text-xs text-gray-400">({{ $row['config_lab'] }})</div>
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-bold text-green-700 text-xl bg-green-50 border-l border-green-100">
                                    ${{ number_format($row['total_a_pagar'], 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">
                                    No se encontraron registros para el rango de fechas seleccionado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>