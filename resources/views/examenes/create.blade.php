<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Registrar Nuevo Examen') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form action="{{ route('examenes.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre del Examen</label>
                            <input type="text" name="nombre" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Categoría</label>
                            <input type="text" name="categoria" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" list="categoriasList">
                            <datalist id="categoriasList">
                                <option value="Hematología"><option value="Bioquímica"><option value="Inmunología"><option value="Hormonas"><option value="Urianálisis">
                            </datalist>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Precio ($)</label>
                            <input type="number" step="0.01" name="precio" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Unidades (Ej: mg/dL)</label>
                            <input type="text" name="unidades" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Valor de Referencia (Ej: 70 - 110)</label>
                            <input type="text" name="valor_referencia" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('examenes.index') }}" class="px-4 py-2 border rounded text-gray-600 hover:bg-gray-50">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Guardar Examen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>