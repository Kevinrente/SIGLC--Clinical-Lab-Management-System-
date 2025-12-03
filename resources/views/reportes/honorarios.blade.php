<table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="px-4 py-3 text-left">Doctor</th>
                            <th class="px-4 py-3 text-center">Actividad</th>
                            <th class="px-4 py-3 text-right">Comisión Consultas</th>
                            <th class="px-4 py-3 text-right">Comisión Lab <small class="block font-normal text-gray-400">(Config)</small></th>
                            <th class="px-4 py-3 text-right bg-green-700 font-bold">TOTAL A PAGAR</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reporte as $row)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-bold text-gray-900">
                                    {{ $row['doctor'] }}
                                </td>
                                <td class="px-4 py-3 text-center text-xs text-gray-500">
                                    {{ $row['consultas_count'] }} Consultas<br>
                                    {{ $row['ordenes_count'] }} Órdenes
                                </td>
                                <td class="px-4 py-3 text-right text-gray-700">
                                    ${{ number_format($row['pago_consultas'], 2) }}
                                </td>
                                <td class="px-4 py-3 text-right text-indigo-700 font-medium">
                                    ${{ number_format($row['pago_laboratorio'], 2) }}
                                    <span class="block text-xs text-gray-400">({{ $row['configuracion_lab'] }})</span>
                                </td>
                                <td class="px-4 py-3 text-right font-mono font-bold text-green-700 text-lg bg-green-50">
                                    ${{ number_format($row['total_a_pagar'], 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>