<!DOCTYPE html>
<html>
<head>
    <title>Resultados Listos</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2 style="color: #4f46e5; text-align: center;">Tus Resultados están Listos</h2>
        
        <p>Hola <strong>{{ $orden->paciente->nombre }}</strong>,</p>
        
        <p>El Laboratorio SIGLC le informa que los exámenes correspondientes a la orden <strong>#{{ $orden->id }}</strong> han sido procesados exitosamente.</p>
        
        <div style="background-color: #eef2ff; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p style="margin: 0; color: #3730a3;"><strong>Exámenes realizados:</strong></p>
            <ul style="margin-top: 5px;">
                @foreach($orden->examenes as $examen)
                    <li>{{ $examen->nombre }}</li>
                @endforeach
            </ul>
        </div>

        <p>Puede encontrar el informe detallado en el <strong>archivo PDF adjunto</strong> a este correo.</p>
        
        <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">
        
        <p style="font-size: 12px; color: #888; text-align: center;">
            Este es un mensaje automático. Por favor no responda a este correo.<br>
            Laboratorio Clínico SIGLC
        </p>
    </div>
</body>
</html>