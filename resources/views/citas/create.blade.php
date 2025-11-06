<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Agendar Nueva Cita') }}</h2>
    </x-slot>

    <div class="py-12"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8"><div class="bg-white p-6 shadow-sm sm:rounded-lg">
        <form action="{{ route('citas.store') }}" method="POST">
            @include('citas._form', ['cita' => new \App\Models\Cita()])
        </form>
    </div></div></div>
</x-app-layout>