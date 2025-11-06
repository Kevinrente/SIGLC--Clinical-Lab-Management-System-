<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Consulta de: ' . $consulta->paciente->nombre . ' ' . $consulta->paciente->apellido) }}
        </h2>
    </x-slot>

    <div class="py-12"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8"><div class="bg-white p-6 shadow-sm sm:rounded-lg">
        <form action="{{ route('consultas.update', $consulta) }}" method="POST">
            @method('PUT')
            @include('consultas._form', ['consulta' => $consulta, 'cita' => $consulta->cita])
        </form>
    </div></div></div>
</x-app-layout>