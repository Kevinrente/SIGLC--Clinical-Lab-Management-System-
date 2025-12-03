<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Control de Caja - Turno Activo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- ALERTAS --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4">{{ session('success') }}</div>
            @endif

            {{-- 1. RESUMEN DEL TURNO --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-gray-400">
                    <p class="text-gray-500 text-sm">Monto Inicial (Base)</p>
                    <p class="text-2xl font-bold text-gray-800">${{ number_format($sesionActual->monto_inicial, 2) }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
                    <p class="text-gray-500 text-sm">Ventas en Efectivo (+)</p>
                    <p class="text-2xl font-bold text-green-700">${{ number_format($ingresosEfectivo, 2) }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-red-500">
                    <p class="text-gray-500 text-sm">Salidas / Gastos (-)</p>
                    <p class="text-2xl font-bold text-red-700">${{ number_format($totalGastos, 2) }}</p>
                </div>
                <div class="bg-indigo-50 p-6 rounded-lg shadow border-l-4 border-indigo-500">
                    <p class="text-indigo-600 text-sm font-bold">SALDO EN CAJA (=)</p>
                    <p class="text-3xl font-bold text-indigo-900">${{ number_format($saldoActual, 2) }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- 2. LISTA DE GASTOS Y BOTÓN DE REGISTRO --}}
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-gray-700">Movimientos de Salida (Gastos)</h3>
                        <button onclick="document.getElementById('modalGasto').classList.remove('hidden')" class="bg-red-100 text-red-700 px-3 py-1 rounded hover:bg-red-200 text-sm font-bold flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
                            Registrar Gasto
                        </button>
                    </div>

                    @if($gastos->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead>
                                <tr>
                                    <th class="text-left py-2">Hora</th>
                                    <th class="text-left py-2">Descripción</th>
                                    <th class="text-right py-2">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gastos as $gasto)
                                    <tr>
                                        <td class="py-2 text-gray-500">{{ $gasto->created_at->format('H:i') }}</td>
                                        <td class="py-2">{{ $gasto->descripcion }}</td>
                                        <td class="py-2 text-right font-bold text-red-600">-${{ number_format($gasto->monto, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-gray-400 text-sm italic text-center py-4">No hay gastos registrados en este turno.</p>
                    @endif
                </div>

                {{-- 3. ZONA DE CIERRE --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                    <h3 class="font-bold text-gray-900 mb-4">Cierre de Turno</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Cuente el dinero físico. Debería tener exactamente <strong>${{ number_format($saldoActual, 2) }}</strong>.
                    </p>

                    <form action="{{ route('caja.cerrar', $sesionActual->id) }}" method="POST" onsubmit="return confirm('¿Seguro que desea cerrar caja?');">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Saldo Real (Contado)</label>
                            <input type="number" step="0.01" name="saldo_real" required class="block w-full rounded-md border-gray-300" placeholder="0.00">
                        </div>
                        <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded transition">
                            Cerrar Caja
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL PARA REGISTRAR GASTO --}}
    <div id="modalGasto" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="document.getElementById('modalGasto').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('caja.gasto.store') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Registrar Salida de Dinero</h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Descripción del Gasto</label>
                                <input type="text" name="descripcion" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Ej: Compra de agua, Pago taxi...">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Monto a Retirar ($)</label>
                                <input type="number" step="0.01" name="monto" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">
                            Registrar Gasto
                        </button>
                        <button type="button" onclick="document.getElementById('modalGasto').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>