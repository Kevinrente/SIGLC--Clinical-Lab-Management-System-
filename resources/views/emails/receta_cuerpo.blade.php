<!DOCTYPE html>
<html>
<head><title>Receta Médica</title></head>
<body style="font-family: Arial, sans-serif; color: #333;">
    <h2>Hola, {{ $consulta->paciente->nombre }}</h2>
    <p>Gracias por atenderse con nosotros.</p>
    <p>Adjunto a este correo encontrará su <strong>Receta Médica</strong> en formato PDF correspondiente a la consulta realizada el día de hoy.</p>
    <br>
    <p>Atentamente,<br><strong>Centro Médico SIGLC</strong></p>
</body>
</html>