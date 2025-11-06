<x-app-layout>
    <div class="py-12"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8"><div class="bg-white p-6 shadow-sm sm:rounded-lg">
        <form action="{{ route('pacientes.store') }}" method="POST">
            @include('pacientes._form', ['paciente' => new \App\Models\Paciente()])
        </form>
    </div></div></div>
</x-app-layout>