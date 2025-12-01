<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                
                {{-- 1. Mostrar Errores de Validación (Como "La cédula es obligatoria") --}}
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">¡Ups! Hay problemas con tus datos:</strong>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- 2. Mostrar Errores de Base de Datos --}}
                @if (session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <strong class="font-bold">Error del Sistema:</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                {{-- FORMULARIO --}}
                <form action="{{ route('pacientes.store') }}" method="POST">
                    @csrf
                    
                    {{-- Aquí se cargan los inputs desde el archivo parcial --}}
                    @include('pacientes._form', ['paciente' => new \App\Models\Paciente()])
                    
                </form>
            </div>
        </div>
    </div>
</x-app-layout>