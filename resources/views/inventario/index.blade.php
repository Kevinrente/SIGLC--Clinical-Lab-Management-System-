<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Inventario de Reactivos e Insumos</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- FORMULARIO NUEVO INSUMO --}}
            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <h3 class="font-bold text-lg mb-4">Registrar Nuevo Insumo</h3>
                <form action="{{ route('inventario.store') }}" method="POST" class="flex gap-4 items-end">
                    @csrf
                    <div>
                        <label class="text-xs font-bold text-gray-500">Nombre</label>
                        <input type="text" name="nombre" required class="w-full border-gray-300 rounded text-sm" placeholder="Ej: Tubos Tapa Roja">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500">Unidad</label>
                        <input type="text" name="unidad_medida" required class="w-24 border-gray-300 rounded text-sm" placeholder="ml, unid">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500">Stock Mínimo</label>
                        <input type="number" name="stock_minimo" required class="w-24 border-gray-300 rounded text-sm" value="10">
                    </div>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm font-bold hover:bg-indigo-700">Crear</button>
                </form>
            </div>

            {{-- TABLA DE STOCK --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Insumo</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Stock Actual</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($insumos as $insumo)
                            <tr>
                                <td class="px-6 py-4">{{ $insumo->nombre }}</td>
                                <td class="px-6 py-4 font-mono font-bold text-lg">
                                    {{ $insumo->stock_actual }} <span class="text-xs font-normal text-gray-500">{{ $insumo->unidad_medida }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($insumo->stock_actual <= $insumo->stock_minimo)
                                        <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded font-bold">BAJO STOCK</span>
                                    @else
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded font-bold">OK</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('inventario.addStock', $insumo->id) }}" method="POST" class="flex gap-2">
                                        @csrf
                                        <input type="number" name="cantidad" step="0.1" class="w-20 text-sm border-gray-300 rounded" placeholder="+ Cant">
                                        <button type="submit" class="text-green-600 hover:text-green-900 font-bold text-sm">Agregar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>