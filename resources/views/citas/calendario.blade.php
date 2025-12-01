<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reservar Nueva Cita') }}
        </h2>
    </x-slot>

    <style>
        .fc-event { cursor: pointer; }
        .fc-toolbar-title { font-size: 1.25rem !important; }
        /* Ocultar horas de madrugada/noche */
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-6">
                
                {{-- Instrucciones --}}
                <div class="mb-4 p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-700">
                    <p><strong>Instrucciones:</strong> Haga clic en cualquier espacio en blanco para agendar una cita en ese horario.</p>
                </div>

                {{-- Calendario --}}
                <div id='calendar'></div>

            </div>

            <div class="bg-white shadow-xl sm:rounded-lg p-6">
                
                {{-- AGREGA ESTO AQUÍ ARRIBA --}}
                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <strong class="font-bold">Error de validación:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{-- FIN DEL BLOQUE DE ALERTAS --}}

                {{-- Instrucciones --}}
                <div class="mb-4 p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-700">
                    <p><strong>Instrucciones:</strong> Haga clic en cualquier espacio en blanco...</p>
                </div>
        </div>
    </div>

    {{-- MODAL DE CONFIRMACIÓN DE CITA --}}
    <div id="bookingModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('citas.store') }}" method="POST" id="bookingForm">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Confirmar Reserva de Cita</h3>
                        
                        <div class="mt-4 space-y-4">
                            {{-- Fecha y Hora (Oculto o Solo Lectura) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Fecha y Hora Seleccionada</label>
                                <input type="text" id="fechaVisual" class="mt-1 block w-full bg-gray-100 border-gray-300 rounded-md shadow-sm" readonly>
                                <input type="hidden" name="fecha_hora" id="fechaInput">
                            </div>

                            {{-- Selección de Doctor (Por ahora listamos todos, luego filtramos) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Doctor / Especialidad</label>
                                <select name="doctor_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                    @foreach(\App\Models\Doctor::with('usuario')->get() as $doctor)
                                        <option value="{{ $doctor->id }}">Dr. {{ $doctor->usuario->name }} - {{ $doctor->especialidad }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Motivo (Opcional) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Motivo de Consulta</label>
                                <textarea name="motivo" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Ej: Dolor de cabeza frecuente..."></textarea>
                            </div>

                            {{-- Campos Ocultos --}}
                            @if(Auth::user()->paciente)
                                <input type="hidden" name="paciente_id" value="{{ Auth::user()->paciente->id }}">
                            @endif
                            {{-- ... inputs anteriores (motivo, etc) ... --}}

                            {{-- SELECCIÓN DE ESTADO (Solo para Staff) --}}
                            @if(Auth::user()->doctor || Auth::user()->can('gestion.administracion'))
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Estado Inicial</label>
                                    <select name="estado" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="Pendiente">Pendiente (Por confirmar)</option>
                                        <option value="Confirmada" selected>Confirmada</option>
                                    </select>
                                </div>
                            @else
                                {{-- Los pacientes siempre crean citas Pendientes --}}
                                <input type="hidden" name="estado" value="Pendiente">
                            @endif

                            {{-- Campos Ocultos --}}
                            @if(Auth::user()->paciente)
                                <input type="hidden" name="paciente_id" value="{{ Auth::user()->paciente->id }}">
                            @endif
                            
                            {{-- Si es doctor/admin, necesitamos seleccionar el paciente --}}
                            @if(!Auth::user()->paciente)
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700">Paciente</label>
                                    <select name="paciente_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                        @foreach(\App\Models\Paciente::orderBy('nombre')->get() as $paciente)
                                            <option value="{{ $paciente->id }}">{{ $paciente->nombre }} {{ $paciente->apellido }} ({{ $paciente->cedula }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Confirmar Reserva
                        </button>
                        <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    
    <script>
        // Funciones del Modal
        function openModal(dateStr) {
            // 1. Mostrar fecha legible al usuario
            let fechaObj = new Date(dateStr);
            document.getElementById('fechaVisual').value = fechaObj.toLocaleString();
            
            // 2. CORRECCIÓN IMPORTANTE: Quitar la "Z" o zona horaria de FullCalendar
            // FullCalendar devuelve algo como: "2025-12-02T10:00:00-05:00" o con Z.
            // Queremos enviar solo: "2025-12-02 10:00:00" para que Laravel lo procese tal cual lo ves.
            
            // Cortamos la cadena para quedarnos con los primeros 19 caracteres (YYYY-MM-DDTHH:mm:ss)
            let cleanDate = dateStr.substring(0, 19).replace('T', ' ');
            
            document.getElementById('fechaInput').value = cleanDate; 
            
            document.getElementById('bookingModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('bookingModal').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                locale: 'es',
                hiddenDays: [0], // Ocultar domingos (0) si quieres
                slotMinTime: '08:00:00', // Horario clínica
                slotMaxTime: '18:00:00',
                allDaySlot: false,
                height: 'auto',
                events: '{{ route("citas.events") }}',

                // AL HACER CLIC EN UN HUECO VACÍO
                dateClick: function(info) {
                    openModal(info.dateStr);
                },

                // AL HACER CLIC EN UNA CITA EXISTENTE
                eventClick: function(info) {
                    // Si es background (ocupado), no hace nada
                    if (info.event.display === 'background') return;
                    
                    alert('Esta cita ya está reservada.');
                }
            });
            calendar.render();
        });

        // EVITAR DOBLE CLIC EN EL FORMULARIO
        const bookingForm = document.getElementById('bookingForm');
        
        if(bookingForm){
            bookingForm.addEventListener('submit', function(e) {
                // Buscamos el botón de guardar
                const btnGuardar = this.querySelector('button[type="submit"]');
                
                // Lo deshabilitamos y cambiamos el texto
                if(btnGuardar) {
                    btnGuardar.disabled = true;
                    btnGuardar.innerText = 'Reservando...';
                    btnGuardar.classList.add('opacity-50', 'cursor-not-allowed');
                }
            });
        }


    </script>
</x-app-layout>