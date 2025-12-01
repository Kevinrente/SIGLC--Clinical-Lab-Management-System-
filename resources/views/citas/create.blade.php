<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Agendar Nueva Cita') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-lg shadow-lg border border-gray-200">
                
                {{-- Mostrar errores de validación generales si los hay --}}
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700">
                        <p class="font-bold">Por favor corrige los siguientes errores:</p>
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('citas.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        {{-- Selección de Paciente --}}
                        <div>
                            <label for="paciente_id" class="block text-sm font-medium text-gray-700 mb-1">Paciente</label>
                            <select name="paciente_id" id="paciente_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="">-- Seleccionar Paciente --</option>
                                @foreach($pacientes as $paciente)
                                    <option value="{{ $paciente->id }}" {{ old('paciente_id') == $paciente->id ? 'selected' : '' }}>
                                        {{ $paciente->nombre }} {{ $paciente->apellido }} ({{ $paciente->identificacion }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Selección de Doctor --}}
                        <div>
                            <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-1">Doctor / Especialista</label>
                            <select name="doctor_id" id="doctor_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="">-- Seleccionar Doctor --</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        Dr. {{ $doctor->apellido }} ({{ $doctor->especialidad }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        {{-- Fecha y Hora --}}
                        <div>
                            <label for="fecha_hora" class="block text-sm font-medium text-gray-700 mb-1">Fecha y Hora</label>
                            {{-- Usamos datetime-local para evitar problemas de formato (DD/MM/YYYY HH:MM) --}}
                            <input type="datetime-local" name="fecha_hora" id="fecha_hora" 
                                   value="{{ old('fecha_hora') }}" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>

                        {{-- Estado Inicial --}}
                        <div>
                            <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                            <select name="estado" id="estado" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="Pendiente" selected>Pendiente</option>
                                <option value="Confirmada">Confirmada</option>
                            </select>
                        </div>
                    </div>

                    {{-- Motivo --}}
                    <div class="mt-6">
                        <label for="motivo" class="block text-sm font-medium text-gray-700 mb-1">Motivo de la Cita</label>
                        <textarea name="motivo" id="motivo" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Ej: Dolor abdominal, chequeo general...">{{ old('motivo') }}</textarea>
                    </div>

                    <div class="flex items-center justify-end mt-8 space-x-3">
                        <a href="{{ route('citas.index') }}" class="text-gray-600 hover:text-gray-900 font-medium text-sm">Cancelar</a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-md shadow-md transition duration-150">
                            Guardar Cita
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>