<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Caja - Cobro de Consulta Médica</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('pagos.consulta.store') }}" method="POST">
                @csrf
                <input type="hidden" name="consulta_id" value="{{ $consulta->id }}">

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg grid grid-cols-1 md:grid-cols-2">
                    <div class="p-6 border-r border-gray-100">
                        <h3 class="text-lg font-bold text-gray-700 mb-4">Consulta #{{ $consulta->id }}</h3>
                        <p class="text-sm mb-2"><strong>Paciente:</strong> {{ $consulta->paciente->nombre }} {{ $consulta->paciente->apellido }}</p>
                        <p class="text-sm mb-4"><strong>Doctor:</strong> {{ $consulta->doctor->usuario->name }} ({{ $consulta->doctor->especialidad }})</p>
                        
                        <div class="bg-indigo-50 p-4 rounded-lg mt-6">
                            <label class="block text-xs font-bold text-indigo-700 uppercase mb-1">Honorario Médico ($)</label>
                            <input type="number" step="0.01" name="monto_total" value="{{ $precio }}" class="w-full text-3xl font-mono text-indigo-900 font-bold border-0 bg-transparent focus:ring-0 p-0" placeholder="0.00">
                        </div>
                    </div>

                    <div class="p-6 bg-gray-50">
                        <h3 class="text-lg font-bold text-indigo-700 mb-4">Facturación</h3>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Método de Pago</label>
                            <select name="metodo_pago" class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="Efectivo">Efectivo</option>
                                <option value="Transferencia">Transferencia</option>
                                <option value="Tarjeta">Tarjeta</option>
                            </select>
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700">Referencia</label>
                            <input type="text" name="referencia" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Opcional">
                        </div>
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded shadow-lg flex justify-center items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" /></svg>
                            Cobrar Consulta
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>