<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configuración del Sitio Web') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('web.update') }}" method="POST">
                @csrf
                
                <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                    <div class="p-6 space-y-6">
                        
                        <div class="border-b pb-2 mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Textos de la Página Principal</h3>
                            <p class="text-sm text-gray-500">Edite los textos visibles en la landing page.</p>
                        </div>

                        @foreach($configs as $config)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start border-b border-gray-50 pb-4 last:border-0">
                                <label for="{{ $config->key }}" class="block text-sm font-bold text-gray-700 pt-2">
                                    {{ $config->label }}
                                </label>
                                
                                <div class="md:col-span-2">
                                    @if($config->type == 'textarea')
                                        <textarea name="{{ $config->key }}" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ $config->value }}</textarea>
                                    @else
                                        <input type="text" name="{{ $config->key }}" value="{{ $config->value }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    @endif
                                </div>
                            </div>
                        @endforeach

                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Guardar Cambios
                        </button>
                    </div>
                </div>
            </form>

            {{-- SECCIÓN 2: GESTIÓN DE ESPECIALIDADES --}}
            <div class="mt-8 bg-white shadow sm:rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="border-b pb-2 mb-4 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Tarjetas de Especialidades</h3>
                            <p class="text-sm text-gray-500">Agregue o elimine las tarjetas que aparecen en la sección de servicios.</p>
                        </div>
                    </div>

                    {{-- Formulario para Agregar --}}
                    <form action="{{ route('especialidad.store') }}" method="POST" class="mb-8 bg-gray-50 p-4 rounded-lg border border-gray-200">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Nombre</label>
                                <input type="text" name="nombre" placeholder="Ej: Cardiología" class="w-full text-sm border-gray-300 rounded-md" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Icono (Emoji)</label>
                                <input type="text" name="icono" placeholder="Ej: ❤️" class="w-full text-sm border-gray-300 rounded-md" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Color de Acento</label>
                                <select name="color" class="w-full text-sm border-gray-300 rounded-md">
                                    <option value="primary">Azul Oscuro (Primary)</option>
                                    <option value="secondary">Verde (Secondary)</option>
                                    <option value="blue-cloud">Azul Claro (Cloud)</option>
                                </select>
                            </div>
                            <button type="submit" class="bg-secondary hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm font-bold shadow">
                                + Agregar
                            </button>
                        </div>
                    </form>

                    {{-- Lista de Especialidades Existentes --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($especialidades as $esp)
                            <div class="border rounded-lg p-4 text-center relative group hover:shadow-md transition">
                                <span class="text-3xl block mb-2">{{ $esp->icono }}</span>
                                <p class="font-bold text-gray-800 text-sm">{{ $esp->nombre }}</p>
                                <span class="text-xs text-gray-400">{{ $esp->color }}</span>

                                {{-- Botón Eliminar (Aparece al pasar el mouse) --}}
                                <form action="{{ route('especialidad.destroy', $esp->id) }}" method="POST" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                                    @csrf @method('DELETE')
                                   <button type="submit" class="text-red-500 hover:text-red-700 bg-white rounded-full p-1 shadow">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                     </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>