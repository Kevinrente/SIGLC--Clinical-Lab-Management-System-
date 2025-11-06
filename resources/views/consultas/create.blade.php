<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Consulta para: ' . $cita->paciente->nombre . ' ' . $cita->paciente->apellido) }}
        </h2>
    </x-slot>

    <div class="py-12"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8"><div class="bg-white p-6 shadow-sm sm:rounded-lg">
        <form action="{{ route('consultas.store') }}" method="POST">
            @include('consultas._form', ['consulta' => new \App\Models\Consulta(), 'cita' => $cita])
        </form>
    </div></div></div>
</x-app-layout>