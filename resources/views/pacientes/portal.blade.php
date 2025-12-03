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
                    <p class="text-blue-700">Aquí puedes consultar tus resultados. Si tienes dudas sobre requisitos o medicamentos, usa el chat de asistencia.</p>
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
                                    <th class="py-3 px-6 text-left">Exámenes</th>
                                    <th class="py-3 px-6 text-center">Estado</th>
                                    <th class="py-3 px-6 text-center">Acciones</th>
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
                                                <div class="flex item-center justify-center gap-2">
                                                    {{-- BOTÓN 1: DESCARGAR PDF --}}
                                                    <a href="{{ route('laboratorio.downloadResultado', $orden->id) }}" target="_blank" class="bg-gray-200 text-gray-700 py-2 px-3 rounded hover:bg-gray-300 transition flex items-center justify-center" title="Descargar PDF">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                        </svg>
                                                    </a>

                                                    {{-- BOTÓN 2: VER CONCLUSIÓN (YA GUARDADA) --}}
                                                    @if(!empty($orden->analisis_ia))
                                                        <button type="button" 
                                                                onclick="verConclusion(`{{ addslashes($orden->analisis_ia) }}`)" 
                                                                class="bg-purple-600 text-white py-2 px-3 rounded hover:bg-purple-700 transition flex items-center justify-center shadow-md transform hover:scale-105" 
                                                                title="Ver Interpretación">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                            Ver Conclusión
                                                        </button>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-gray-400 text-xs italic">Resultados pendientes</span>
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
                        <p class="text-lg">No tienes órdenes de exámenes registradas aún.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL SIMPLE (SIN AJAX) PARA LA CONCLUSIÓN GUARDADA --}}
    <div id="modal-ia" class="fixed inset-0 z-40 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="cerrarModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-purple-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Interpretación de Resultados</h3>
                            <div class="mt-2 bg-gray-50 p-3 rounded border">
                                <p id="texto-explicacion" class="text-sm text-gray-700 whitespace-pre-line leading-relaxed"></p>
                            </div>
                            <p class="mt-2 text-xs text-gray-400 italic">* Generado por Laboratorio. Consulte a su médico.</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="cerrarModal()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Entendido</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- WIDGET DE CHATBOT IA (NUEVO)               --}}
    {{-- ========================================== --}}
    
    <button onclick="toggleChat()" class="fixed bottom-6 right-6 bg-indigo-600 hover:bg-indigo-700 text-white p-4 rounded-full shadow-2xl flex items-center justify-center transition transform hover:scale-110 z-50">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
    </button>

    <div id="chat-window" class="fixed bottom-24 right-6 w-80 md:w-96 bg-white rounded-xl shadow-2xl border border-gray-200 hidden z-50 flex flex-col overflow-hidden" style="height: 500px;">
        <div class="bg-indigo-600 p-4 text-white flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="bg-white text-indigo-600 p-1 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm">Asistente SIGLC</h3>
                    <p class="text-xs text-indigo-200">En línea • IA</p>
                </div>
            </div>
            <button onclick="toggleChat()" class="text-indigo-200 hover:text-white font-bold text-xl">&times;</button>
        </div>

        <div id="chat-messages" class="flex-1 p-4 overflow-y-auto bg-gray-50 space-y-3">
            <div class="flex justify-start">
                <div class="bg-white border border-gray-200 text-gray-700 rounded-lg py-2 px-3 text-sm max-w-xs shadow-sm">
                    Hola, soy tu asistente virtual. 🤖<br>
                    Pregúntame sobre:<br>
                    - Requisitos para exámenes.<br>
                    - Para qué sirven tus medicamentos.<br>
                    - Dudas generales.
                </div>
            </div>
        </div>

        <div class="p-3 bg-white border-t border-gray-200">
            <form id="chat-form" class="flex gap-2" onsubmit="enviarMensaje(event)">
                <input type="text" id="chat-input" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Escribe tu duda..." autocomplete="off">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white p-2 rounded-md transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <script>
        // Funciones del Modal de Conclusión (Local)
        function verConclusion(texto) {
            const modal = document.getElementById('modal-ia');
            document.getElementById('texto-explicacion').innerText = texto;
            modal.classList.remove('hidden');
        }
        function cerrarModal() {
            document.getElementById('modal-ia').classList.add('hidden');
        }

        // Funciones del Chatbot (AJAX)
        function toggleChat() {
            const chat = document.getElementById('chat-window');
            chat.classList.toggle('hidden');
            if(!chat.classList.contains('hidden')) {
                document.getElementById('chat-input').focus();
            }
        }

        async function enviarMensaje(e) {
            e.preventDefault();
            const input = document.getElementById('chat-input');
            const mensaje = input.value.trim();
            const container = document.getElementById('chat-messages');

            if (!mensaje) return;

            // 1. Mensaje Usuario
            container.innerHTML += `<div class="flex justify-end"><div class="bg-indigo-600 text-white rounded-lg py-2 px-3 text-sm max-w-xs shadow-sm">${mensaje}</div></div>`;
            input.value = '';
            container.scrollTop = container.scrollHeight;

            // 2. Loading
            const loadingId = 'loading-' + Date.now();
            container.innerHTML += `<div id="${loadingId}" class="flex justify-start"><div class="bg-white border border-gray-200 text-gray-500 rounded-lg py-2 px-3 text-xs shadow-sm flex items-center">Escribiendo...</div></div>`;
            container.scrollTop = container.scrollHeight;

            try {
                // 3. Petición al Backend
                const response = await fetch("{{ route('pacientes.chat') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ mensaje: mensaje })
                });

                const data = await response.json();
                document.getElementById(loadingId).remove();
                
                // 4. Respuesta IA
                container.innerHTML += `<div class="flex justify-start"><div class="bg-white border border-gray-200 text-gray-700 rounded-lg py-2 px-3 text-sm max-w-xs shadow-sm">${data.respuesta}</div></div>`;

            } catch (error) {
                document.getElementById(loadingId).remove();
                container.innerHTML += `<div class="flex justify-start"><div class="bg-red-50 text-red-600 rounded-lg py-2 px-3 text-xs">Error de conexión.</div></div>`;
            }
            container.scrollTop = container.scrollHeight;
        }
    </script>
</x-app-layout>