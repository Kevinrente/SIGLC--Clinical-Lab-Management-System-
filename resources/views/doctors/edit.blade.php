<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Editar Doctor: ' . $doctor->nombre . ' ' . $doctor->apellido) }}</h2>
    </x-slot>

    <div class="py-12"><div class="max-w-xl mx-auto sm:px-6 lg:px-8"><div class="bg-white p-6 shadow-sm sm:rounded-lg">
        <form action="{{ route('doctors.update', $doctor) }}" method="POST">
            @method('PUT')
            @include('doctors._form', ['doctor' => $doctor])
        </form>
    </div></div></div>
</x-app-layout>