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

                    <h3 class="text-lg font-bold mb-4 text-gray-800 border-b pb-2">Selección de Exámenes</h3>

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
</x-app-layout>