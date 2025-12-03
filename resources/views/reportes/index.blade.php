<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reporte Financiero General') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- 1. BARRA DE HERRAMIENTAS (FILTROS + BOTONES) --}}
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6 flex flex-col md:flex-row justify-between items-end gap-4">
                
                {{-- Formulario de Fechas --}}
                <form action="{{ route('reportes.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end w-full md:w-auto">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Fecha Inicio</label>
                        <input type="date" name="inicio" value="{{ $inicio }}" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Fecha Fin</label>
                        <input type="date" name="fin" value="{{ $fin }}" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-md shadow transition">
                        Filtrar
                    </button>
                </form>

                {{-- Botón para ir a Honorarios Médicos --}}
                <a href="{{ route('reportes.honorarios') }}" class="flex items-center gap-2 bg-white border border-indigo-200 text-indigo-700 font-bold py-2 px-4 rounded-md hover:bg-indigo-50 transition shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Ver Honorarios Médicos
                </a>
            </div>

            {{-- 2. TARJETAS DE RESUMEN (KPIs) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500 flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Ingresos Totales</p>
                        <p class="text-2xl font-extrabold text-gray-800">${{ number_format($totalIngresos, 2) }}</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-red-500 flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Gastos Operativos</p>
                        <p class="text-2xl font-extrabold text-gray-800">${{ number_format($totalGastos, 2) }}</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow border-l-4 {{ $balance >= 0 ? 'border-indigo-500' : 'border-orange-500' }} flex items-center">
                    <div class="p-3 rounded-full {{ $balance >= 0 ? 'bg-indigo-100 text-indigo-600' : 'bg-orange-100 text-orange-600' }} mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Ganancia Neta</p>
                        <p class="text-2xl font-extrabold {{ $balance >= 0 ? 'text-indigo-800' : 'text-orange-600' }}">
                            ${{ number_format($balance, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- 3. GRÁFICO DE BARRAS --}}
            <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                    Evolución de Ingresos Diarios
                </h3>
                <div class="relative h-80 w-full">
                    <canvas id="incomeChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    {{-- SCRIPTS PARA EL GRÁFICO (Chart.js) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('incomeChart').getContext('2d');
            
            // Datos que vienen del controlador
            const rawData = @json($ingresosPorDia);
            
            const labels = rawData.map(item => item.fecha);
            const values = rawData.map(item => item.total);

            new Chart(ctx, {
                type: 'bar', // Tipo de gráfico: barras verticales
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Ingresos ($)',
                        data: values,
                        backgroundColor: 'rgba(79, 70, 229, 0.7)', // Color Indigo con transparencia
                        borderColor: 'rgba(79, 70, 229, 1)',
                        borderWidth: 1,
                        borderRadius: 4, // Bordes redondeados en las barras
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f3f4f6' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return '$ ' + context.raw.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>