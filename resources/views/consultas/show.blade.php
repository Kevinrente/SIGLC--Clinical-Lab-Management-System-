<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Detalle de Consulta Médica') }}</h2>
    </x-slot>

    <div class="py-12"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8"><div class="bg-white p-6 shadow-sm sm:rounded-lg">
        
        <h3 class="text-2xl font-bold mb-4">Consulta de {{ $consulta->paciente->nombre }} {{ $consulta->paciente->apellido }}</h3>
        
        <div class="grid grid-cols-2 gap-4 border-b pb-4 mb-4">
            <div>
                <p class="text-sm font-semibold text-gray-600">Registrada por:</p>
                <p class="text-lg">{{ $consulta->doctor->nombre }} {{ $consulta->doctor->apellido }} ({{ $consulta->doctor->especialidad }})</p>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-600">Fecha de Registro:</p>
                <p class="text-lg">{{ $consulta->created_at->format('d/M/Y H:i') }}</p>
            </div>
        </div>

        <div class="mb-6">
            <p class="text-lg font-semibold mb-2 text-indigo-700">Síntomas Reportados:</p>
            <div class="bg-gray-100 p-3 rounded-lg whitespace-pre-wrap">{{ $consulta->sintomas ?? 'No especificado.' }}</div>
        </div>

        <div class="mb-6">
            <p class="text-lg font-semibold mb-2 text-indigo-700">Diagnóstico:</p>
            <div class="bg-red-100 border-l-4 border-red-500 p-3 rounded-lg font-medium whitespace-pre-wrap">{{ $consulta->diagnostico }}</div>
        </div>

        <div class="mb-6">
            <p class="text-lg font-semibold mb-2 text-indigo-700">Tratamiento / Plan Terapéutico:</p>
            <div class="bg-green-100 p-3 rounded-lg whitespace-pre-wrap">{{ $consulta->tratamiento ?? 'No especificado.' }}</div>
        </div>

        <div class="flex justify-between items-center border-t pt-4">
            <a href="{{ route('consultas.index') }}" class="text-gray-600 hover:text-gray-800">← Volver al Historial</a>
            <div>
                <a href="{{ route('consultas.edit', $consulta) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-2">Editar Consulta</a>
                {{-- Aquí se colocaría el enlace al módulo de Laboratorio --}}
                <button class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">Solicitar Exámenes</button>
            </div>
        </div>
        
    </div></div></div>
</x-app-layout>