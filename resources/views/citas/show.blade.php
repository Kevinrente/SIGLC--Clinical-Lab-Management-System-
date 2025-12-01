<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle de la Cita Médica') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                
                <h3 class="text-xl font-bold mb-4 border-b pb-2 text-indigo-700">Información de la Cita #{{ $cita->id }}</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700">
                    <div>
                        <p class="mb-2"><strong>Paciente:</strong> 
                            <a href="{{ route('pacientes.show', $cita->paciente) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                {{-- CORRECCIÓN 1: Acceder a los campos nombre y apellido del paciente --}}
                                {{ $cita->paciente->nombre ?? 'N/A' }} {{ $cita->paciente->apellido ?? '' }}
                            </a>
                        </p>
                        <p class="mb-2"><strong>Doctor:</strong> 
                            {{-- CORRECCIÓN 2: Acceder directamente al nombre del Doctor (no del User, que podría ser diferente) --}}
                            {{ $cita->doctor->nombre ?? 'N/A' }} {{ $cita->doctor->apellido ?? '' }} ({{ $cita->doctor->especialidad ?? 'N/A' }})
                        </p>
                        <p class="mb-2"><strong>Motivo:</strong> {{ $cita->motivo }}</p>
                    </div>
                    <div>
                        <p class="mb-2"><strong>Fecha y Hora:</strong> 
                            <span class="font-semibold text-lg">{{ $cita->fecha_hora->format('d/m/Y H:i A') }}</span>
                        </p>
                        <p class="mb-2"><strong>Estado Actual:</strong> 
                            <span class="px-3 py-1 text-sm font-semibold rounded-full 
                                @if($cita->estado == 'Completada') bg-green-100 text-green-800
                                @elseif($cita->estado == 'Confirmada') bg-blue-100 text-blue-800
                                @elseif($cita->estado == 'Cancelada') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ $cita->estado }}
                            </span>
                        </p>
                        <p class="mb-2"><strong>Fecha de Registro:</strong> {{ $cita->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>

                <hr class="my-6">

                {{-- ========================================================= --}}
                {{-- SECCIÓN DE ACCIONES DEL DOCTOR / RECEPCIÓN --}}
                {{-- ========================================================= --}}
                <h3 class="text-xl font-bold mb-4 text-orange-600">Acciones y Gestión</h3>

                <div class="flex flex-wrap gap-4 items-center">
                    
                    {{-- 1. Botón para Editar Cita (Recepción/Admin) --}}
                    @can('gestion.citas')
                        <a href="{{ route('citas.edit', $cita) }}" 
                           class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition duration-150">
                            📝 Editar Cita/Reagendar
                        </a>
                    @endcan

                    {{-- 2. Formulario para Cambiar Estado (Principalmente a Completada para el Doctor) --}}
                    @if (Auth::user()->hasRole('Doctor') && $cita->estado != 'Completada')
                        <form method="POST" action="{{ route('citas.update', $cita) }}" class="inline-block">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="estado" value="Completada">
                            {{-- Envío de campos requeridos por el UpdateCitaRequest --}}
                            <input type="hidden" name="paciente_id" value="{{ $cita->paciente_id }}">
                            <input type="hidden" name="doctor_id" value="{{ $cita->doctor_id }}">
                            <input type="hidden" name="motivo" value="{{ $cita->motivo }}">
                            <input type="hidden" name="fecha_hora" value="{{ $cita->fecha_hora->format('d/m/Y, h:i A') }}">
                            
                            <button type="submit" 
                                    onclick="return confirm('¿Está seguro de que desea marcar esta cita como Completada?')"
                                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-150">
                                ✔️ Marcar como Completada
                            </button>
                        </form>
                    @endif

                    {{-- 3. Botón para Generar Orden de Examen (Flujo implementado) --}}
                    @if (Auth::user()->hasRole('Doctor') && $cita->estado == 'Completada')
                        <a href="{{ route('ordenes.create', $cita) }}" 
                           class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded transition duration-150">
                            🔬 Generar Orden de Examen
                        </a>
                    @endif
                    
                    {{-- 4. Botón para Ver/Generar Consulta/Nota Médica --}}
                    {{-- ASUMO que el enlace de 'Ver Consulta' debe llevar a crear o ver la nota médica --}}
                    @if ($cita->estado == 'Completada' && $cita->consulta)
                         <a href="{{ route('consultas.show', $cita->consulta) }}" class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-4 rounded transition duration-150">
                            ✍️ Ver Notas Médicas
                         </a>
                    @elseif ($cita->estado == 'Completada' && !$cita->consulta && Auth::user()->hasRole('Doctor'))
                         <a href="{{ route('consultas.createFromCita', $cita) }}" class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-4 rounded transition duration-150">
                            ✍️ Registrar Consulta
                         </a>
                    @endif
                </div>
            </div>
            
            {{-- ========================================================= --}}
            {{-- SECCIÓN DE ÓRDENES ASOCIADAS --}}
            {{-- ========================================================= --}}
            <div class="mt-8 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-xl font-bold mb-4 border-b pb-2 text-indigo-700">Órdenes de Examen Asociadas</h3>

                @if ($cita->ordenesExamen->isNotEmpty())
                    <ul class="space-y-4">
                        @foreach ($cita->ordenesExamen as $orden)
                            <li class="p-4 border rounded shadow-sm flex justify-between items-center">
                                <div>
                                    <p class="font-semibold">Orden #{{ $orden->id }} - {{ $orden->created_at->format('d/m/Y') }}</p>
                                    
                                    {{-- NUEVO CÓDIGO: Mostrar lista de exámenes desde la relación --}}
                                    <p class="text-sm text-gray-600 mt-1">
                                        <strong>Estudios:</strong> 
                                        @if($orden->examenes->count() > 0)
                                            {{ $orden->examenes->pluck('nombre')->join(', ') }}
                                        @else
                                            <span class="italic text-gray-400">Sin exámenes especificados</span>
                                        @endif
                                    </p>
                                </div>
                                
                                <div class="flex items-center space-x-3">
                                    {{-- ... (etiqueta de estado y botón PDF igual que antes) ... --}}
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full 
                                        @if($orden->estado == 'Finalizado') bg-green-100 text-green-800
                                        @elseif($orden->estado == 'Muestra Tomada') bg-blue-100 text-blue-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ $orden->estado }}
                                    </span>
                                    {{-- ... --}}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500 italic">No hay órdenes de examen asociadas a esta cita.</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>