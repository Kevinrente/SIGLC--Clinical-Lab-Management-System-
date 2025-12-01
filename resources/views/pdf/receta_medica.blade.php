<!DOCTYPE html>
<html>
<head>
    <title>Receta Médica #{{ $consulta->id }}</title>
    <style>
        body { font-family: sans-serif; color: #333; font-size: 14px; }
        .header { width: 100%; border-bottom: 2px solid #4f46e5; margin-bottom: 20px; padding-bottom: 10px; }
        .logo-text { font-size: 24px; font-weight: bold; color: #4f46e5; }
        .sub-header { width: 100%; margin-bottom: 30px; }
        .patient-info { background-color: #f3f4f6; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        
        /* Tabla de Receta */
        .recipe-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .recipe-table th { background-color: #4f46e5; color: white; text-align: left; padding: 10px; }
        .recipe-table td { border-bottom: 1px solid #ddd; padding: 12px 8px; vertical-align: top; }
        .medicine { font-weight: bold; color: #1f2937; }
        .indication { color: #4b5563; font-style: italic; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #9ca3af; border-top: 1px solid #ddd; padding-top: 10px; }
        .doctor-sign { text-align: center; margin-top: 60px; float: right; width: 200px; border-top: 1px solid #333; padding-top: 5px; }
    </style>
</head>
<body>
    {{-- Encabezado --}}
    <div class="header">
        <div class="logo-text">CENTRO MÉDICO SIGLC</div>
        <div style="font-size: 12px; color: #666;">Dirección: Av. Principal y Calle 5ta • Tel: 0991234567</div>
    </div>

    {{-- Datos del Paciente y Cita --}}
    <div class="patient-info">
        <table width="100%">
            <tr>
                <td><strong>Paciente:</strong> {{ $consulta->paciente->nombre }} {{ $consulta->paciente->apellido }}</td>
                <td><strong>Fecha:</strong> {{ $consulta->created_at->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td><strong>Cédula:</strong> {{ $consulta->paciente->cedula }}</td>
                <td><strong>Edad:</strong> {{ \Carbon\Carbon::parse($consulta->paciente->fecha_nacimiento)->age }} años</td>
            </tr>
            <tr>
                <td colspan="2" style="padding-top: 10px;"><strong>Diagnóstico:</strong> {{ $consulta->diagnostico_confirmado }}</td>
            </tr>
        </table>
    </div>

    {{-- Título --}}
    <h3 style="color: #4f46e5; margin-bottom: 5px;">RECETA MÉDICA / PLAN TERAPÉUTICO</h3>

    {{-- Tabla de Medicamentos --}}
    <table class="recipe-table">
        <thead>
            <tr>
                <th width="40%">Medicamento / Concentración</th>
                <th width="60%">Indicaciones (Dosis, Frecuencia, Duración)</th>
            </tr>
        </thead>
        <tbody>
            @if($consulta->receta_medica)
                @foreach($consulta->receta_medica as $item)
                    <tr>
                        <td class="medicine">{{ $item['medicamento'] ?? '-' }}</td>
                        <td class="indication">{{ $item['indicacion'] ?? '-' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="2" style="text-align: center; padding: 20px;">No se registraron medicamentos en esta consulta.</td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- Notas Adicionales --}}
    @if($consulta->exploracion_fisica)
    <div style="margin-top: 30px; font-size: 12px; color: #666;">
        <strong>Nota de Exploración:</strong> {{ $consulta->exploracion_fisica }}
    </div>
    @endif

    {{-- Firma del Doctor --}}
    <div class="doctor-sign">
        <strong>Dr. {{ $consulta->doctor->usuario->name }}</strong><br>
        {{ $consulta->doctor->especialidad }}
    </div>

    <div class="footer">
        Receta generada electrónicamente por Sistema SIGLC. Valide esta receta con su farmacéutico.
    </div>
</body>
</html>