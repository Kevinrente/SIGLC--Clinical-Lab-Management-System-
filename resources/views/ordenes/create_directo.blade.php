<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nueva Orden de Laboratorio (Directa)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="mb-6 bg-blue-50 p-4 rounded border-l-4 border-blue-500">
                    <h3 class="font-bold text-lg">Paciente: {{ $paciente->nombre }} {{ $paciente->apellido }}</h3>
                    <p class="text-sm text-gray-600">Cédula: {{ $paciente->cedula }}</p>
                </div>

                <form action="{{ route('ordenes.store') }}" method="POST">
                    @csrf
                    {{-- Enviamos el ID del paciente --}}
                    <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">
                    {{-- Cita ID va nulo --}}
                    <input type="hidden" name="cita_id" value="">

                    {{-- SELECCIÓN OPCIONAL DE DOCTOR --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Doctor Referente (Opcional)</label>
                        <select name="doctor_id" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 w-full md:w-1/3">
                            <option value="">-- Sin Doctor / Paciente Particular --</option>
                            @foreach($doctores as $doc)
                                <option value="{{ $doc->id }}">Dr. {{ $doc->usuario->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Si el paciente viene por su cuenta, deje este campo vacío.</p>
                    </div>

                    {{-- BLOQUE OCR --}}
                    <div class="flex justify-between items-end border-b pb-2 mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Selección de Exámenes</h3>
                        
                        {{-- Botón para activar el input file oculto --}}
                        <button type="button" onclick="document.getElementById('input_scan').click()" id="btn-scan" class="bg-purple-600 hover:bg-purple-700 text-white text-sm px-4 py-2 rounded shadow flex items-center transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Escanear Orden (IA)
                        </button>

                        {{-- Input oculto --}}
                        <input type="file" id="input_scan" accept="image/*" class="hidden" onchange="procesarImagen(this)">
                    </div>

                    {{-- Mensaje de carga/resultado --}}
                    <div id="scan-feedback" class="hidden mb-4 p-3 rounded text-sm"></div>

                    {{-- LISTA DE EXÁMENES (Reutilizamos lógica visual) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($examenesPorCategoria as $categoria => $examenes)
                            <div class="border rounded-lg p-4 shadow-sm bg-gray-50">
                                <h4 class="font-bold text-indigo-700 mb-3 border-b border-gray-200 pb-1">{{ $categoria }}</h4>
                                <div class="space-y-2">
                                    @foreach($examenes as $examen)
                                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-white p-1 rounded transition">
                                            <input type="checkbox" name="examenes[]" value="{{ $examen->id }}" class="form-checkbox h-5 w-5 text-indigo-600 rounded">
                                            <span class="text-gray-700 text-sm">{{ $examen->nombre }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded shadow-lg">
                            Crear Orden
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        async function procesarImagen(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const feedback = document.getElementById('scan-feedback');
                const btn = document.getElementById('btn-scan');

                // UI: Cargando
                feedback.className = "mb-4 p-3 rounded text-sm bg-blue-100 text-blue-700 border border-blue-200 flex items-center";
                feedback.innerHTML = `<svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Analizando imagen con IA... esto puede tardar unos segundos.`;
                feedback.classList.remove('hidden');
                btn.disabled = true;

                const formData = new FormData();
                formData.append('imagen_orden', file);

                try {
                    const response = await fetch("{{ route('ordenes.escanear') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (data.ids && data.ids.length > 0) {
                        // 1. Limpiar checkboxes previos (Opcional, depende de tu flujo)
                        // document.querySelectorAll('input[type="checkbox"]').forEach(el => el.checked = false);

                        // 2. Marcar los encontrados
                        let count = 0;
                        data.ids.forEach(id => {
                            const checkbox = document.querySelector(`input[value="${id}"]`);
                            if (checkbox) {
                                checkbox.checked = true;
                                // Efecto visual para resaltar lo encontrado
                                checkbox.parentElement.classList.add('bg-green-100', 'border-green-300');
                                count++;
                            }
                        });

                        feedback.className = "mb-4 p-3 rounded text-sm bg-green-100 text-green-700 border border-green-200";
                        feedback.innerHTML = `<strong>¡Listo!</strong> Se detectaron y marcaron <strong>${count}</strong> exámenes automáticamente. Por favor verifica.`;

                    } else {
                        feedback.className = "mb-4 p-3 rounded text-sm bg-yellow-100 text-yellow-700 border border-yellow-200";
                        feedback.innerHTML = "La IA analizó la imagen pero no encontró coincidencias exactas con tu catálogo.";
                    }

                } catch (error) {
                    console.error(error);
                    feedback.className = "mb-4 p-3 rounded text-sm bg-red-100 text-red-700 border border-red-200";
                    feedback.innerHTML = "Error al procesar la imagen.";
                } finally {
                    btn.disabled = false;
                    input.value = ''; // Limpiar input para permitir subir la misma imagen si falla
                }
            }
        }
    </script>

</x-app-layout>