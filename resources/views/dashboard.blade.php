<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Operacional del SIGLC') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Bienvenida Personalizada y Fecha -->
            <div class="mb-8 p-6 bg-white border border-gray-100 rounded-lg shadow-xl flex justify-between items-center">
                <div>
                    <h3 class="text-3xl font-bold text-gray-800">
                        Hola, {{ Auth::user()->name }}
                    </h3>
                    <p class="text-gray-500 mt-1">Tu rol: {{ Auth::user()->roles->pluck('name')->join(', ') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold text-indigo-600">{{ now()->format('d F Y') }}</p>
                    <p class="text-sm text-gray-400">{{ now()->format('H:i A') }}</p>
                </div>
            </div>

            <!-- SECCIÓN 1: INDICADORES CLÍNICOS Y ADMINISTRATIVOS -->
            <h3 class="text-xl font-bold text-gray-700 mb-4">Indicadores de Rendimiento (KPIs)</h3>
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                
                <!-- Tarjeta 1: Citas Pendientes HOY -->
                <div class="bg-white border-b-4 border-red-500 p-5 rounded-lg shadow-lg hover:shadow-2xl transition duration-150">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-red-600">Citas Pendientes Hoy</p>
                        <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-4 4V3m-4 4h8M4 11h16M4 15h16M4 19h16"/></svg>
                    </div>
                    <p class="text-5xl font-extrabold text-gray-900 mt-2">{{ $totalCitasHoy ?? 0 }}</p>
                    <a href="{{ route('citas.index') }}" class="text-xs text-red-700 hover:text-red-900 mt-2 block font-semibold">Ver Agenda Completa &rarr;</a>
                </div>

                <!-- Tarjeta 2: Total de Pacientes Registrados -->
                <div class="bg-white border-b-4 border-blue-500 p-5 rounded-lg shadow-lg hover:shadow-2xl transition duration-150">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-blue-600">Pacientes Registrados</p>
                        <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15M12 4.5v15M19.5 12h-15"/></svg>
                    </div>
                    <p class="text-5xl font-extrabold text-gray-900 mt-2">{{ $totalPacientes ?? 0 }}</p>
                    <a href="{{ route('pacientes.index') }}" class="text-xs text-blue-700 hover:text-blue-900 mt-2 block font-semibold">Gestionar Pacientes &rarr;</a>
                </div>
                
                <!-- Tarjeta 3: Órdenes de Laboratorio Pendientes -->
                <div class="bg-white border-b-4 border-yellow-500 p-5 rounded-lg shadow-lg hover:shadow-2xl transition duration-150">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-yellow-600">Órdenes Lab Pendientes</p>
                        <svg class="h-6 w-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m-7.5-6h7.5M12 21h4a2 2 0 002-2V7a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-5xl font-extrabold text-gray-900 mt-2">{{ $ordenesPendientesLab ?? 0 }}</p>
                    <a href="{{ route('laboratorio.index') }}" class="text-xs text-yellow-700 hover:text-yellow-900 mt-2 block font-semibold">Módulo Laboratorio &rarr;</a>
                </div>
                
                <!-- Tarjeta 4: Acciones de Gestión Rápida -->
                <div class="bg-white border-b-4 border-green-500 p-5 rounded-lg shadow-lg hover:shadow-2xl transition duration-150">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-green-600">Acciones Rápidas</p>
                        <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="mt-4 flex flex-col space-y-2">
                        @can('gestion.citas')
                        <a href="{{ route('citas.create') }}" class="w-full text-center px-3 py-1 text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition">Agendar Cita</a>
                        @endcan
                        @can('gestion.pacientes')
                        <a href="{{ route('pacientes.create') }}" class="w-full text-center px-3 py-1 text-sm font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 transition">Registrar Paciente</a>
                        @endcan
                    </div>
                </div>

            </div>
            
            <!-- SECCIÓN 2: ALERTAS CLÍNICAS Y LOGÍSTICAS -->
            <div class="mt-10">
                <h3 class="text-xl font-bold text-gray-700 mb-4 border-b pb-2">Alertas y Notificaciones</h3>
                
                <!-- Alerta de Doctor Logueado -->
                @if(Auth::user()->hasRole('Doctor'))
                <div class="p-4 bg-teal-100 border-l-4 border-teal-500 text-teal-800 rounded-lg shadow-sm mb-4">
                    <p class="font-medium">✅ Agenda Lista: Estás asignado como Dr. {{ Auth::user()->doctor->apellido }}. Revisa tus citas pendientes hoy.</p>
                </div>
                @endif
                
                <!-- Alerta de Ejemplo (Órdenes Antiguas) -->
                <div class="p-4 bg-red-100 border-l-4 border-red-500 text-red-800 rounded-lg shadow-sm">
                    <p class="font-medium">🔥 CRÍTICO: 2 Órdenes de Examen llevan más de 7 días sin resultado. Contactar Laboratorio Central.</p>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>