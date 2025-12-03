<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Editar Examen') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                
                {{-- 1. FORMULARIO PRINCIPAL (DATOS DEL EXAMEN) --}}
                <form action="{{ route('examenes.update', $examen->id) }}" method="POST" id="mainForm">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre del Examen</label>
                            <input type="text" name="nombre" value="{{ old('nombre', $examen->nombre) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Categoría</label>
                            <input type="text" name="categoria" value="{{ old('categoria', $examen->categoria) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" list="categoriasList">
                            <datalist id="categoriasList">
                                <option value="Hematología"><option value="Bioquímica"><option value="Inmunología"><option value="Hormonas"><option value="Urianálisis">
                            </datalist>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Precio ($)</label>
                            <input type="number" step="0.01" name="precio" value="{{ old('precio', $examen->precio) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        
                        {{-- Datos Técnicos --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Unidades (Ej: mg/dL)</label>
                            <input type="text" name="unidades" value="{{ old('unidades', $examen->unidades) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Valor de Referencia (Ej: 70 - 110)</label>
                            <input type="text" name="valor_referencia" value="{{ old('valor_referencia', $examen->valor_referencia) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('examenes.index') }}" class="px-4 py-2 border rounded text-gray-600 hover:bg-gray-50">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 font-bold">
                            Actualizar Examen
                        </button>
                    </div>
                </form>
                {{-- FIN FORMULARIO PRINCIPAL --}}

                <hr class="my-8 border-gray-200">

                {{-- 2. SECCIÓN DE INSUMOS (RECETA) --}}
                <div class="bg-gray-50 p-5 rounded-lg border border-gray-200">
                    <h4 class="font-bold text-gray-800 mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                        Insumos que consume este examen
                    </h4>
                    
                    @if($examen->insumos->count() > 0)
                        <table class="w-full text-sm text-left mb-4 bg-white rounded border border-gray-200 overflow-hidden">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2">Insumo</th>
                                    <th class="px-4 py-2">Cantidad a descontar</th>
                                    <th class="px-4 py-2 text-right">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($examen->insumos as $insumo)
                                    <tr class="border-b last:border-0">
                                        <td class="px-4 py-2 font-medium text-gray-900">{{ $insumo->nombre }}</td>
                                        <td class="px-4 py-2 text-gray-600">{{ $insumo->pivot->cantidad_necesaria }} {{ $insumo->unidad_medida }}</td>
                                        <td class="px-4 py-2 text-right">
                                            <form action="{{ route('examenes.insumos.destroy', [$examen->id, $insumo->id]) }}" method="POST" class="inline">
                                                @csrf 
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-bold uppercase tracking-wider">Quitar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-sm text-gray-500 italic mb-4 bg-white p-3 rounded border border-dashed border-gray-300 text-center">
                            No hay insumos asignados a este examen.
                        </p>
                    @endif

                    {{-- Formulario Visual para Agregar (No es un <form> real, solo inputs para el JS) --}}
                    <div class="flex flex-col md:flex-row gap-4 items-end pt-2">
                        <div class="flex-grow w-full">
                            <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Agregar Insumo</label>
                            <select id="selectInsumo" class="w-full text-sm border-gray-300 rounded-md focus:border-green-500 focus:ring-green-500">
                                @foreach(\App\Models\Insumo::orderBy('nombre')->get() as $ins)
                                    <option value="{{ $ins->id }}">{{ $ins->nombre }} ({{ $ins->unidad_medida }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full md:w-32">
                            <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Cantidad</label>
                            <input type="number" id="inputCantidad" step="0.01" class="w-full text-sm border-gray-300 rounded-md focus:border-green-500 focus:ring-green-500" placeholder="Ej: 1">
                        </div>
                        <button type="button" onclick="asignarInsumo()" class="w-full md:w-auto bg-green-600 text-white px-4 py-2 rounded-md text-sm font-bold hover:bg-green-700 shadow-sm transition">
                            Asignar
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- 3. FORMULARIO OCULTO (Para enviar la asignación) --}}
    {{-- Este form está FUERA de todo lo demás para evitar conflictos --}}
    <form id="formAsignar" action="{{ route('examenes.insumos.store', $examen->id) }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="insumo_id" id="hiddenInsumo">
        <input type="hidden" name="cantidad" id="hiddenCantidad">
    </form>

    <script>
        function asignarInsumo() {
            const insumoId = document.getElementById('selectInsumo').value;
            const cantidad = document.getElementById('inputCantidad').value;
            
            if(!cantidad || cantidad <= 0) {
                alert('Por favor ingrese una cantidad válida mayor a 0.');
                return;
            }

            // Pasamos los valores al formulario oculto
            document.getElementById('hiddenInsumo').value = insumoId;
            document.getElementById('hiddenCantidad').value = cantidad;
            
            // Enviamos
            document.getElementById('formAsignar').submit();
        }
    </script>
</x-app-layout>