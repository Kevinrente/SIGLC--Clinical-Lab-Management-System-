<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Caja - Cobro de Laboratorio
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('pagos.orden.store') }}" method="POST" id="formCobro">
                @csrf
                <input type="hidden" name="orden_id" value="{{ $orden->id }}">
                
                {{-- Input oculto para enviar el total final calculado --}}
                <input type="hidden" name="monto_total_final" id="inputTotalFinal">

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        
                        {{-- COLUMNA IZQUIERDA: ITEMS EDITABLES --}}
                        <div class="md:col-span-2 p-6 border-r border-gray-100">
                            <h3 class="text-lg font-bold text-gray-700 mb-2">Orden #{{ $orden->id }}</h3>
                            <p class="text-sm text-gray-500 mb-6">Paciente: {{ $orden->paciente->nombre }} {{ $orden->paciente->apellido }}</p>

                            <table class="w-full text-left text-sm text-gray-600">
                                <thead class="bg-gray-50 text-gray-800 font-bold border-b">
                                    <tr>
                                        <th class="py-2 px-3">Examen</th>
                                        <th class="py-2 px-3 text-right" width="150">Precio ($)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @foreach($orden->examenes as $examen)
                                        <tr>
                                            <td class="py-3 px-3 align-middle">
                                                {{ $examen->nombre }}
                                            </td>
                                            <td class="py-3 px-3">
                                                {{-- INPUT DE PRECIO INDIVIDUAL --}}
                                                <input type="number" 
                                                       step="0.01" 
                                                       name="precios[{{ $examen->id }}]" 
                                                       value="{{ $examen->precio }}" 
                                                       class="input-precio w-full text-right border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 font-mono"
                                                       oninput="calcularTotal()">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="border-t-2 border-gray-200">
                                    <tr>
                                        <td class="py-4 px-3 text-right font-bold text-gray-600">Subtotal:</td>
                                        <td class="py-4 px-3 text-right font-mono text-gray-800" id="displaySubtotal">$0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-3 text-right font-bold text-red-500 flex justify-end items-center gap-2">
                                            Descuento (%):
                                            <input type="number" name="descuento_porcentaje" id="inputDescuento" value="0" min="0" max="100" class="w-16 text-center border-gray-300 rounded text-sm py-1" oninput="calcularTotal()">
                                        </td>
                                        <td class="py-2 px-3 text-right font-mono text-red-500" id="displayDescuento">-$0.00</td>
                                    </tr>
                                    <tr class="bg-indigo-50">
                                        <td class="py-4 px-3 text-right font-bold text-xl text-indigo-900">TOTAL A PAGAR:</td>
                                        <td class="py-4 px-3 text-right font-bold text-xl text-indigo-900 font-mono" id="displayTotalFinal">$0.00</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        {{-- COLUMNA DERECHA: PAGO --}}
                        <div class="p-6 bg-gray-50 flex flex-col justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-indigo-700 mb-6">Datos de Facturación</h3>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Método de Pago</label>
                                    <select name="metodo_pago" class="w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Transferencia">Transferencia Bancaria</option>
                                        <option value="Tarjeta">Tarjeta de Débito/Crédito</option>
                                    </select>
                                </div>

                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Referencia / Observación</label>
                                    <textarea name="referencia" rows="3" placeholder="Opcional..." class="w-full border-gray-300 rounded-md shadow-sm"></textarea>
                                </div>
                            </div>

                            <div>
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-4 rounded shadow-lg transform transition hover:scale-105 flex justify-center items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                    Cobrar y Generar Factura
                                </button>
                                <div class="mt-4 text-center">
                                    <a href="{{ route('laboratorio.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancelar Operación</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- JAVASCRIPT: CALCULADORA EN TIEMPO REAL --}}
    <script>
        function calcularTotal() {
            let subtotal = 0;
            
            // 1. Sumar todos los inputs de precio
            document.querySelectorAll('.input-precio').forEach(input => {
                let valor = parseFloat(input.value);
                if (isNaN(valor)) valor = 0;
                subtotal += valor;
            });

            // 2. Calcular Descuento
            let descuentoPorcentaje = parseFloat(document.getElementById('inputDescuento').value);
            if (isNaN(descuentoPorcentaje)) descuentoPorcentaje = 0;
            
            let descuentoValor = subtotal * (descuentoPorcentaje / 100);
            let totalFinal = subtotal - descuentoValor;

            // 3. Actualizar Textos en Pantalla
            document.getElementById('displaySubtotal').innerText = '$' + subtotal.toFixed(2);
            document.getElementById('displayDescuento').innerText = '-$' + descuentoValor.toFixed(2);
            document.getElementById('displayTotalFinal').innerText = '$' + totalFinal.toFixed(2);

            // 4. Actualizar Input Oculto (para enviar al backend)
            document.getElementById('inputTotalFinal').value = totalFinal.toFixed(2);
        }

        // Ejecutar al cargar la página por primera vez
        document.addEventListener('DOMContentLoaded', calcularTotal);
    </script>
</x-app-layout>