<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Historia Clínica y Receta') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden">
                
                {{-- ENCABEZADO PACIENTE --}}
                <div class="bg-indigo-50 p-6 border-b border-indigo-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-indigo-900">{{ $cita->paciente->nombre }} {{ $cita->paciente->apellido }}</h3>
                        <p class="text-sm text-indigo-600">
                            {{ \Carbon\Carbon::parse($cita->paciente->fecha_nacimiento)->age }} años • {{ $cita->paciente->sexo }} • Cita: {{ $cita->fecha_hora->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="bg-white border border-indigo-200 text-indigo-800 text-xs font-bold px-3 py-1 rounded-full">
                            En Consulta
                        </span>
                    </div>
                </div>

                {{-- CAJA DE ERRORES GENERALES --}}
                @if ($errors->any())
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <strong class="font-bold">¡Hay problemas con el formulario!</strong>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- ERRORES DE BASE DE DATOS --}}
                @if (session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <strong class="font-bold">Error del Sistema:</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
                
                <form action="{{ route('consultas.store') }}" method="POST" id="consultaForm">
                    @csrf
                    <input type="hidden" name="cita_id" value="{{ $cita->id }}">
                    <input type="hidden" name="paciente_id" value="{{ $cita->paciente_id }}">
                    
                    <div class="p-6 space-y-8">
                        
                        
                        {{-- 1. ANAMNESIS Y EXPLORACIÓN --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Columna Izquierda: Motivo + IA --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Motivo de Consulta / Síntomas</label>
                                
                                <button type="button" id="btn-dictado" onclick="toggleGrabacion()" class="text-xs flex items-center gap-1 px-2 py-1 rounded border border-gray-300 bg-white hover:bg-gray-50 text-gray-600 transition">
                                    <span id="icono-mic">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                                        </svg>
                                    </span>
                                    <span id="texto-mic">Dictar</span>
                                </button>

                                <div class="relative">
                                    {{-- CORREGIDO: Se agregó el < al inicio --}}
                                    <textarea 
                                        id="motivo_consulta" 
                                        name="motivo_consulta" 
                                        rows="4" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50" 
                                        placeholder="Escriba o dicte los síntomas..."
                                        required>{{ old('motivo_consulta', $cita->motivo) }}</textarea>
                                        
                                    <p id="estado-grabacion" class="text-xs text-red-600 mt-1 hidden font-bold animate-pulse">● Grabando... (Habla ahora)</p>
                                </div>
                                    
                                    {{-- Botón Flotante o Debajo (Lo pondré debajo para no tapar texto) --}}
                                </div>

                                <div class="mt-2 flex justify-end">
                                    <button type="button" onclick="consultarIA()" id="btn-ia" class="flex items-center text-xs bg-purple-600 hover:bg-purple-700 text-white px-3 py-1.5 rounded-md shadow transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13 7H7v6h6V7z" />
                                            <path fill-rule="evenodd" d="M7 2a1 1 0 012 0v1h2V2a1 1 0 112 0v1h2a2 2 0 012 2v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v2a2 2 0 01-2 2h-2v1a1 1 0 11-2 0v-1H9v1a1 1 0 11-2 0v-1H5a2 2 0 01-2-2v-2H2a1 1 0 110-2h1V9H2a1 1 0 010-2h1V5a2 2 0 012-2h2V2zM5 5h10v10H5V5z" clip-rule="evenodd" />
                                        </svg>
                                        Analizar con IA
                                    </button>
                                </div>

                                {{-- Área de Respuesta de la IA (Oculta por defecto) --}}
                                <div id="resultado_ia" class="hidden mt-3 bg-purple-50 border-l-4 border-purple-500 p-4 rounded shadow-sm">
                                    <div class="flex justify-between items-start">
                                        <h4 class="text-xs font-bold text-purple-700 uppercase mb-1">Sugerencia Clínica (Groq):</h4>
                                        <button type="button" onclick="document.getElementById('resultado_ia').classList.add('hidden')" class="text-purple-400 hover:text-purple-600">
                                            &times;
                                        </button>
                                    </div>
                                    <p id="texto_ia" class="text-sm text-gray-800 whitespace-pre-line leading-relaxed"></p>
                                    <div class="mt-2 text-right">
                                        <button type="button" onclick="copiarAlDiagnostico()" class="text-xs text-purple-600 underline hover:text-purple-800">
                                            Copiar sugerencia a Diagnóstico Presuntivo
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Columna Derecha: Exploración Física --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Exploración Física (Signos Vitales)</label>
                                <textarea name="exploracion_fisica" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-full" placeholder="PA: 120/80, FC: 80, Temp: 36.5...">{{ old('exploracion_fisica') }}</textarea>
                            </div>
                        </div>

                        <hr>

                        {{-- 2. DIAGNÓSTICOS --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Diagnóstico Presuntivo</label>
                                <input type="text" name="diagnostico_presuntivo" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Lo que sospecha..." value="{{ old('diagnostico_presuntivo') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Diagnóstico Confirmado (CIE-10)</label>
                                <input type="text" name="diagnostico_confirmado" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Diagnóstico definitivo" value="{{ old('diagnostico_confirmado') }}">
                            </div>
                        </div>

                        <hr>

                        {{-- 3. RECETA MÉDICA DINÁMICA --}}
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="text-lg font-bold text-gray-800 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Receta Médica / Plan Terapéutico
                                </h4>
                                <button type="button" onclick="agregarMedicamento()" class="text-sm bg-green-100 text-green-700 px-3 py-1 rounded hover:bg-green-200 font-bold border border-green-300 transition flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Agregar Medicamento
                                </button>
                            </div>

                            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                <table class="w-full" id="tablaReceta">
                                    <thead>
                                        <tr class="text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                            <th class="pb-2 w-1/3">Medicamento / Concentración</th>
                                            <th class="pb-2 w-1/2">Indicaciones (Dosis, Frecuencia, Duración)</th>
                                            <th class="pb-2 text-center w-10"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="listaMedicamentos">
                                        {{-- FILA INICIAL OBLIGATORIA --}}
                                        <tr>
                                            <td class="pr-2 pb-2">
                                                <input type="text" name="receta[0][medicamento]" placeholder="Ej: Paracetamol 500mg" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            </td>
                                            <td class="pr-2 pb-2">
                                                <input type="text" name="receta[0][indicacion]" placeholder="Ej: Tomar 1 tableta cada 8 horas por 3 días" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            </td>
                                            <td class="pb-2 text-center">
                                                {{-- El primero no se puede borrar --}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <p class="text-xs text-gray-400 mt-2 italic">* Presione "Agregar Medicamento" para sumar líneas a la receta.</p>
                            </div>
                        </div>

                    </div>

                    {{-- BOTONES DE ACCIÓN --}}
                    <div class="bg-gray-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-gray-100">
                        <a href="{{ route('citas.index') }}" class="text-gray-600 hover:text-gray-800 text-sm font-medium">Cancelar</a>
                        
                        <button type="submit" name="action" value="finish" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-100 font-bold py-2 px-4 rounded shadow-sm transition">
                            Finalizar Consulta
                        </button>

                        <button type="submit" name="action" value="order" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded shadow-lg flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Guardar y Pedir Exámenes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPT COMPLETO --}}
    <script>
        // ==========================================
        // 1. LÓGICA DE RECETA MÉDICA
        // ==========================================
        let contadorFilas = 1;

        function agregarMedicamento() {
            const tbody = document.getElementById('listaMedicamentos');
            const fila = document.createElement('tr');
            fila.innerHTML = `
                <td class="pr-2 pb-2">
                    <input type="text" name="receta[${contadorFilas}][medicamento]" placeholder="Medicamento..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                </td>
                <td class="pr-2 pb-2">
                    <input type="text" name="receta[${contadorFilas}][indicacion]" placeholder="Indicaciones..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                </td>
                <td class="pb-2 text-center">
                    <button type="button" onclick="eliminarFila(this)" class="text-red-500 hover:text-red-700 p-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                    </button>
                </td>
            `;
            tbody.appendChild(fila);
            contadorFilas++;
        }

        function eliminarFila(boton) {
            boton.closest('tr').remove();
        }

        // ==========================================
        // 2. LÓGICA DE TEXTO IA (Groq)
        // ==========================================
        async function consultarIA() {
            const sintomas = document.getElementById('motivo_consulta').value;
            const btn = document.getElementById('btn-ia');
            const resultadoDiv = document.getElementById('resultado_ia');
            const textoP = document.getElementById('texto_ia');

            if (!sintomas || sintomas.length < 5) {
                alert('Por favor, ingresa los síntomas primero.');
                document.getElementById('motivo_consulta').focus();
                return;
            }

            btn.disabled = true;
            btn.innerHTML = `<svg class="animate-spin h-4 w-4 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Pensando...`;
            
            try {
                const response = await fetch("{{ route('consultas.ia') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ sintomas: sintomas })
                });

                if (!response.ok) throw new Error('Error en la red');
                const data = await response.json();
                
                textoP.innerText = data.respuesta;
                resultadoDiv.classList.remove('hidden');

            } catch (error) {
                console.error(error);
                alert('Error al consultar la IA.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor"><path d="M13 7H7v6h6V7z" /><path fill-rule="evenodd" d="M7 2a1 1 0 012 0v1h2V2a1 1 0 112 0v1h2a2 2 0 012 2v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v2a2 2 0 01-2 2h-2v1a1 1 0 11-2 0v-1H9v1a1 1 0 11-2 0v-1H5a2 2 0 01-2-2v-2H2a1 1 0 110-2h1V9H2a1 1 0 010-2h1V5a2 2 0 012-2h2V2zM5 5h10v10H5V5z" clip-rule="evenodd" /></svg> Analizar con IA`;
            }
        }

        function copiarAlDiagnostico() {
            const textoIA = document.getElementById('texto_ia').innerText;
            const inputDiagnostico = document.querySelector('input[name="diagnostico_presuntivo"]');
            inputDiagnostico.value = textoIA.substring(0, 250);
            inputDiagnostico.focus();
        }

        // ==========================================
        // 3. LÓGICA DE DICTADO POR VOZ (Whisper) - ¡ESTO FALTABA!
        // ==========================================
        let mediaRecorder;
        let audioChunks = [];
        let isRecording = false;

        async function toggleGrabacion() {
            const btn = document.getElementById('btn-dictado');
            const estado = document.getElementById('estado-grabacion');
            const textoBtn = document.getElementById('texto-mic');
            const iconoBtn = document.getElementById('icono-mic');

            if (!isRecording) {
                // INICIAR
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    mediaRecorder = new MediaRecorder(stream);
                    audioChunks = [];

                    mediaRecorder.ondataavailable = event => audioChunks.push(event.data);
                    
                    mediaRecorder.onstop = async () => {
                        const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                        enviarAudioBackend(audioBlob);
                    };

                    mediaRecorder.start();
                    isRecording = true;
                    
                    // UI: Grabando
                    estado.classList.remove('hidden');
                    textoBtn.innerText = "Detener";
                    btn.classList.add('bg-red-50', 'text-red-600', 'border-red-200');
                    iconoBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 animate-pulse" viewBox="0 0 20 20" fill="currentColor"><rect x="5" y="5" width="10" height="10" /></svg>`;

                } catch (err) {
                    alert('Permiso de micrófono denegado.');
                }
            } else {
                // DETENER
                mediaRecorder.stop();
                isRecording = false;
                
                // UI: Procesando
                estado.innerText = "Transcribiendo...";
                estado.classList.remove('text-red-600', 'animate-pulse');
                estado.classList.add('text-indigo-600');
                textoBtn.innerText = "Procesando...";
                btn.disabled = true;
            }
        }

        async function enviarAudioBackend(audioBlob) {
            const formData = new FormData();
            formData.append('audio', audioBlob, 'grabacion.webm');

            try {
                // Asegúrate que esta ruta exista en web.php
                const response = await fetch("{{ route('consultas.transcribir') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.texto) {
                    const textarea = document.getElementById('motivo_consulta');
                    // Añade el texto al final con un espacio
                    textarea.value = textarea.value ? textarea.value + " " + data.texto : data.texto;
                } else {
                    alert('No se escuchó nada claro.');
                }

            } catch (error) {
                console.error(error);
                alert('Error al enviar el audio.');
            } finally {
                // Reset UI
                const btn = document.getElementById('btn-dictado');
                const estado = document.getElementById('estado-grabacion');
                const textoBtn = document.getElementById('texto-mic');
                const iconoBtn = document.getElementById('icono-mic');

                btn.disabled = false;
                btn.classList.remove('bg-red-50', 'text-red-600', 'border-red-200');
                textoBtn.innerText = "Dictar";
                iconoBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" /></svg>`;
                
                estado.classList.add('hidden');
                estado.innerText = "● Grabando... (Habla ahora)"; // Reset texto original
                estado.classList.add('text-red-600', 'animate-pulse'); // Reset clases originales
                estado.classList.remove('text-indigo-600');
            }
        }
    </script>
</x-app-layout>