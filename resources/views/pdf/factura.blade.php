<!DOCTYPE html>
<html>
<head>
    <title>Recibo de Pago #{{ $orden->id }}</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 1px dashed #ccc; padding-bottom: 10px; }
        .info { margin-bottom: 20px; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { text-align: left; border-bottom: 1px solid #000; padding: 5px; }
        td { padding: 5px; border-bottom: 1px solid #eee; }
        .total-row td { border-top: 1px solid #000; font-weight: bold; font-size: 16px; }
        .footer { text-align: center; font-size: 10px; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LABORATORIO CLÍNICO SIGLC</h2>
        <p>RUC: 0801234567001</p>
        <p>RECIBO DE CAJA</p>
    </div>

    <div class="info">
        <strong>Orden:</strong> #{{ str_pad($orden->id, 6, '0', STR_PAD_LEFT) }}<br>
        <strong>Fecha:</strong> {{ now()->format('d/m/Y H:i') }}<br>
        <strong>Paciente:</strong> {{ $orden->paciente->nombre }} {{ $orden->paciente->apellido }}<br>
        <strong>Cédula:</strong> {{ $orden->paciente->cedula }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Descripción</th>
                <th style="text-align: right;">Valor</th>
            </tr>
        </thead>
        <tbody>
            @php $subtotal = 0; @endphp
            @foreach($orden->examenes as $examen)
                @php 
                    // Tomamos el precio cobrado, o el del catálogo si falló algo
                    $precio = $examen->pivot->precio_cobrado ?? $examen->precio;
                    $subtotal += $precio;
                @endphp
                <tr>
                    <td>{{ $examen->nombre }}</td>
                    <td style="text-align: right;">${{ number_format($precio, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            @if($orden->pago->monto_total < $subtotal)
            <tr>
                <td style="text-align: right;">Descuento Aplicado:</td>
                <td style="text-align: right;">- ${{ number_format($subtotal - $orden->pago->monto_total, 2) }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td style="text-align: right;">TOTAL PAGADO:</td>
                <td style="text-align: right;">${{ number_format($orden->pago->monto_total, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="info">
        <strong>Forma de Pago:</strong> {{ $orden->pago->metodo_pago }}<br>
        <strong>Cajero:</strong> {{ Auth::user()->name }}
    </div>

    <div class="footer">
        Gracias por su preferencia.<br>
        Este documento no tiene validez tributaria.
    </div>
</body>
</html>