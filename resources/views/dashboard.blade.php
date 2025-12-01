<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Control Gerencial') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- SECCIÓN 1: TARJETAS KPI (Resumen rápido) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-gray-500">Total Pacientes</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $totalPacientes }}</div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="text-gray-500">Citas Pendientes Hoy</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $totalCitasHoy }}</div>
                    <a href="{{ route('citas.calendario') }}" class="text-xs text-indigo-600 hover:underline">Ver Agenda &rarr;</a>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="text-gray-500">Laboratorio (Por procesar)</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $ordenesPendientes }}</div>
                    <a href="{{ route('laboratorio.index') }}" class="text-xs text-yellow-600 hover:underline">Ir a Laboratorio &rarr;</a>
                </div>
            </div>

            {{-- SECCIÓN 2: GRÁFICOS --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">Ingresos Mensuales ($)</h3>
                    <canvas id="chartIngresos" height="150"></canvas>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">Top 5 Exámenes Más Solicitados</h3>
                    <canvas id="chartExamenes" height="150"></canvas>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 lg:col-span-2">
                    <h3 class="text-lg font-bold text-gray-700 mb-4 text-center">Distribución de Citas Médicas</h3>
                    <div class="h-64 flex justify-center">
                        <canvas id="chartCitas"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- SCRIPTS DE CHART.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // 1. CONFIGURACIÓN GRÁFICO DE INGRESOS
        const ctxIngresos = document.getElementById('chartIngresos');
        const dataIngresos = @json($ingresosData);
        
        new Chart(ctxIngresos, {
            type: 'bar',
            data: {
                labels: dataIngresos.map(item => item.mes), // Eje X: Meses
                datasets: [{
                    label: 'Ingresos ($)',
                    data: dataIngresos.map(item => item.total), // Eje Y: Dinero
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: { scales: { y: { beginAtZero: true } } }
        });

        // 2. CONFIGURACIÓN GRÁFICO TOP EXÁMENES
        const ctxExamenes = document.getElementById('chartExamenes');
        const dataExamenes = @json($topExamenes);

        new Chart(ctxExamenes, {
            type: 'bar',
            indexAxis: 'y', // Hace que las barras sean horizontales
            data: {
                labels: dataExamenes.map(item => item.nombre),
                datasets: [{
                    label: 'Cantidad Solicitada',
                    data: dataExamenes.map(item => item.total),
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            }
        });

        // 3. CONFIGURACIÓN GRÁFICO CITAS (DONA)
        const ctxCitas = document.getElementById('chartCitas');
        const dataCitas = @json($estadoCitas);

        new Chart(ctxCitas, {
            type: 'doughnut',
            data: {
                labels: dataCitas.map(item => item.estado),
                datasets: [{
                    data: dataCitas.map(item => item.total),
                    backgroundColor: [
                        '#fbbf24', // Pendiente (Amarillo)
                        '#10b981', // Completada (Verde)
                        '#ef4444', // Cancelada (Rojo)
                        '#6b7280'  // Otro (Gris)
                    ],
                }]
            },
            options: { maintainAspectRatio: false }
        });
    </script>
</x-app-layout>