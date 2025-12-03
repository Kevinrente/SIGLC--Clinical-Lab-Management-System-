<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cargar Resultados de Laboratorio') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    {{-- 1. BLOQUE DE ALERTAS (MOVIMOS ESTO ARRIBA) --}}
                    @if (session('success'))
                        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                            <p class="font-bold">¡Éxito!</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm" role="alert">
                            <p class="font-bold">Error:</p>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded shadow-sm">
                            <p class="font-bold">Atención:</p>
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- 2. ENCABEZADO PACIENTE --}}
                    <div class="mb-8 bg-blue-50 p-4 rounded-lg border border-blue-100">
                        <h3 class="text-lg font-bold text-blue-800 mb-2">Orden #{{ $orden->id }} - {{ $orden->paciente->nombre }} {{ $orden->paciente->apellido }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-700">
                            <p><strong>Doctor:</strong> {{ $orden->doctor->usuario->name ?? 'N/A' }}</p>
                            <p><strong>Fecha Solicitud:</strong> {{ $orden->created_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Edad:</strong> {{ $orden->paciente->edad }} años</p>
                        </div>
                    </div>

                    {{-- 3. FORMULARIO --}}
                    <form action="{{ route('laboratorio.update', $orden->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Iteramos sobre los exámenes de la orden --}}
                        <div class="space-y-8">
                            @foreach($orden->examenes as $examen)
                                {{-- CLASE 'item-examen' PRESENTE --}}
                                <div class="border rounded-lg p-5 shadow-sm hover:shadow-md transition item-examen">
                                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                                        <h4 class="text-xl font-bold text-indigo-700">{{ $examen->nombre }}</h4>
                                        <span class="text-xs font-mono bg-gray-100 px-2 py-1 rounded text-gray-500">
                                            Ref: {{ $examen->valor_referencia ?? 'N/A' }}
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        {{-- CASO 1: Examen Complejo --}}
                                        @if(!empty($examen->campos_dinamicos))
                                            @foreach($examen->campos_dinamicos as $campo)
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $campo }}</label>
                                                    <input type="text" 
                                                           name="resultados[{{ $examen->id }}][{{ $campo }}]"
                                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                           placeholder="Valor..." required>
                                                </div>
                                            @endforeach

                                        {{-- CASO 2: Examen Simple --}}
                                        @else
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Resultado</label>
                                                <div class="flex rounded-md shadow-sm">
                                                    <input type="text" 
                                                           name="resultados[{{ $examen->id }}][valor]"
                                                           class="flex-1 rounded-none rounded-l-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                           placeholder="Ej: 95" required>
                                                    <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                                        {{ $examen->unidades ?? '-' }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="md:col-span-2 mt-2">
                                            <label class="block text-xs text-gray-500">Observaciones (Opcional)</label>
                                            <input type="text" name="observaciones[{{ $examen->id }}]" class="w-full mt-1 text-sm border-gray-200 rounded focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Notas internas...">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- BLOQUE DE INTERPRETACIÓN IA --}}
                        <div class="mt-8 bg-purple-50 p-6 rounded-lg border border-purple-100">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold text-purple-900 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                    Interpretación Diagnóstica (Global)
                                </h3>
                                <button type="button" onclick="generarConclusion()" id="btn-ia-lab" class="bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold py-2 px-4 rounded shadow flex items-center gap-2 transition transform hover:scale-105">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    Generar Resumen con IA
                                </button>
                            </div>
                            <p class="text-sm text-purple-700 mb-2">La IA analizará todos los valores ingresados arriba y redactará una conclusión técnica.</p>
                            <textarea name="observacion_general" id="observacion_general" rows="3" class="w-full rounded-md border-purple-200 shadow-sm focus:border-purple-500 focus:ring-purple-500 bg-white" placeholder="Conclusión general..."></textarea>
                        </div>

                        {{-- Acciones Finales --}}
                        <div class="mt-8 flex justify-end items-center gap-4 border-t pt-6">
                            <a href="{{ route('laboratorio.index') }}" class="text-gray-600 hover:text-gray-900">Cancelar</a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded shadow-lg flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Guardar Resultados y Generar PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT JAVASCRIPT CORREGIDO (CON PROTECCIÓN NULL) --}}
    <script>
    async function generarConclusion() {
        const btn = document.getElementById('btn-ia-lab');
        const textarea = document.getElementById('observacion_general');
        
        let resultados = [];
        let bloques = document.querySelectorAll('.item-examen');
        
        if (bloques.length === 0) bloques = document.querySelectorAll('.border.rounded-lg');

        bloques.forEach(bloque => {
            const tituloEl = bloque.querySelector('h4');
            if (!tituloEl) return; 

            const nombreExamen = tituloEl.innerText.trim();
            const refEl = bloque.querySelector('span.font-mono');
            const referenciaText = refEl ? refEl.innerText : '';
            
            const inputs = bloque.querySelectorAll('input[name^="resultados"]');
            
            inputs.forEach(input => {
                if(input.value && input.value.trim() !== '') {
                    resultados.push({
                        examen: nombreExamen,
                        valor: input.value,
                        referencia: referenciaText
                    });
                }
            });
        });

        if (resultados.length === 0) {
            alert('No encontré valores. Asegúrate de escribir los números en los campos de resultado.');
            return;
        }

        const textoOriginal = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = `Analizando...`;
        textarea.value = "Consultando a la IA...";

        try {
            const response = await fetch("{{ route('laboratorio.interpretar') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    resultados: resultados,
                    paciente_info: "{{ $orden->paciente->nombre }} ({{ $orden->paciente->edad }} años)"
                })
            });

            if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);

            const data = await response.json();
            textarea.value = data.conclusion;
            
        } catch (error) {
            console.error(error);
            // Intentar leer el mensaje que mandó el servidor
            let mensaje = "Error al conectar con la IA.";
            if (error.message) mensaje = error.message;
            
            textarea.value = "Error: " + mensaje;
            alert(mensaje); // <--- ESTO TE DIRÁ EL PROBLEMA REAL
        } finally {
            btn.disabled = false;
            btn.innerHTML = textoOriginal;
        }
    }
    </script>
</x-app-layout>