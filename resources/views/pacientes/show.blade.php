<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Historial Clínico: ' . $paciente->nombre . ' ' . $paciente->apellido) }}</h2>
    </x-slot>

    <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
            <h3 class="text-xl font-bold mb-4 border-b pb-2">Información del Paciente</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                <div><span class="font-semibold">ID:</span> {{ $paciente->identificacion }}</div>
                <div><span class="font-semibold">Nacimiento:</span> {{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->format('d/M/Y') }}</div>
                <div><span class="font-semibold">Sexo:</span> {{ $paciente->sexo }}</div>
                <div><span class="font-semibold">Teléfono:</span> {{ $paciente->telefono ?? 'N/A' }}</div>
            </div>
            <div class="mt-4 border-t pt-3 flex justify-between items-center">
                <span class="text-sm">Historial creado el: {{ $paciente->created_at->format('d/M/Y') }}</span>
                <a href="{{ route('pacientes.edit', $paciente) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Editar Datos</a>
            </div>
        </div>

        <div class="bg-gray-50 p-6 shadow-sm sm:rounded-lg border border-gray-200">
            <h3 class="text-xl font-bold mb-4 border-b pb-2">Historial Médico y Citas ({{ $paciente->consultas->count() + $paciente->citas->count() }})</h3>
            
            <p class="italic text-gray-500">
                La visualización detallada de citas y resultados de laboratorio se implementará después de los módulos de Citas y Laboratorio.
            </p>
            
            <h4 class="font-semibold mt-4">Consultas Registradas ({{ $paciente->consultas->count() }})</h4>
            <ul class="divide-y divide-gray-200 mt-2">
                @forelse($paciente->consultas as $consulta)
                    <li class="py-2 text-sm">
                        Consulta del **{{ $consulta->created_at->format('d/M/Y') }}** con Dr. {{ $consulta->doctor->apellido ?? 'N/A' }}. 
                        <span class="text-gray-500 ml-3">Diagnóstico: {{ Str::limit($consulta->diagnostico, 50) }}</span>
                    </li>
                @empty
                    <li class="py-2 text-gray-500 italic">No hay consultas médicas registradas.</li>
                @endforelse
            </ul>

            <h4 class="font-semibold mt-4">Citas Programadas/Anteriores ({{ $paciente->citas->count() }})</h4>
            <ul class="divide-y divide-gray-200 mt-2">
                @forelse($paciente->citas->sortByDesc('fecha_hora') as $cita)
                    <li class="py-2 text-sm">
                        {{ $cita->fecha_hora->format('d/M/Y H:i') }} - Dr. {{ $cita->doctor->apellido ?? 'N/A' }}. 
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @if($cita->estado == 'Completada') bg-green-100 text-green-800 @elseif($cita->estado == 'Cancelada') bg-red-100 text-red-800 @else bg-yellow-100 text-yellow-800 @endif">
                            {{ $cita->estado }}
                        </span>
                    </li>
                @empty
                    <li class="py-2 text-gray-500 italic">No hay citas registradas.</li>
                @endforelse
            </ul>
        </div>
        
    </div></div>
</x-app-layout>