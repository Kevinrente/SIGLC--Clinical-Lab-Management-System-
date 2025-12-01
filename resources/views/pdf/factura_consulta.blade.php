<!DOCTYPE html>
<html>
<head>
    <title>Recibo Consulta #{{ $consulta->id }}</title>
    <style>
        body { font-family: sans-serif; color: #333; text-align: center; }
        .box { border: 1px solid #ddd; padding: 20px; max-width: 500px; margin: 0 auto; }
        h2 { margin-bottom: 5px; color: #000; }
        .amount { font-size: 24px; font-weight: bold; margin: 20px 0; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc; padding: 10px; }
        .info { text-align: left; font-size: 14px; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="box">
        <h2>CENTRO MÉDICO SIGLC</h2>
        <p>RECIBO DE HONORARIOS MÉDICOS</p>
        
        <div class="info">
            <strong>Fecha:</strong> {{ now()->format('d/m/Y H:i') }}<br>
            <strong>Paciente:</strong> {{ $consulta->paciente->nombre }} {{ $consulta->paciente->apellido }}<br>
            <strong>Doctor:</strong> {{ $consulta->doctor->usuario->name }}<br>
            <strong>Especialidad:</strong> {{ $consulta->doctor->especialidad }}
        </div>

        <div class="amount">
            TOTAL PAGADO: ${{ number_format($consulta->pago->monto_total, 2) }}
        </div>

        <p style="font-size: 12px; color: #777;">
            Forma de Pago: {{ $consulta->pago->metodo_pago }}<br>
            Atendido por: {{ Auth::user()->name }}
        </p>
    </div>
</body>
</html>